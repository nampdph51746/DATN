<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test image paths
$movies = \App\Models\Movie::select('id', 'name', 'image_path', 'poster_url')->take(3)->get();

echo "=== KIỂM TRA ĐƯỜNG DẪN ẢNH PHIM ===\n\n";

foreach ($movies as $movie) {
    echo "ID: {$movie->id}\n";
    echo "Tên phim: {$movie->name}\n";
    echo "image_path: " . ($movie->image_path ?? 'NULL') . "\n";
    echo "poster_url: " . ($movie->poster_url ?? 'NULL') . "\n";
    
    if ($movie->image_path) {
        $storageUrl = \Illuminate\Support\Facades\Storage::url($movie->image_path);
        echo "Storage URL: {$storageUrl}\n";
        
        $fullPath = storage_path('app/public/' . $movie->image_path);
        echo "Full path: {$fullPath}\n";
        echo "File exists: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
    }
    
    echo str_repeat('-', 50) . "\n";
}
