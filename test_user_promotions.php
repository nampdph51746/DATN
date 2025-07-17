<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Promotion;
use App\Models\User;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test Current User & Available Promotions ===\n";

// Giả lập user 692 (từ debug trước)
$user = User::find(692);
if ($user) {
    echo "Found user:\n";
    echo "- ID: {$user->id}\n";
    echo "- Name: {$user->name}\n";
    echo "- Email: {$user->email}\n";
    echo "- Rank ID: {$user->customer_rank_id}\n\n";
    
    // Lấy danh sách promotion available theo logic trong HomeController
    $now = now();
    $userRankId = $user->customer_rank_id;
    
    echo "=== Available Promotions for Rank {$userRankId} ===\n";
    
    // Logic từ getUserRankPromotions
    $promotions = Promotion::with('rank')
        ->where('status', 'active')
        ->where('start_date', '<=', $now)
        ->where('end_date', '>=', $now)
        ->where('quantity', '>', 0)
        ->where('rank_id', $userRankId)
        ->whereNotExists(function ($query) use ($user) {
            $query->select(DB::raw(1))
                ->from('bookings')
                ->whereColumn('bookings.promotion_id', 'promotions.id')
                ->where('bookings.user_id', $user->id)
                ->where('bookings.status', 'confirmed');
        })
        ->orderBy('discount_value', 'desc')
        ->get();
    
    echo "Found " . $promotions->count() . " promotions:\n";
    foreach ($promotions as $promotion) {
        echo "- ID: {$promotion->id}, Code: {$promotion->code}, Name: {$promotion->name}\n";
    }
    
    // Kiểm tra xem mã 2026 có trong danh sách không
    $promotion2026 = $promotions->firstWhere('code', '2026');
    if ($promotion2026) {
        echo "\n✅ Promotion 2026 IS shown in available list\n";
    } else {
        echo "\n❌ Promotion 2026 is NOT shown in available list\n";
    }
    
} else {
    echo "User 692 not found\n";
}

echo "\n=== End Test ===\n";
?>
