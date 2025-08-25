<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Comment;
use App\Services\ContentFilterService;
use Illuminate\Support\Facades\DB;

class CommentContentMaintenance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comment:content-maintenance 
                            {--recheck : Kiểm tra lại tất cả comment}
                            {--cleanup : Xóa comment spam cũ}
                            {--stats : Hiển thị thống kê}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bảo trì và kiểm tra nội dung review';

    protected $contentFilterService;

    public function __construct(ContentFilterService $contentFilterService)
    {
        parent::__construct();
        $this->contentFilterService = $contentFilterService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('stats')) {
            $this->showStats();
            return;
        }

        if ($this->option('cleanup')) {
            $this->cleanupSpamReviews();
            return;
        }

        if ($this->option('recheck')) {
            $this->recheckAllReviews();
            return;
        }

        $this->info('Sử dụng các tùy chọn:');
        $this->info('--recheck  : Kiểm tra lại tất cả review');
        $this->info('--cleanup  : Xóa review spam cũ');
        $this->info('--stats    : Hiển thị thống kê');
    }

    protected function recheckAllReviews()
    {
        $this->info('Bắt đầu kiểm tra lại tất cả comment...');
        
        $reviews = Comment::whereNotNull('comment')->get();
        $updated = 0;
        $errors = 0;

        $progressBar = $this->output->createProgressBar($reviews->count());
        $progressBar->start();

        foreach ($reviews as $review) {
            try {
                $contentCheck = $this->contentFilterService->checkContent($review->comment, $review->user_id);
                
                // Chỉ cập nhật nếu có thay đổi
                $shouldUpdate = false;
                $updateData = [];

                if ($review->status !== $contentCheck['status']) {
                    $updateData['status'] = $contentCheck['status'];
                    $shouldUpdate = true;
                }

                if ($contentCheck['auto_flagged']) {
                    $newNote = 'Kiểm tra lại: ' . implode(', ', $contentCheck['reasons']);
                    if ($review->admin_note !== $newNote) {
                        $updateData['admin_note'] = $newNote;
                        $shouldUpdate = true;
                    }
                }

                if ($shouldUpdate) {
                    $review->update($updateData);
                    $updated++;
                }

            } catch (\Exception $e) {
                $errors++;
                $this->error("Lỗi kiểm tra review ID {$review->id}: " . $e->getMessage());
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->info("\n\nHoàn thành!");
        $this->info("Đã kiểm tra: {$reviews->count()} review");
        $this->info("Đã cập nhật: {$updated} review");
        if ($errors > 0) {
            $this->warn("Lỗi: {$errors} review");
        }
    }

    protected function cleanupSpamReviews()
    {
        $this->info('Đang dọn dẹp comment spam...');
        
        // Tìm các comment có dấu hiệu spam nghiêm trọng
        $spamReviews = Comment::where(function($query) {
            $query->where('admin_note', 'like', '%ký tự lặp lại nhiều lần%')
                  ->orWhere('admin_note', 'like', '%Quá nhiều ký tự đặc biệt%')
                  ->orWhere('admin_note', 'like', '%lặp lại quá nhiều%');
        })
        ->where('status', 'rejected')
        ->where('created_at', '<', now()->subDays(30)) // Chỉ xóa review cũ hơn 30 ngày
        ->get();

        if ($spamReviews->isEmpty()) {
            $this->info('Không tìm thấy review spam cần dọn dẹp.');
            return;
        }

        if ($this->confirm("Tìm thấy {$spamReviews->count()} comment spam. Bạn có muốn xóa không?")) {
            $movieIds = $spamReviews->pluck('movie_id')->unique();
            
            // Xóa comment spam
            Comment::whereIn('id', $spamReviews->pluck('id'))->delete();
            
            $this->info("Đã xóa {$spamReviews->count()} comment spam.");
        }
    }
}