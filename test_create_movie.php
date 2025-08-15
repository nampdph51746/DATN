<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Khởi tạo Laravel app
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test dữ liệu mẫu để tạo phim
$testData = [
    'name' => 'Test Movie ' . date('H:i:s'),
    'director' => 'Test Director',
    'actors' => 'Test Actor 1, Test Actor 2',
    'duration_minutes' => 120,
    'release_date' => '2025-08-06',
    'end_date' => '2025-12-31',
    'description' => 'This is a test movie description with more than 10 characters to pass validation.',
    'language' => 'Tiếng Việt',
    'country_id' => 1, // Giả định có country với ID 1
    'age_limit_id' => 1, // Giả định có age limit với ID 1
    'status' => 'upcoming',
    'genre_ids' => [1, 2], // Giả định có genres với ID 1, 2
    'average_rating' => 8.5,
];

echo "=== KIỂM TRA DỮ LIỆU CẦN THIẾT ===\n";

// Kiểm tra Countries
$countries = \App\Models\Country::all();
echo "Countries available: " . $countries->count() . "\n";
if ($countries->count() > 0) {
    echo "First country: ID {$countries->first()->id} - {$countries->first()->name}\n";
    $testData['country_id'] = $countries->first()->id;
}

// Kiểm tra Age Limits
$ageLimits = \App\Models\AgeLimit::all();
echo "Age limits available: " . $ageLimits->count() . "\n";
if ($ageLimits->count() > 0) {
    echo "First age limit: ID {$ageLimits->first()->id} - " . ($ageLimits->first()->name ?? $ageLimits->first()->label) . "\n";
    $testData['age_limit_id'] = $ageLimits->first()->id;
}

// Kiểm tra Genres
$genres = \App\Models\Genre::all();
echo "Genres available: " . $genres->count() . "\n";
if ($genres->count() > 0) {
    echo "Available genres: " . $genres->pluck('name')->join(', ') . "\n";
    $testData['genre_ids'] = [$genres->first()->id];
}

echo "\n=== THỰC HIỆN TẠO PHIM TEST ===\n";

try {
    // Tạo phim mới
    $movie = \App\Models\Movie::create([
        'name' => $testData['name'],
        'director' => $testData['director'],
        'actors' => $testData['actors'],
        'duration_minutes' => $testData['duration_minutes'],
        'release_date' => $testData['release_date'],
        'end_date' => $testData['end_date'],
        'description' => $testData['description'],
        'language' => $testData['language'],
        'country_id' => $testData['country_id'],
        'age_limit_id' => $testData['age_limit_id'],
        'status' => $testData['status'],
        'average_rating' => $testData['average_rating'],
    ]);

    // Gán thể loại
    $movie->genres()->sync($testData['genre_ids']);

    echo "✅ Phim được tạo thành công!\n";
    echo "ID: {$movie->id}\n";
    echo "Tên: {$movie->name}\n";
    echo "Trạng thái: " . (is_object($movie->status) ? $movie->status->value : $movie->status) . "\n";
    echo "Created at: {$movie->created_at}\n";

    echo "\n=== KIỂM TRA DANH SÁCH PHIM HIỆN TẠI ===\n";
    $movies = \App\Models\Movie::with(['country', 'ageLimit', 'genres'])
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    foreach ($movies as $m) {
        $status = is_object($m->status) ? $m->status->value : $m->status;
        echo "- ID: {$m->id} | {$m->name} | {$status} | " . $m->created_at->format('Y-m-d H:i:s') . "\n";
    }

} catch (\Exception $e) {
    echo "❌ Lỗi khi tạo phim: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

echo "\n=== KIỂM TRA VALIDATION RULES ===\n";

// Test validation
$validator = \Illuminate\Support\Facades\Validator::make($testData, [
    'name' => 'required|string|max:255|unique:movies,name',
    'director' => 'required|string|max:255',
    'actors' => 'nullable|string|max:1000',
    'duration_minutes' => 'required|integer|min:1|max:600',
    'release_date' => 'required|date',
    'end_date' => 'nullable|date|after_or_equal:release_date',
    'description' => 'required|string|min:10|max:5000',
    'language' => 'required|string|max:50',
    'country_id' => 'required|integer|exists:countries,id',
    'age_limit_id' => 'required|integer|exists:age_limits,id',
    'status' => 'required|in:showing,upcoming,ended',
    'genre_ids' => 'required|array|min:1|max:5',
    'genre_ids.*' => 'exists:genres,id',
    'average_rating' => 'nullable|numeric|min:0|max:10',
]);

if ($validator->passes()) {
    echo "✅ Validation passed!\n";
} else {
    echo "❌ Validation failed:\n";
    foreach ($validator->errors()->all() as $error) {
        echo "- $error\n";
    }
}
