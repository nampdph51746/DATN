<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BookingAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DebugController extends Controller
{
    public function bookingAttempts(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $userId = Auth::id();
        $showtimeId = $request->input('showtime_id');

        $query = BookingAttempt::where('user_id', $userId);
        
        if ($showtimeId) {
            $query->where('showtime_id', $showtimeId);
        }

        $attempts = $query->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function($attempt) {
                return [
                    'id' => $attempt->id,
                    'showtime_id' => $attempt->showtime_id,
                    'status' => $attempt->status->value,
                    'created_at' => $attempt->created_at->format('Y-m-d H:i:s'),
                    'expired_at' => $attempt->expired_at?->format('Y-m-d H:i:s'),
                    'completed_at' => $attempt->completed_at?->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'user_id' => $userId,
            'showtime_id' => $showtimeId,
            'attempts' => $attempts
        ]);
    }
}
