<?php

namespace App\Services;

use App\Models\BookingAttempt;
use App\Models\UserBookingBan;
use App\Models\User;
use App\Enums\BookingAttemptStatus;
use Illuminate\Support\Facades\Log;

class BookingAttemptService
{
    const MAX_FAILED_ATTEMPTS = 3;      // Số lần tối đa
    const BAN_DURATION_DAYS = 7;        // Số ngày cấm
    const ATTEMPT_TIMEOUT_MINUTES = 10; // Thời gian giữ ghế (phút)

    /**
     * Tạo một booking attempt mới hoặc cập nhật attempt hiện tại
     */
    public function createAttempt(int $userId, int $showtimeId, array $seatIds): BookingAttempt
    {
        // Kiểm tra xem có attempt đang active cho cùng user và showtime không
        $existingAttempt = BookingAttempt::where('user_id', $userId)
            ->where('showtime_id', $showtimeId)
            ->where('status', BookingAttemptStatus::Reserved)
            ->first();

        if ($existingAttempt) {
            // Cập nhật attempt hiện tại với danh sách ghế mới
            $existingAttempt->update([
                'seat_ids' => $seatIds,
                'reserved_at' => now(),
                'expired_at' => now()->addMinutes(self::ATTEMPT_TIMEOUT_MINUTES),
            ]);
            
            Log::info("Updated existing booking attempt {$existingAttempt->id} for user {$userId} with new seats");
            return $existingAttempt;
        }

        // Hủy các attempt đang active của user cho cùng showtime (từ sessions khác)
        $this->cancelActiveAttempts($userId, $showtimeId, false); // false = không check ban

        $attempt = BookingAttempt::create([
            'user_id' => $userId,
            'showtime_id' => $showtimeId,
            'seat_ids' => $seatIds,
            'status' => BookingAttemptStatus::Reserved,
            'reserved_at' => now(),
            'expired_at' => now()->addMinutes(self::ATTEMPT_TIMEOUT_MINUTES),
        ]);

        Log::info("Created new booking attempt {$attempt->id} for user {$userId}");
        return $attempt;
    }

    /**
     * Hủy các attempt đang active
     */
    public function cancelActiveAttempts(int $userId, int $showtimeId, bool $checkBan = true): void
    {
        $cancelledCount = BookingAttempt::where('user_id', $userId)
            ->where('showtime_id', $showtimeId)
            ->where('status', BookingAttemptStatus::Reserved)
            ->update(['status' => BookingAttemptStatus::Cancelled]);
            
        if ($cancelledCount > 0 && $checkBan) {
            // Chỉ kiểm tra và ban user nếu được yêu cầu (không phải khi đang cập nhật attempt)
            $this->checkAndBanUser($userId);
        }
    }

    /**
     * Hủy TẤT CẢ các attempt đang reserved của user (khi reset/reload page)
     */
    public function cancelAllActiveAttempts(int $userId): int
    {
        $cancelledCount = BookingAttempt::where('user_id', $userId)
            ->where('status', BookingAttemptStatus::Reserved)
            ->update(['status' => BookingAttemptStatus::Cancelled]);

        if ($cancelledCount > 0) {
            Log::info("Auto-cancelled {$cancelledCount} reserved attempts for user {$userId} due to page reload/reset");
            
            // Kiểm tra và ban user nếu cần thiết sau khi cancel
            $this->checkAndBanUser($userId);
        }

        return $cancelledCount;
    }

    /**
     * Đánh dấu attempt là completed khi thanh toán thành công
     */
    public function completeAttempt(int $userId, int $showtimeId): void
    {
        $attempt = BookingAttempt::where('user_id', $userId)
            ->where('showtime_id', $showtimeId)
            ->where('status', BookingAttemptStatus::Reserved)
            ->first();

        if ($attempt) {
            $attempt->markAsCompleted();
            Log::info("Completed booking attempt {$attempt->id} for user {$userId}");
        }
    }

    /**
     * Xử lý timeout cho các attempt hết hạn
     */
    public function processTimeoutAttempts(): array
    {
        // LUÔN cleanup expired attempts trước khi xử lý timeout
        $this->autoCleanupExpiredAttempts();
        
        $expiredAttempts = BookingAttempt::where('status', BookingAttemptStatus::Reserved)
            ->where('expired_at', '<=', now())
            ->get();

        $processed = 0;
        $bansCreated = 0;

        foreach ($expiredAttempts as $attempt) {
            $attempt->markAsTimeout();
            Log::info("Timeout booking attempt {$attempt->id} for user {$attempt->user_id}");
            $processed++;
            
            // Kiểm tra và cấm user nếu cần
            $banned = $this->checkAndBanUser($attempt->user_id);
            if ($banned) {
                $bansCreated++;
            }
        }

        return [
            'processed' => $processed,
            'bans_created' => $bansCreated
        ];
    }

    /**
     * Tự động cleanup dữ liệu cũ (luôn chạy)
     */
    private function autoCleanupExpiredAttempts(): void
    {
        try {
            // Xóa ngay tất cả attempts đã expired (timeout/cancelled)
            $deletedCount = BookingAttempt::whereIn('status', [
                    BookingAttemptStatus::Timeout, 
                    BookingAttemptStatus::Cancelled
                ])
                ->where('expired_at', '<=', now())
                ->delete();

            if ($deletedCount > 0) {
                Log::info("Auto-cleaned up {$deletedCount} expired booking attempts");
            }
        } catch (\Exception $e) {
            Log::warning("Failed to auto-cleanup expired booking attempts: " . $e->getMessage());
        }
    }

    /**
     * Tự động cleanup dữ liệu cũ (gọi khi xử lý timeout)
     */
    private function autoCleanupOldAttempts(): void
    {
        try {
            // Chỉ cleanup ngẫu nhiên (1/5 lần) để tránh làm chậm hệ thống
            if (rand(1, 5) === 1) {
                // Xóa ngay các attempts đã hết hạn (không cần chờ)
                $deletedExpired = BookingAttempt::whereIn('status', [
                        BookingAttemptStatus::Timeout, 
                        BookingAttemptStatus::Cancelled
                    ])
                    ->where('expired_at', '<=', now())
                    ->delete();

                // Xóa các attempts completed cũ hơn 7 ngày
                $deletedCompleted = BookingAttempt::where('status', BookingAttemptStatus::Completed)
                    ->where('completed_at', '<=', now()->subDays(7))
                    ->delete();

                if ($deletedExpired > 0 || $deletedCompleted > 0) {
                    Log::info("Auto-cleaned up {$deletedExpired} expired attempts and {$deletedCompleted} completed attempts");
                }
            }
        } catch (\Exception $e) {
            Log::warning("Failed to auto-cleanup old booking attempts: " . $e->getMessage());
        }
    }

    /**
     * Kiểm tra và cấm user nếu có quá nhiều lần thất bại
     */
    public function checkAndBanUser(int $userId): bool
    {
        // Đếm số lần thất bại trong 24h gần nhất
        $failedCount = BookingAttempt::where('user_id', $userId)
            ->failed()
            ->where('reserved_at', '>=', now()->subDay())
            ->count();

        Log::info("User {$userId} has {$failedCount} failed attempts in last 24h");

        if ($failedCount >= self::MAX_FAILED_ATTEMPTS) {
            $this->banUser($userId, $failedCount);
            return true;
        }
        
        return false;
    }

    /**
     * Force check và ban user (dùng khi cần kiểm tra manual)
     */
    public function forceCheckAndBanUser(int $userId): bool
    {
        Log::info("Force checking ban status for user {$userId}");
        return $this->checkAndBanUser($userId);
    }

    /**
     * Cấm user đặt vé
     */
    public function banUser(int $userId, int $failedAttempts): void
    {
        // Kiểm tra xem user đã bị cấm chưa
        $existingBan = UserBookingBan::where('user_id', $userId)
            ->active()
            ->first();

        if ($existingBan) {
            Log::info("User {$userId} is already banned until {$existingBan->banned_until}");
            return;
        }

        $ban = UserBookingBan::create([
            'user_id' => $userId,
            'failed_attempts' => $failedAttempts,
            'banned_at' => now(),
            'banned_until' => now()->addDays(self::BAN_DURATION_DAYS),
            'is_active' => true,
            'reason' => "Tự động cấm do đặt ghế {$failedAttempts} lần liên tiếp mà không thanh toán trong 24h gần nhất",
        ]);

        Log::warning("Banned user {$userId} for {$failedAttempts} failed booking attempts. Ban ID: {$ban->id}");
    }

    /**
     * Kiểm tra user có bị cấm không
     */
    public function isUserBanned(int $userId): bool
    {
        return UserBookingBan::where('user_id', $userId)
            ->active()
            ->exists();
    }

    /**
     * Lấy thông tin ban của user
     */
    public function getUserBanInfo(int $userId): ?UserBookingBan
    {
        return UserBookingBan::where('user_id', $userId)
            ->active()
            ->first();
    }

    /**
     * Hủy ban cho user (admin có thể dùng)
     */
    public function unbanUser(int $userId): bool
    {
        $ban = UserBookingBan::where('user_id', $userId)
            ->active()
            ->first();

        if ($ban) {
            $ban->expire();
            Log::info("Unbanned user {$userId}");
            return true;
        }

        return false;
    }

    /**
     * Cleanup các ban đã hết hạn
     */
    public function cleanupExpiredBans(): void
    {
        UserBookingBan::expired()
            ->where('is_active', true)
            ->update(['is_active' => false]);
    }

    /**
     * Lấy thống kê attempts của user
     */
    public function getUserAttemptStats(int $userId): array
    {
        $totalAttempts = BookingAttempt::where('user_id', $userId)->count();
        $failedAttempts = BookingAttempt::where('user_id', $userId)->failed()->count();
        $successfulAttempts = BookingAttempt::where('user_id', $userId)
            ->where('status', BookingAttemptStatus::Completed)
            ->count();

        $recentFailedAttempts = BookingAttempt::where('user_id', $userId)
            ->failed()
            ->where('reserved_at', '>=', now()->subDay())
            ->count();

        return [
            'total_attempts' => $totalAttempts,
            'failed_attempts' => $failedAttempts,
            'successful_attempts' => $successfulAttempts,
            'recent_failed_attempts' => $recentFailedAttempts,
            'success_rate' => $totalAttempts > 0 ? round(($successfulAttempts / $totalAttempts) * 100, 2) : 0,
        ];
    }

    /**
     * Xóa các booking attempts cũ đã hết hạn
     * Chỉ xóa các attempts đã hết hạn sau một khoảng thời gian nhất định
     */
    public function cleanupExpiredAttempts(int $daysOld = 0): array
    {
        if ($daysOld == 0) {
            // Xóa ngay khi hết hạn
            $cutoffDate = now();
        } else {
            // Xóa sau X ngày
            $cutoffDate = now()->subDays($daysOld);
        }
        
        // Xóa các attempts đã timeout/cancelled và đã hết hạn
        $deletedTimeouts = BookingAttempt::whereIn('status', [
                BookingAttemptStatus::Timeout, 
                BookingAttemptStatus::Cancelled
            ])
            ->where('expired_at', '<=', $cutoffDate)
            ->count();
            
        BookingAttempt::whereIn('status', [
                BookingAttemptStatus::Timeout, 
                BookingAttemptStatus::Cancelled
            ])
            ->where('expired_at', '<=', $cutoffDate)
            ->delete();

        // Xóa các attempts completed cũ (sau 7 ngày để giữ lại cho báo cáo)
        $completedCutoff = $daysOld == 0 ? now()->subDays(7) : $cutoffDate;
        $deletedCompleted = BookingAttempt::where('status', BookingAttemptStatus::Completed)
            ->where('completed_at', '<=', $completedCutoff)
            ->count();
            
        BookingAttempt::where('status', BookingAttemptStatus::Completed)
            ->where('completed_at', '<=', $completedCutoff)
            ->delete();

        Log::info("Cleaned up {$deletedTimeouts} timeout/cancelled attempts and {$deletedCompleted} completed attempts (daysOld: {$daysOld})");

        return [
            'deleted_timeouts' => $deletedTimeouts,
            'deleted_completed' => $deletedCompleted,
            'total_deleted' => $deletedTimeouts + $deletedCompleted
        ];
    }
}
