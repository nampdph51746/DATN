<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->boot();

echo "=== Customer Ranks ===" . PHP_EOL;
$ranks = App\Models\CustomerRank::orderBy('min_points_required')->get();
foreach($ranks as $rank) {
    echo "ID: {$rank->id}, Name: {$rank->name}, Min Points: {$rank->min_points_required}" . PHP_EOL;
}

echo PHP_EOL . "=== Users with Points ===" . PHP_EOL;
$users = App\Models\User::with(['points', 'customerRank'])->whereHas('points')->take(5)->get();
foreach($users as $user) {
    $points = $user->points ? $user->points->total_points : 0;
    $rank = $user->customerRank ? $user->customerRank->name : 'None';
    echo "User ID: {$user->id}, Name: {$user->name}, Points: {$points}, Current Rank: {$rank}" . PHP_EOL;
}

echo PHP_EOL . "=== Testing Rank Update Logic ===" . PHP_EOL;
// Test với user đầu tiên có điểm
$testUser = $users->first();
if ($testUser) {
    echo "Testing with User ID: {$testUser->id}" . PHP_EOL;
    echo "Before update: Rank = " . ($testUser->customerRank?->name ?? 'None') . PHP_EOL;
    
    // Gọi hàm update rank
    $testUser->updateRankByTotalSpent();
    $testUser->refresh();
    
    echo "After update: Rank = " . ($testUser->customerRank?->name ?? 'None') . PHP_EOL;
}
