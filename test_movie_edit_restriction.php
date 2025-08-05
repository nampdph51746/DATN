<?php

// File test để kiểm tra logic ngăn chặn chỉnh sửa phim có vé đã đặt

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->bootstrap();

use App\Models\Movie;
use App\Models\Showtime;
use App\Models\Ticket;
use App\Models\Booking;

// Test logic kiểm tra phim có vé đã đặt
echo "=== Test Movie Edit Restriction Logic ===\n\n";

// Lấy một vài phim để test
$movies = Movie::with(['showtimes.tickets'])->take(3)->get();

foreach ($movies as $movie) {
    echo "Phim: {$movie->name}\n";
    echo "ID: {$movie->id}\n";
    echo "Trạng thái: " . (is_object($movie->status) ? $movie->status->value : $movie->status) . "\n";
    
    // Kiểm tra có suất chiếu không
    $showtimeCount = $movie->showtimes->count();
    echo "Số suất chiếu: {$showtimeCount}\n";
    
    // Kiểm tra có vé được đặt không
    $hasBookedTickets = $movie->hasBookedTickets();
    echo "Có vé đã đặt: " . ($hasBookedTickets ? 'CÓ' : 'KHÔNG') . "\n";
    
    // Kiểm tra có thể chỉnh sửa không
    $canBeEdited = $movie->canBeEdited();
    echo "Có thể chỉnh sửa: " . ($canBeEdited ? 'CÓ' : 'KHÔNG') . "\n";
    
    if (!$canBeEdited) {
        $movieStatus = is_object($movie->status) ? $movie->status->value : $movie->status;
        if ($movieStatus === 'ended') {
            echo "Lý do: Phim đã kết thúc\n";
        } elseif ($movieStatus === 'showing') {
            echo "Lý do: Phim đang chiếu\n";
        } elseif ($hasBookedTickets) {
            echo "Lý do: Phim đã có vé được đặt\n";
        }
    }
    
    echo "----------------------------------------\n";
}

echo "\n=== Test Complete ===\n";
