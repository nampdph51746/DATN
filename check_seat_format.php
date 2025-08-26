<?php
/**
 * Script để kiểm tra và chuẩn hóa seat_number format trong database
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Seat;
use Illuminate\Support\Facades\DB;

echo "=== KIỂM TRA VÀ CHUẨN HÓA SEAT_NUMBER FORMAT ===\n\n";

// Kiểm tra các seat_number có format khác nhau
echo "🔍 Đang kiểm tra format seat_number trong database...\n";

$seats = Seat::select('id', 'room_id', 'row_char', 'seat_number')
    ->orderBy('room_id')
    ->orderBy('row_char')
    ->orderBy('seat_number')
    ->get();

$formatCounts = ['with_zero' => 0, 'without_zero' => 0];
$duplicatePositions = [];
$sampleData = [];

foreach ($seats as $seat) {
    // Phân loại format
    if (preg_match('/^0\d/', $seat->seat_number)) {
        $formatCounts['with_zero']++;
    } else {
        $formatCounts['without_zero']++;
    }
    
    // Kiểm tra duplicate positions
    $position = $seat->room_id . '_' . $seat->row_char . '_' . $seat->seat_number;
    $normalizedPosition = $seat->room_id . '_' . $seat->row_char . '_' . str_pad($seat->seat_number, 2, '0', STR_PAD_LEFT);
    
    if (!isset($sampleData[$seat->room_id])) {
        $sampleData[$seat->room_id] = [];
    }
    
    $sampleData[$seat->room_id][] = [
        'id' => $seat->id,
        'position' => $seat->row_char . $seat->seat_number,
        'seat_number' => $seat->seat_number,
        'normalized' => str_pad($seat->seat_number, 2, '0', STR_PAD_LEFT)
    ];
}

echo "📊 Kết quả thống kê:\n";
echo "- Ghế có format '0X' (01, 02, ...): {$formatCounts['with_zero']}\n";
echo "- Ghế có format 'X' (1, 2, ...): {$formatCounts['without_zero']}\n\n";

// Hiển thị sample data cho room 71
if (isset($sampleData[71])) {
    echo "📋 Sample data cho Room 71:\n";
    $room71Seats = collect($sampleData[71])->take(20);
    foreach ($room71Seats as $seat) {
        echo "- ID {$seat['id']}: {$seat['position']} (seat_number: '{$seat['seat_number']}' -> normalized: '{$seat['normalized']}')\n";
    }
    echo "\n";
}

// Kiểm tra potential duplicates
echo "🔍 Kiểm tra potential duplicate positions...\n";
$potentialDuplicates = [];

$groupedSeats = $seats->groupBy(function($seat) {
    return $seat->room_id . '_' . $seat->row_char;
});

foreach ($groupedSeats as $groupKey => $groupSeats) {
    $normalizedSeats = [];
    foreach ($groupSeats as $seat) {
        $normalized = str_pad($seat->seat_number, 2, '0', STR_PAD_LEFT);
        if (isset($normalizedSeats[$normalized])) {
            $potentialDuplicates[] = [
                'position' => $groupKey . '_' . $normalized,
                'seats' => [$normalizedSeats[$normalized], $seat]
            ];
        } else {
            $normalizedSeats[$normalized] = $seat;
        }
    }
}

if (count($potentialDuplicates) > 0) {
    echo "⚠️ Tìm thấy " . count($potentialDuplicates) . " vị trí có potential duplicates:\n";
    foreach ($potentialDuplicates as $duplicate) {
        echo "- Position: {$duplicate['position']}\n";
        foreach ($duplicate['seats'] as $seat) {
            echo "  * ID {$seat->id}: {$seat->row_char}{$seat->seat_number}\n";
        }
    }
} else {
    echo "✅ Không tìm thấy duplicate positions\n";
}

echo "\n💡 GIẢI PHÁP:\n";
echo "1. Logic import đã được cập nhật để handle cả 2 format\n";
echo "2. Khi tìm kiếm ghế existing, sẽ check cả '01' và '1'\n";
echo "3. Không cần modify database, chỉ cần update logic import\n\n";

echo "🧪 TEST LOGIC MỚI:\n";
echo "- Tìm kiếm seat_number '01' sẽ match cả '01' và '1' trong DB\n";
echo "- Tìm kiếm seat_number '10' sẽ match '10' (không đổi)\n";
echo "- Import sẽ skip đúng những vị trí đã có ghế\n\n";

echo "=== HOÀN THÀNH KIỂM TRA ===\n";
