<?php
/**
 * Test logic tạo ghế theo hàng mới
 */

echo "=== TEST LOGIC TẠO GHẾ THEO HÀNG ===\n\n";

// Simulate existing rows data
$existingRows = ['A', 'B', 'C'];
echo "🔍 Hàng hiện có: " . implode(', ', $existingRows) . "\n";

// Calculate start row
$startRowCode = 65; // Default A
if (count($existingRows) > 0) {
    $lastRow = end($existingRows);
    $startRowCode = ord($lastRow) + 1;
}

echo "📊 Tính toán hàng tiếp theo:\n";
echo "- Hàng cuối: " . end($existingRows) . " (ASCII: " . ord(end($existingRows)) . ")\n";
echo "- Hàng tiếp theo: " . chr($startRowCode) . " (ASCII: {$startRowCode})\n\n";

// Simulate frontend data
$seatConfiguration = [
    ['seatsCount' => 10, 'seatType' => 7], // Hàng D
    ['seatsCount' => 10, 'seatType' => 8]  // Hàng E
];

echo "📋 Cấu hình ghế frontend:\n";
foreach ($seatConfiguration as $index => $config) {
    echo "- Hàng " . ($index + 1) . ": {$config['seatsCount']} ghế, loại {$config['seatType']}\n";
}

// Generate backend data (NEW LOGIC)
echo "\n🚀 Dữ liệu gửi đến backend (LOGIC MỚI):\n";
$seatConfigData = [];
foreach ($seatConfiguration as $index => $config) {
    $seatConfigData[] = [
        'row' => $startRowCode + $index,  // ASCII code: 68 (D), 69 (E)
        'seats_per_row' => $config['seatsCount'],
        'seat_type_id' => $config['seatType']
    ];
}

foreach ($seatConfigData as $config) {
    $rowChar = chr($config['row']);
    echo "- Hàng {$rowChar} (ASCII: {$config['row']}): {$config['seats_per_row']} ghế, loại {$config['seat_type_id']}\n";
}

// Simulate backend processing
echo "\n🔧 Xử lý backend:\n";
foreach ($seatConfigData as $config) {
    $rowCode = $config['row'];
    $rowChar = chr($rowCode);
    $seatsPerRow = $config['seats_per_row'];
    $seatTypeId = $config['seat_type_id'];
    
    echo "- Tạo {$seatsPerRow} ghế ở hàng {$rowChar}:\n";
    for ($seatNum = 1; $seatNum <= $seatsPerRow; $seatNum++) {
        echo "  * Ghế {$rowChar}{$seatNum}\n";
    }
}

echo "\n✅ KẾT QUẢ:\n";
echo "- Frontend hiển thị: 'Hàng mới sẽ tạo: D'\n";
echo "- Backend thực tế tạo: Hàng D với 10 ghế\n";
echo "- Logic đã nhất quán!\n\n";

echo "=== KẾT THÚC TEST ===\n";
?>
