<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Review;
use App\Models\Booking;
use App\Services\ContentFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    protected $contentFilterService;

    public function __construct(ContentFilterService $contentFilterService)
    {
        $this->contentFilterService = $contentFilterService;
    }
    public function store(Request $request, $movieId)
    {
        $request->validate([
            'rating_star' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ], [
            'rating_star.required' => 'Vui lòng chọn số sao đánh giá.',
            'rating_star.min' => 'Đánh giá tối thiểu 1 sao.',
            'rating_star.max' => 'Đánh giá tối đa 5 sao.',
            'comment.max' => 'Bình luận không được vượt quá 1000 ký tự.',
        ]);

        $user = Auth::user();
        $movie = Movie::findOrFail($movieId);

        // Kiểm tra người dùng đã xem phim này chưa (có booking thành công)
        $hasWatchedMovie = Booking::where('user_id', $user->id)
            ->whereHas('showtime', function ($query) use ($movieId) {
                $query->where('movie_id', $movieId);
            })
            ->where('status', 'confirmed')
            ->exists();

        if (!$hasWatchedMovie) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần xem phim này trước khi có thể đánh giá.'
            ], 403);
        }

        // Kiểm tra người dùng đã đánh giá phim này chưa
        $existingReview = Review::where('user_id', $user->id)
            ->where('movie_id', $movieId)
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã đánh giá phim này rồi.'
            ], 400);
        }

        // Tạo review mới
        $contentCheck = $this->contentFilterService->checkContent($request->comment ?? '', $user->id);
        
        $review = Review::create([
            'user_id' => $user->id,
            'movie_id' => $movieId,
            'rating_star' => $request->rating_star,
            'comment' => $request->comment,
            'status' => $contentCheck['status'], // 'approved' hoặc 'pending' tùy vào kết quả kiểm tra
            'admin_note' => $contentCheck['auto_flagged'] 
                ? 'Tự động chờ duyệt: ' . implode(', ', $contentCheck['reasons'])
                : null,
        ]);

        // Chỉ cập nhật rating nếu review được approve ngay
        if ($contentCheck['status'] === 'approved') {
            $this->updateMovieAverageRating($movieId);
        }

        $message = $contentCheck['status'] === 'approved' 
            ? 'Đánh giá của bạn đã được gửi thành công!'
            : 'Đánh giá của bạn đã được gửi và đang chờ kiểm duyệt.';

        return response()->json([
            'success' => true,
            'message' => $message,
            'status' => $contentCheck['status'],
            'review' => [
                'id' => $review->id,
                'rating_star' => $review->rating_star,
                'comment' => $review->comment,
                'user_name' => $user->name,
                'created_at' => $review->created_at->format('d/m/Y H:i'),
                'status' => $review->status,
            ]
        ]);
    }

    public function getReviews($movieId)
    {
        $reviews = Review::with('user')
            ->where('movie_id', $movieId)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'reviews' => $reviews->items(),
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ]
        ]);
    }

    private function updateMovieAverageRating($movieId)
    {
        $averageRating = Review::where('movie_id', $movieId)
            ->where('status', 'approved')
            ->avg('rating_star');

        Movie::where('id', $movieId)->update([
            'average_rating' => round($averageRating, 1)
        ]);
    }

    public function checkUserCanReview($movieId)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'can_review' => false,
                'message' => 'Vui lòng đăng nhập để đánh giá.'
            ]);
        }

        // Kiểm tra đã xem phim chưa
        $hasWatchedMovie = Booking::where('user_id', $user->id)
            ->whereHas('showtime', function ($query) use ($movieId) {
                $query->where('movie_id', $movieId);
            })
            ->where('status', 'confirmed')
            ->exists();

        if (!$hasWatchedMovie) {
            return response()->json([
                'can_review' => false,
                'message' => 'Bạn cần xem phim này trước khi có thể đánh giá.'
            ]);
        }

        // Kiểm tra đã đánh giá chưa
        $hasReviewed = Review::where('user_id', $user->id)
            ->where('movie_id', $movieId)
            ->exists();

        if ($hasReviewed) {
            return response()->json([
                'can_review' => false,
                'message' => 'Bạn đã đánh giá phim này rồi.'
            ]);
        }

        return response()->json([
            'can_review' => true,
            'message' => 'Bạn có thể đánh giá phim này.'
        ]);
    }
}
