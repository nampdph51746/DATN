<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Movie;
use App\Models\User;
use App\Services\ContentFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminReviewController extends Controller
{
    protected $contentFilterService;

    public function __construct(ContentFilterService $contentFilterService)
    {
        $this->contentFilterService = $contentFilterService;
    }
    public function index(Request $request)
    {
        $query = Review::with(['user', 'movie']);

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Lọc theo phim
        if ($request->has('movie_id') && $request->movie_id !== '') {
            $query->where('movie_id', $request->movie_id);
        }

        // Lọc theo đánh giá sao
        if ($request->has('rating') && $request->rating !== '') {
            $query->where('rating_star', $request->rating);
        }

        // Lọc theo loại xử lý (tự động/thủ công)
        if ($request->has('auto_type') && $request->auto_type !== '') {
            if ($request->auto_type === 'auto') {
                $query->where('admin_note', 'like', 'Tự động chờ duyệt%');
            } elseif ($request->auto_type === 'manual') {
                $query->where(function($q) {
                    $q->whereNull('admin_note')
                      ->orWhere('admin_note', 'not like', 'Tự động chờ duyệt%');
                });
            }
        }

        // Tìm kiếm theo tên user hoặc comment
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhere('comment', 'like', "%{$search}%");
            });
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(15);

        // Lấy danh sách phim để filter
        $movies = Movie::select('id', 'name')->orderBy('name')->get();

        // Thống kê (refresh mỗi lần load)
        $stats = [
            'total' => Review::count(),
            'approved' => Review::where('status', 'approved')->count(),
            'pending' => Review::where('status', 'pending')->count(),
            'rejected' => Review::where('status', 'rejected')->count(),
        ];

        // Force refresh nếu có request refresh
        if ($request->has('refresh')) {
            // Clear any potential cache
            if (function_exists('opcache_reset')) {
                opcache_reset();
            }
            
            // Recalculate all movie ratings
            $this->recalculateAllRatingsInternal();
        }

        return view('admin.reviews.index', compact('reviews', 'movies', 'stats'));
    }

    public function show($id)
    {
        $review = Review::with(['user', 'movie'])->findOrFail($id);
        
        return view('admin.reviews.show', compact('review'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,pending,rejected',
            'admin_note' => 'nullable|string|max:500'
        ]);

        $review = Review::findOrFail($id);
        $oldStatus = $review->status;
        
        // Kiểm tra nội dung nhạy cảm khi admin muốn duyệt
        if ($request->status === 'approved' && $review->comment) {
            $contentCheck = $this->contentFilterService->checkContent($review->comment, $review->user_id);
            
            if ($contentCheck['auto_flagged']) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Không thể duyệt bình luận này vì chứa nội dung không phù hợp: ' . 
                           implode(', ', $contentCheck['reasons']) . 
                           '. Vui lòng từ chối hoặc để ở trạng thái chờ duyệt.');
            }
        }
        
        $review->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now()
        ]);

        // Cập nhật lại average rating của phim nếu status thay đổi
        if ($oldStatus !== $request->status) {
            $this->updateMovieAverageRating($review->movie_id);
        }

        return redirect()->route('admin.reviews.show', $id)
            ->with('success', 'Cập nhật trạng thái đánh giá thành công!');
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id',
            'status' => 'required|in:approved,pending,rejected'
        ]);

        $reviews = Review::whereIn('id', $request->review_ids)->get();
        $movieIds = $reviews->pluck('movie_id')->unique();
        
        // Kiểm tra nội dung nhạy cảm khi admin muốn duyệt hàng loạt
        if ($request->status === 'approved') {
            $flaggedReviews = [];
            
            foreach ($reviews as $review) {
                if ($review->comment) {
                    $contentCheck = $this->contentFilterService->checkContent($review->comment, $review->user_id);
                    
                    if ($contentCheck['auto_flagged']) {
                        $flaggedReviews[] = [
                            'id' => $review->id,
                            'user' => $review->user->name,
                            'reasons' => $contentCheck['reasons']
                        ];
                    }
                }
            }
            
            if (!empty($flaggedReviews)) {
                $errorMessage = 'Không thể duyệt một số bình luận vì chứa nội dung không phù hợp:<br>';
                foreach ($flaggedReviews as $flagged) {
                    $errorMessage .= "- ID #{$flagged['id']} ({$flagged['user']}): " . implode(', ', $flagged['reasons']) . '<br>';
                }
                $errorMessage .= 'Vui lòng xem xét từng bình luận riêng lẻ.';
                
                return redirect()->back()
                    ->with('error', $errorMessage);
            }
        }

        Review::whereIn('id', $request->review_ids)->update([
            'status' => $request->status,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now()
        ]);

        // Cập nhật lại average rating cho các phim
        foreach ($movieIds as $movieId) {
            $this->updateMovieAverageRating($movieId);
        }

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Cập nhật trạng thái cho ' . count($request->review_ids) . ' đánh giá thành công!');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $movieId = $review->movie_id;
        
        $review->delete();

        // Cập nhật lại average rating của phim
        $this->updateMovieAverageRating($movieId);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Xóa đánh giá thành công!');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id'
        ]);

        $reviews = Review::whereIn('id', $request->review_ids)->get();
        $movieIds = $reviews->pluck('movie_id')->unique();

        Review::whereIn('id', $request->review_ids)->delete();

        // Cập nhật lại average rating cho các phim
        foreach ($movieIds as $movieId) {
            $this->updateMovieAverageRating($movieId);
        }

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Xóa ' . count($request->review_ids) . ' đánh giá thành công!');
    }

    private function updateMovieAverageRating($movieId)
    {
        $averageRating = Review::where('movie_id', $movieId)
            ->where('status', 'approved')
            ->avg('rating_star');

        Movie::where('id', $movieId)->update([
            'average_rating' => $averageRating ? round($averageRating, 1) : 0
        ]);
    }

    public function recalculateAllRatings()
    {
        // Lấy tất cả phim có reviews
        $movies = Movie::whereHas('reviews')->get();
        $updated = 0;

        foreach ($movies as $movie) {
            $averageRating = Review::where('movie_id', $movie->id)
                ->where('status', 'approved')
                ->avg('rating_star');

            $newRating = $averageRating ? round($averageRating, 1) : 0;
            
            if ($movie->average_rating != $newRating) {
                $movie->update(['average_rating' => $newRating]);
                $updated++;
            }
        }

        return redirect()->route('admin.reviews.index')
            ->with('success', "Đã tính lại điểm trung bình cho {$updated} phim!");
    }

    private function recalculateAllRatingsInternal()
    {
        $movies = Movie::whereHas('reviews')->get();
        
        foreach ($movies as $movie) {
            $averageRating = Review::where('movie_id', $movie->id)
                ->where('status', 'approved')
                ->avg('rating_star');

            $newRating = $averageRating ? round($averageRating, 1) : 0;
            
            if ($movie->average_rating != $newRating) {
                $movie->update(['average_rating' => $newRating]);
            }
        }
    }

    public function contentFilterSettings()
    {
        // Sửa lỗi: gọi phương thức public thay vì private
        $sensitiveWords = $this->contentFilterService->listSensitiveWords();

        return view('admin.reviews.content-filter', compact('sensitiveWords'));
    }

    public function addSensitiveWord(Request $request)
    {
        $request->validate([
            'word' => 'required|string|max:50|unique:sensitive_words,word'
        ], [
            'word.required' => 'Vui lòng nhập từ khóa.',
            'word.unique' => 'Từ khóa này đã tồn tại.',
            'word.max' => 'Từ khóa không được vượt quá 50 ký tự.'
        ]);

        $this->contentFilterService->addSensitiveWord($request->word);

        return redirect()->route('admin.reviews.content-filter')
            ->with('success', 'Đã thêm từ khóa nhạy cảm: ' . $request->word);
    }

    public function removeSensitiveWord(Request $request)
    {
        $request->validate([
            'word' => 'required|string'
        ]);

        $this->contentFilterService->removeSensitiveWord($request->word);

        return redirect()->route('admin.reviews.content-filter')
            ->with('success', 'Đã xóa từ khóa nhạy cảm: ' . $request->word);
    }

    public function recheckReview($id)
    {
        $review = Review::findOrFail($id);
        
        if (!$review->comment) {
            return redirect()->back()
                ->with('error', 'Không thể kiểm tra lại review không có nội dung.');
        }

        $contentCheck = $this->contentFilterService->checkContent($review->comment, $review->user_id);
        
        // Đề xuất trạng thái mới dựa trên kết quả kiểm tra
        $suggestedStatus = $contentCheck['auto_flagged'] ? 'pending' : 'approved';
        
        $review->update([
            'admin_note' => $contentCheck['auto_flagged'] 
                ? 'Kiểm tra lại: ' . implode(', ', $contentCheck['reasons'])
                : 'Kiểm tra lại: Nội dung hợp lệ',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now()
        ]);

        $statusText = $contentCheck['auto_flagged'] ? 'Chờ duyệt (có vấn đề)' : 'Có thể duyệt (sạch)';

        return redirect()->back()
            ->with('success', 'Đã kiểm tra lại review. Kết quả: ' . $statusText . 
                   ($contentCheck['auto_flagged'] ? '. Lý do: ' . implode(', ', $contentCheck['reasons']) : ''));
    }

    public function forceApprove(Request $request, $id)
    {
        $request->validate([
            'force_reason' => 'required|string|min:10|max:500'
        ], [
            'force_reason.required' => 'Vui lòng nhập lý do ép duyệt.',
            'force_reason.min' => 'Lý do phải có ít nhất 10 ký tự.',
            'force_reason.max' => 'Lý do không được vượt quá 500 ký tự.'
        ]);

        $review = Review::findOrFail($id);
        $oldStatus = $review->status;
        
        $review->update([
            'status' => 'approved',
            'admin_note' => 'ÉP DUYỆT - Lý do: ' . $request->force_reason,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now()
        ]);

        // Cập nhật lại average rating của phim nếu status thay đổi
        if ($oldStatus !== 'approved') {
            $this->updateMovieAverageRating($review->movie_id);
        }

        return redirect()->route('admin.reviews.show', $id)
            ->with('warning', 'Đã ép duyệt bình luận. Lý do: ' . $request->force_reason);
    }

    public function resetAllStats()
    {
        try {
            // Reset all movie ratings
            $movies = Movie::all();
            foreach ($movies as $movie) {
                $averageRating = Review::where('movie_id', $movie->id)
                    ->where('status', 'approved')
                    ->avg('rating_star');

                $movie->update([
                    'average_rating' => $averageRating ? round($averageRating, 1) : 0
                ]);
            }

            // Clear any potential cache
            if (function_exists('opcache_reset')) {
                opcache_reset();
            }

            return redirect()->route('admin.reviews.index')
                ->with('success', 'Đã reset tất cả thống kê thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.reviews.index')
                ->with('error', 'Có lỗi xảy ra khi reset thống kê: ' . $e->getMessage());
        }
    }
}