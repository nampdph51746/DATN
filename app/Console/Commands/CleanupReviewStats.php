<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Review;
use App\Models\Movie;

class CleanupReviewStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reviews:cleanup-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup and recalculate all review statistics';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of review statistics...');

        // Get current stats
        $totalReviews = Review::count();
        $approvedReviews = Review::where('status', 'approved')->count();
        $pendingReviews = Review::where('status', 'pending')->count();
        $rejectedReviews = Review::where('status', 'rejected')->count();

        $this->table(
            ['Status', 'Count'],
            [
                ['Total Reviews', $totalReviews],
                ['Approved', $approvedReviews],
                ['Pending', $pendingReviews],
                ['Rejected', $rejectedReviews],
            ]
        );

        // Recalculate movie ratings
        $this->info('Recalculating movie ratings...');
        $movies = Movie::all();
        $updated = 0;

        foreach ($movies as $movie) {
            $oldRating = $movie->average_rating;
            
            $averageRating = Review::where('movie_id', $movie->id)
                ->where('status', 'approved')
                ->avg('rating_star');

            $newRating = $averageRating ? round($averageRating, 1) : 0;
            
            if ($oldRating != $newRating) {
                $movie->update(['average_rating' => $newRating]);
                $this->line("Movie '{$movie->name}': {$oldRating} -> {$newRating}");
                $updated++;
            }
        }

        $this->info("Updated {$updated} movies.");

        // Clean up orphaned data if any
        $this->info('Checking for orphaned reviews...');
        $orphanedReviews = Review::whereDoesntHave('user')->count();
        $orphanedMovieReviews = Review::whereDoesntHave('movie')->count();

        if ($orphanedReviews > 0) {
            $this->warn("Found {$orphanedReviews} reviews with missing users");
        }
        
        if ($orphanedMovieReviews > 0) {
            $this->warn("Found {$orphanedMovieReviews} reviews with missing movies");
        }

        if ($orphanedReviews === 0 && $orphanedMovieReviews === 0) {
            $this->info('No orphaned data found.');
        }

        $this->info('Cleanup completed successfully!');
        return 0;
    }
}
