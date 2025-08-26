<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Seat;

class CheckSeatFormat extends Command
{
    protected $signature = 'check:seat-format {room_id?}';
    protected $description = 'Check seat_number format consistency in database';

    public function handle()
    {
        $roomId = $this->argument('room_id');
        
        $this->info('=== KIỂM TRA FORMAT SEAT_NUMBER ===');
        
        $query = Seat::select('id', 'room_id', 'row_char', 'seat_number');
        
        if ($roomId) {
            $query->where('room_id', $roomId);
            $this->info("Kiểm tra Room ID: {$roomId}");
        }
        
        $seats = $query->orderBy('room_id')
            ->orderBy('row_char') 
            ->orderBy('seat_number')
            ->get();

        $formatCounts = ['with_zero' => 0, 'without_zero' => 0];
        $roomData = [];

        foreach ($seats as $seat) {
            // Phân loại format
            if (preg_match('/^0\d/', $seat->seat_number)) {
                $formatCounts['with_zero']++;
            } else {
                $formatCounts['without_zero']++;
            }
            
            if (!isset($roomData[$seat->room_id])) {
                $roomData[$seat->room_id] = [];
            }
            
            $roomData[$seat->room_id][] = [
                'id' => $seat->id,
                'position' => $seat->row_char . $seat->seat_number,
                'seat_number' => $seat->seat_number,
                'normalized' => str_pad($seat->seat_number, 2, '0', STR_PAD_LEFT)
            ];
        }

        $this->info('📊 Thống kê format:');
        $this->line("- Format '01', '02': {$formatCounts['with_zero']} ghế");
        $this->line("- Format '1', '2': {$formatCounts['without_zero']} ghế");
        
        if ($roomId && isset($roomData[$roomId])) {
            $this->info("\n📋 Chi tiết Room {$roomId}:");
            $seats = collect($roomData[$roomId])->take(10);
            foreach ($seats as $seat) {
                $this->line("ID {$seat['id']}: {$seat['position']} ('{$seat['seat_number']}' -> '{$seat['normalized']}')");
            }
            
            // Kiểm tra duplicates
            $this->info("\n🔍 Kiểm tra duplicates:");
            $normalized = collect($roomData[$roomId])->groupBy('normalized');
            $duplicates = $normalized->filter(function($group) {
                return $group->count() > 1;
            });
            
            if ($duplicates->count() > 0) {
                $this->warn("⚠️ Tìm thấy {$duplicates->count()} vị trí duplicate:");
                foreach ($duplicates as $position => $seats) {
                    $this->line("Position {$position}:");
                    foreach ($seats as $seat) {
                        $this->line("  - ID {$seat['id']}: {$seat['position']}");
                    }
                }
            } else {
                $this->info("✅ Không có duplicate trong room này");
            }
        }
        
        $this->info("\n✅ Logic import đã được cập nhật để handle cả 2 format!");
        
        return 0;
    }
}
