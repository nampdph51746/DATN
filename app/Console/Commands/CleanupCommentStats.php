<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Comment;
use App\Models\Movie;

class CleanupCommentStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comment:cleanup-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup and recalculate all comment statistics (DISABLED - use comment:content-maintenance instead)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Command này đã được vô hiệu hóa do hệ thống đã chuyển từ review sang comment-only.');
        $this->info('Sử dụng command comment:content-maintenance thay thế.');
        return 0;
    }
}