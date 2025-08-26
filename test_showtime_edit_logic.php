<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Showtime;
use App\Models\Ticket;
use App\Models\Movie;

echo "=== Test Logic Kiểm Tra Suất Chiếu Có Thể Chỉnh Sửa ===\n\n";

try {
    // Lấy một vài suất chiếu để test
    $showtimes = Showtime::with(['tickets', 'movie'])->take(5)->get();
    
    if ($showtimes->isEmpty()) {
        echo "Không có suất chiếu nào trong database để test.\n";
        exit;
    }
    
    foreach ($showtimes as $showtime) {
        echo "Suất chiếu ID: {$showtime->id}\n";
        echo "Phim: {$showtime->movie->name}\n";
        echo "Thời gian: {$showtime->start_time->format('d/m/Y H:i')} - {$showtime->end_time->format('d/m/Y H:i')}\n";
        echo "Trạng thái: {$showtime->status->value}\n";
        
        // Kiểm tra số vé đã đặt
        $bookedTicketsCount = $showtime->tickets()->whereNotNull('booking_id')->count();
        echo "Số vé đã đặt: {$bookedTicketsCount}\n";
        
        // Kiểm tra có thể chỉnh sửa không
        $canEdit = $showtime->canBeEdited();
        echo "Có thể chỉnh sửa: " . ($canEdit ? 'CÓ' : 'KHÔNG') . "\n";
        
        // Lý do không thể chỉnh sửa
        if (!$canEdit) {
            if ($showtime->hasBookedTickets()) {
                echo "Lý do: Đã có vé được đặt\n";
            } elseif (in_array($showtime->status->value, ['completed', 'cancelled', 'ongoing'])) {
                echo "Lý do: Trạng thái suất chiếu là {$showtime->status->value}\n";
            }
        }
        
        echo "-----------------------------------\n\n";
    }
    
    echo "Test hoàn thành!\n";
    
} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " (Line: " . $e->getLine() . ")\n";
}
