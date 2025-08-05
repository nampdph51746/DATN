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

        // Thống kê
        $stats = [
            'total' => Review::count(),
            'approved' => Review::where('status', 'approved')->count(),
            'pending' => Review::where('status', 'pending')->count(),
            'rejected' => Review::where('status', 'rejected')->count(),
        ];

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

    public function contentFilterSettings()
    {
        $sensitiveWords = $this->contentFilterService->getSensitiveWords();
        
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
        
        $review->update([
            'admin_note' => $contentCheck['auto_flagged'] 
                ? 'Kiểm tra lại: ' . implode(', ', $contentCheck['reasons'])
                : 'Kiểm tra lại: Nội dung hợp lệ',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now()
        ]);

        return redirect()->back()
            ->with('success', 'Đã kiểm tra lại review. Trạng thái đề xuất: ' . 
                   ($contentCheck['auto_flagged'] ? 'Pending' : 'Approved'));
    }
}
