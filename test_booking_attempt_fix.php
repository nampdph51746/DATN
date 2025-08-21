<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Showtime;
use App\Services\BookingAttemptService;
use App\Models\BookingAttempt;
use App\Enums\BookingAttemptStatus;

echo "=== TEST BOOKING ATTEMPT FIX ===\n";

$service = app(BookingAttemptService::class);

// Tạo user test
$user = User::factory()->create([
    'email' => 'test_attempt_' . time() . '@example.com'
]);

// Tìm một showtime có sẵn
$showtime = Showtime::first();
if (!$showtime) {
    echo "❌ Không tìm thấy showtime nào\n";
    exit;
}

echo "👤 User ID: {$user->id}\n";
echo "🎬 Showtime ID: {$showtime->id}\n";

// Test 1: Tạo attempt đầu tiên
echo "\n--- Test 1: Tạo attempt đầu tiên ---\n";
$attempt1 = $service->createAttempt($user->id, $showtime->id, [1, 2]);
echo "✅ Attempt 1 tạo thành công: {$attempt1->id}\n";
echo "Seat IDs: " . implode(', ', $attempt1->seat_ids) . "\n";

// Kiểm tra database
$count = BookingAttempt::where('user_id', $user->id)
    ->where('status', BookingAttemptStatus::Reserved)
    ->count();
echo "📊 Số attempt đang reserved: {$count}\n";

// Test 2: Cập nhật attempt với ghế mới (như khi user chọn thêm ghế)
echo "\n--- Test 2: Cập nhật attempt với ghế mới ---\n";
$attempt2 = $service->createAttempt($user->id, $showtime->id, [1, 2, 3]);
echo "✅ Attempt 2 (cập nhật): {$attempt2->id}\n";
echo "Seat IDs: " . implode(', ', $attempt2->seat_ids) . "\n";

// Kiểm tra xem có phải là cùng attempt không
if ($attempt1->id === $attempt2->id) {
    echo "✅ PASS: Cùng một attempt được cập nhật\n";
} else {
    echo "❌ FAIL: Tạo attempt mới thay vì cập nhật\n";
}

// Kiểm tra database sau cập nhật
$count = BookingAttempt::where('user_id', $user->id)
    ->where('status', BookingAttemptStatus::Reserved)
    ->count();
echo "📊 Số attempt đang reserved sau cập nhật: {$count}\n";

// Test 3: Thêm ghế nữa
echo "\n--- Test 3: Thêm ghế nữa ---\n";
$attempt3 = $service->createAttempt($user->id, $showtime->id, [1, 2, 3, 4]);
echo "✅ Attempt 3 (cập nhật): {$attempt3->id}\n";
echo "Seat IDs: " . implode(', ', $attempt3->seat_ids) . "\n";

// Kiểm tra xem vẫn là cùng attempt không
if ($attempt1->id === $attempt3->id) {
    echo "✅ PASS: Vẫn cùng một attempt được cập nhật\n";
} else {
    echo "❌ FAIL: Tạo attempt mới thay vì cập nhật\n";
}

// Kiểm tra database cuối cùng
$count = BookingAttempt::where('user_id', $user->id)
    ->where('status', BookingAttemptStatus::Reserved)
    ->count();
echo "📊 Số attempt đang reserved cuối cùng: {$count}\n";

// Kiểm tra tổng số attempt (bao gồm cancelled)
$totalCount = BookingAttempt::where('user_id', $user->id)->count();
echo "📊 Tổng số attempt trong DB: {$totalCount}\n";

// Kiểm tra số failed attempts
$failedCount = BookingAttempt::where('user_id', $user->id)
    ->failed()
    ->count();
echo "📊 Số failed attempts: {$failedCount}\n";

// Test check ban
echo "\n--- Test 4: Kiểm tra trạng thái ban ---\n";
$isBanned = $service->isUserBanned($user->id);
echo "🚫 User có bị ban không: " . ($isBanned ? "CÓ" : "KHÔNG") . "\n";

echo "\n=== KẾT THÚC TEST ===\n";

// Cleanup
$user->delete();
?>
