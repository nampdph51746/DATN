<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\CustomerRank;
use App\Models\Point;

// Route để liệt kê users có điểm
Route::get('/test-users-with-points', function () {
    $users = User::with(['points', 'customerRank'])->whereHas('points')->take(10)->get();
    
    $result = $users->map(function($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'points' => $user->points?->total_points ?? 0,
            'current_rank_id' => $user->customer_rank_id,
            'current_rank_name' => $user->customerRank?->name ?? 'None',
        ];
    });
    
    return response()->json([
        'users' => $result,
        'ranks' => CustomerRank::orderBy('min_points_required')->get()->map(function($rank) {
            return [
                'id' => $rank->id,
                'name' => $rank->name,
                'min_points_required' => $rank->min_points_required,
            ];
        })
    ]);
});

// Test route để kiểm tra logic cập nhật rank
Route::get('/test-rank-update/{userId}', function ($userId) {
    $user = User::with(['points', 'customerRank'])->find($userId);
    if (!$user) {
        return response()->json(['error' => 'User not found']);
    }

    // Lấy thông tin trước khi cập nhật
    $beforeUpdate = [
        'user_id' => $user->id,
        'current_rank_id' => $user->customer_rank_id,
        'current_rank_name' => $user->customerRank?->name ?? 'None',
        'total_points' => $user->points?->total_points ?? 0,
    ];

    // Debug: Log chi tiết
    \Log::info("Testing rank update for user {$user->id}", [
        'points' => $user->points?->total_points ?? 0,
        'current_rank' => $user->customer_rank_id
    ]);

    // Gọi hàm cập nhật rank
    $user->updateRankByTotalSpent();

    // Refresh lại user để lấy dữ liệu mới
    $user->refresh();

    // Lấy thông tin sau khi cập nhật
    $afterUpdate = [
        'user_id' => $user->id,
        'current_rank_id' => $user->customer_rank_id,
        'current_rank_name' => $user->customerRank?->name ?? 'None',
        'total_points' => $user->points?->total_points ?? 0,
    ];

    // Lấy danh sách ranks
    $ranks = CustomerRank::orderBy('min_points_required')->get()->map(function($rank) {
        return [
            'id' => $rank->id,
            'name' => $rank->name,
            'min_points_required' => $rank->min_points_required,
        ];
    });

    return response()->json([
        'before' => $beforeUpdate,
        'after' => $afterUpdate,
        'ranks' => $ranks,
        'updated' => $beforeUpdate['current_rank_id'] !== $afterUpdate['current_rank_id']
    ]);
});
