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
            'comment' => 'required|string|max:1000',
        ], [
            'comment.required' => 'Vui lòng nhập bình luận.',
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
                'message' => 'Bạn cần xem phim này trước khi có thể bình luận.'
            ], 403);
        }

        // Kiểm tra người dùng đã bình luận phim này chưa
        $existingReview = Review::where('user_id', $user->id)
            ->where('movie_id', $movieId)
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã bình luận phim này rồi.'
            ], 400);
        }

        // Tạo bình luận mới
        $contentCheck = $this->contentFilterService->checkContent($request->comment ?? '', $user->id);

        $review = Review::create([
            'user_id' => $user->id,
            'movie_id' => $movieId,
            'comment' => $request->comment,
            'status' => $contentCheck['status'], // 'approved' hoặc 'pending' tùy vào kết quả kiểm tra
            'admin_note' => $contentCheck['auto_flagged'] 
                ? 'Tự động chờ duyệt: ' . implode(', ', $contentCheck['reasons'])
                : null,
        ]);

        $message = $contentCheck['status'] === 'approved' 
            ? 'Bình luận của bạn đã được gửi thành công!'
            : 'Bình luận của bạn đã được gửi và đang chờ kiểm duyệt.';

        return response()->json([
            'success' => true,
            'message' => $message,
            'status' => $contentCheck['status'],
            'review' => [
                'id' => $review->id,
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

        // Chỉ trả về bình luận, không trả về rating
        $comments = collect($reviews->items())->map(function ($review) {
            return [
                'id' => $review->id,
                'comment' => $review->comment,
                'user_name' => $review->user->name ?? '',
                'created_at' => $review->created_at->format('d/m/Y H:i'),
                'status' => $review->status,
            ];
        });

        return response()->json([
            'success' => true,
            'comments' => $comments,
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ]
        ]);
    }

    // Đã loại bỏ chức năng tính average rating

    public function checkUserCanComment($movieId)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'can_comment' => false,
                'message' => 'Vui lòng đăng nhập để bình luận.'
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
                'can_comment' => false,
                'message' => 'Bạn cần xem phim này trước khi có thể bình luận.'
            ]);
        }

        // Kiểm tra đã bình luận chưa
        $hasCommented = Review::where('user_id', $user->id)
            ->where('movie_id', $movieId)
            ->exists();

        if ($hasCommented) {
            return response()->json([
                'can_comment' => false,
                'message' => 'Bạn đã bình luận phim này rồi.'
            ]);
        }

        return response()->json([
            'can_comment' => true,
            'message' => 'Bạn có thể bình luận phim này.'
        ]);
    }
}