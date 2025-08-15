<?php

require __DIR__ . '/vendor/autoload.php';

// Khởi tạo Laravel app
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== KIỂM TRA ADMINMOVIECONTROLLER INDEX ===\n";

try {
    // Tạo controller instance
    $controller = new \App\Http\Controllers\Admin\AdminMovieController();
    
    // Tạo mock request
    $request = new \Illuminate\Http\Request();
    
    // Gọi method index
    $response = $controller->index($request);
    
    // Lấy data từ response
    $viewData = $response->getData();
    $movies = $viewData['movies'];
    
    echo "✅ Controller index hoạt động thành công!\n";
    echo "Số lượng phim: " . $movies->count() . "\n";
    echo "Tổng số phim: " . $movies->total() . "\n";
    echo "Current page: " . $movies->currentPage() . "\n";
    echo "Per page: " . $movies->perPage() . "\n";
    
    echo "\n=== DANH SÁCH PHIM HIỆN TẠI (Trang đầu) ===\n";
    foreach ($movies as $movie) {
        $status = is_object($movie->status) ? $movie->status->value : $movie->status;
        echo "- ID: {$movie->id} | {$movie->name} | {$status} | " . $movie->created_at->format('d/m/Y H:i') . "\n";
        
        // Kiểm tra hình ảnh
        if ($movie->image_path) {
            echo "  📸 Image path: {$movie->image_path}\n";
        } elseif ($movie->poster_url) {
            echo "  🔗 Poster URL: {$movie->poster_url}\n";
        } else {
            echo "  ❌ No image\n";
        }
    }
    
    echo "\n=== KIỂM TRA RELATIONSHIPS ===\n";
    $testMovie = $movies->first();
    if ($testMovie) {
        echo "Test movie: {$testMovie->name}\n";
        echo "Country: " . ($testMovie->country ? $testMovie->country->name : 'N/A') . "\n";
        echo "Age limit: " . ($testMovie->ageLimit ? ($testMovie->ageLimit->name ?? $testMovie->ageLimit->label) : 'N/A') . "\n";
        echo "Genres: " . $testMovie->genres->pluck('name')->join(', ') . "\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
