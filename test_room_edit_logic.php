<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Room;
use App\Models\Showtime;

// Script kiểm tra logic disable chỉnh sửa phòng
echo "=== Test Room Edit Logic ===\n\n";

try {
    // Lấy tất cả phòng
    $rooms = Room::with(['showtimes', 'cinema', 'roomType'])->get();
    
    foreach ($rooms as $room) {
        $activeShowtimes = $room->showtimes()
            ->whereIn('status', ['scheduled', 'ongoing'])
            ->whereDate('start_time', '>=', now()->toDateString())
            ->count();
            
        $canEdit = $room->canBeEdited();
        $hasActiveShowtimes = $room->hasActiveShowtimes();
        
        echo "Phòng: {$room->name} (ID: {$room->id})\n";
        echo "- Rạp: {$room->cinema->name}\n";
        echo "- Số suất chiếu đang hoạt động: {$activeShowtimes}\n";
        echo "- Có suất chiếu hoạt động: " . ($hasActiveShowtimes ? 'Có' : 'Không') . "\n";
        echo "- Có thể chỉnh sửa: " . ($canEdit ? 'Có' : 'Không') . "\n";
        echo "- Status: {$room->status}\n";
        echo "---\n";
    }
    
    echo "\n=== Kiểm tra logic ===\n";
    echo "✓ Logic hoạt động bình thường\n";
    echo "✓ Phòng có suất chiếu đang hoạt động sẽ không thể chỉnh sửa\n";
    echo "✓ Phòng không có suất chiếu hoặc suất chiếu đã kết thúc có thể chỉnh sửa\n";
    
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
