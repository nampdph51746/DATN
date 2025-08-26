<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Room;
use App\Models\Seat;
use App\Models\SeatType;
use App\Models\Cinema;
use App\Models\RoomType;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeatImportLogicTest extends TestCase
{
    use RefreshDatabase;

    protected $room;
    protected $seatType;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Tạo test data
        $cinema = Cinema::factory()->create();
        $roomType = RoomType::factory()->create();
        $this->room = Room::factory()->create([
            'cinema_id' => $cinema->id,
            'room_type_id' => $roomType->id,
            'capacity' => 50
        ]);
        
        $this->seatType = SeatType::factory()->create(['name' => 'VIP']);
    }

    /** @test */
    public function it_only_creates_seats_in_empty_positions()
    {
        // Tạo ghế đã tồn tại
        Seat::create([
            'room_id' => $this->room->id,
            'row_char' => 'A',
            'seat_number' => '01',
            'seat_type_id' => $this->seatType->id,
            'status' => 'available'
        ]);

        // Tạo file CSV với ghế đã tồn tại và ghế mới
        $csvContent = "row_char,seat_number,seat_type,status\nA,01,VIP,available\nA,02,VIP,available";
        $file = UploadedFile::fake()->createWithContent('test.csv', $csvContent);

        // Gọi import
        $response = $this->post(route('admin.seats.import'), [
            'room_id' => $this->room->id,
            'excel_file' => $file
        ]);

        // Kiểm tra kết quả
        $this->assertEquals(2, Seat::where('room_id', $this->room->id)->count()); // Chỉ có 2 ghế
        
        // Ghế A01 không bị thay đổi
        $existingSeat = Seat::where([
            'room_id' => $this->room->id,
            'row_char' => 'A',
            'seat_number' => '01'
        ])->first();
        $this->assertNotNull($existingSeat);
        
        // Ghế A02 được tạo mới
        $newSeat = Seat::where([
            'room_id' => $this->room->id,
            'row_char' => 'A',
            'seat_number' => '02'
        ])->first();
        $this->assertNotNull($newSeat);
        
        // Kiểm tra session message
        $response->assertSessionHas('import_success');
        $message = session('import_success');
        $this->assertStringContainsString('1 ghế', $message); // 1 ghế được import
        $this->assertStringContainsString('1 vị trí', $message); // 1 vị trí bị bỏ qua
    }

    /** @test */
    public function it_handles_all_existing_positions()
    {
        // Tạo tất cả ghế trong file
        Seat::create([
            'room_id' => $this->room->id,
            'row_char' => 'A',
            'seat_number' => '01',
            'seat_type_id' => $this->seatType->id,
            'status' => 'available'
        ]);
        
        Seat::create([
            'room_id' => $this->room->id,
            'row_char' => 'A',
            'seat_number' => '02',
            'seat_type_id' => $this->seatType->id,
            'status' => 'available'
        ]);

        // File CSV với tất cả ghế đã tồn tại
        $csvContent = "row_char,seat_number,seat_type,status\nA,01,VIP,available\nA,02,VIP,available";
        $file = UploadedFile::fake()->createWithContent('test.csv', $csvContent);

        $response = $this->post(route('admin.seats.import'), [
            'room_id' => $this->room->id,
            'excel_file' => $file
        ]);

        // Kiểm tra không có ghế mới nào được tạo
        $this->assertEquals(2, Seat::where('room_id', $this->room->id)->count());
        
        // Kiểm tra message
        $response->assertSessionHas('import_success');
        $message = session('import_success');
        $this->assertStringContainsString('Không có ghế nào được thêm', $message);
    }

    /** @test */
    public function it_supports_excel_format()
    {
        // Test với file Excel (thực tế sẽ cần file Excel thật)
        $this->markTestSkipped('Cần file Excel thật để test');
    }

    /** @test */
    public function it_validates_file_format()
    {
        $invalidFile = UploadedFile::fake()->create('test.txt', 100);

        $response = $this->post(route('admin.seats.import'), [
            'room_id' => $this->room->id,
            'excel_file' => $invalidFile
        ]);

        $response->assertSessionHasErrors(['excel_file']);
    }

    /** @test */
    public function it_handles_invalid_seat_type()
    {
        $csvContent = "row_char,seat_number,seat_type,status\nA,01,INVALID_TYPE,available";
        $file = UploadedFile::fake()->createWithContent('test.csv', $csvContent);

        $response = $this->post(route('admin.seats.import'), [
            'room_id' => $this->room->id,
            'excel_file' => $file
        ]);

        // Kiểm tra có lỗi về seat_type_id
        $response->assertSessionHas('import_error');
        $this->assertEquals(0, Seat::where('room_id', $this->room->id)->count());
    }
}
