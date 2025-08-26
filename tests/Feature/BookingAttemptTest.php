<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Showtime;
use App\Services\BookingAttemptService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingAttemptTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_attempt_service_can_be_instantiated()
    {
        $service = app(BookingAttemptService::class);
        $this->assertInstanceOf(BookingAttemptService::class, $service);
    }

    public function test_can_create_booking_attempt()
    {
        $user = User::factory()->create();
        $showtime = Showtime::factory()->create();
        
        $service = app(BookingAttemptService::class);
        
        $attempt = $service->createAttempt($user->id, $showtime->id, [1, 2, 3]);
        
        $this->assertDatabaseHas('booking_attempts', [
            'user_id' => $user->id,
            'showtime_id' => $showtime->id,
            'status' => 'reserved'
        ]);
    }

    public function test_user_ban_check()
    {
        $user = User::factory()->create();
        
        $service = app(BookingAttemptService::class);
        
        // Initially not banned
        $this->assertFalse($service->isUserBanned($user->id));
        
        // Ban user manually
        $service->banUser($user->id, 3);
        
        // Now should be banned
        $this->assertTrue($service->isUserBanned($user->id));
    }
}
