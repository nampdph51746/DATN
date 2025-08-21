<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestSeatLimit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:seat-limit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test seat limit functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== TEST SEAT LIMIT FUNCTIONALITY ===');
        $this->newLine();

        // Test config
        $this->info('1. Kiểm tra config giới hạn ghế:');
        $maxSeats = config('booking.max_seats_per_booking', 8);
        $this->line("- Max seats per booking: {$maxSeats}");
        $this->newLine();

        // Test validation logic
        $this->info('2. Kiểm tra logic validation:');
        $testCases = [
            ['seat_count' => 5, 'expected' => 'PASS'],
            ['seat_count' => 8, 'expected' => 'PASS'],
            ['seat_count' => 9, 'expected' => 'FAIL'],
            ['seat_count' => 10, 'expected' => 'FAIL'],
        ];

        foreach ($testCases as $test) {
            $result = $test['seat_count'] <= $maxSeats ? 'PASS' : 'FAIL';
            $status = $result === $test['expected'] ? '✅' : '❌';
            $this->line("- {$test['seat_count']} ghế: {$result} {$status}");
        }

        $this->newLine();
        $this->info('3. Test server-side validation (simulation):');

        $testSeatLists = [
            [1, 2, 3, 4, 5],        // 5 ghế
            [1, 2, 3, 4, 5, 6, 7, 8], // 8 ghế  
            [1, 2, 3, 4, 5, 6, 7, 8, 9], // 9 ghế
        ];

        foreach ($testSeatLists as $index => $seatIds) {
            $result = $this->validateSeatLimit($seatIds, $maxSeats);
            $count = count($seatIds);
            $this->line("- Test " . ($index + 1) . " ({$count} ghế): " . 
                ($result['status'] === 200 ? "✅ PASS - {$result['message']}" : "❌ FAIL - {$result['message']}"));
        }

        $this->newLine();
        $this->info('4. Kiểm tra config file tồn tại:');
        $configPath = config_path('booking.php');
        if (file_exists($configPath)) {
            $this->line("✅ Config file exists: {$configPath}");
            $config = include $configPath;
            $this->line("- Max seats setting: " . ($config['max_seats_per_booking'] ?? 'NOT SET'));
        } else {
            $this->line("❌ Config file not found: {$configPath}");
        }

        $this->newLine();
        $this->info('=== TEST COMPLETED ===');
    }

    private function validateSeatLimit($seatIds, $maxSeats)
    {
        if (count($seatIds) > $maxSeats) {
            return [
                'error' => 'Vượt quá giới hạn đặt ghế',
                'message' => "Bạn chỉ có thể đặt tối đa {$maxSeats} ghế trong 1 lần đặt vé",
                'status' => 400
            ];
        }
        return ['status' => 200, 'message' => 'OK'];
    }
}
