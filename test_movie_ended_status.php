<?php
require_once 'vendor/autoload.php';

// Chạy Laravel bootstrap
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Movie;
use App\Enums\MovieStatus;

// Lấy phim đầu tiên để test
$movie = Movie::first();

if ($movie) {
    echo "Testing movie: {$movie->title}\n";
    echo "Current status: {$movie->status->value}\n";
    
    // Thay đổi trạng thái thành 'ended' để test
    $originalStatus = $movie->status;
    $movie->status = MovieStatus::Ended;
    $movie->save();
    
    echo "Changed status to: {$movie->status->value}\n";
    echo "Now test the admin page - the create showtime button should be disabled.\n";
    echo "Movie URL: /admin/movies/{$movie->id}\n";
    
    // Hỏi có muốn revert lại không
    echo "\nPress Enter to revert status back to original...";
    fgets(STDIN);
    
    $movie->status = $originalStatus;
    $movie->save();
    echo "Status reverted to: {$movie->status->value}\n";
} else {
    echo "No movies found in database.\n";
}
