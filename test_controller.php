<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TESTING AdminMovieController index() method ===\n";

// Simulate a request
$request = new \Illuminate\Http\Request();

$controller = new \App\Http\Controllers\Admin\AdminMovieController();
$movies = \App\Models\Movie::query()
    ->with(['country', 'ageLimit', 'genres'])
    ->orderBy('release_date', 'desc')
    ->paginate(10);

echo "Total movies found: " . $movies->total() . "\n";
echo "Movies per page: " . $movies->perPage() . "\n";
echo "Current page: " . $movies->currentPage() . "\n";

echo "\n=== MOVIES IN PAGINATED RESULT ===\n";
foreach ($movies as $movie) {
    $status = is_object($movie->status) ? $movie->status->value : $movie->status;
    echo "ID: {$movie->id} | Name: {$movie->name} | Status: {$status}\n";
}
