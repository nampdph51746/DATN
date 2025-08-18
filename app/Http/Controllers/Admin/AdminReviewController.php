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

        // Lọc theo trạng thái - cẩn thận với giá trị đầu vào
        if ($request->filled('status') && in_array($request->status, ['approved', 'pending', 'rejected'])) {
            $query->where('status', $request->status);
        }

        // Lọc theo phim - kiểm tra movie có tồn tại
        if ($request->filled('movie_id') && is_numeric($request->movie_id)) {
            $movieExists = Movie::where('id', $request->movie_id)->exists();
            if ($movieExists) {
                $query->where('movie_id', $request->movie_id);
            }
        }

        // Lọc theo loại xử lý (tự động/thủ công) - cẩn thận với logic
        if ($request->filled('auto_type') && in_array($request->auto_type, ['auto', 'manual'])) {
            if ($request->auto_type === 'auto') {
                // Bình luận được xử lý tự động - có admin_note chứa "Tự động"
                $query->where(function($q) {
                    $q->where('admin_note', 'like', 'Tự động%')
                      ->orWhere('admin_note', 'like', '%tự động%')
                      ->orWhere('admin_note', 'like', '%auto%');
                });
            } elseif ($request->auto_type === 'manual') {
                // Bình luận được xử lý thủ công - không có admin_note tự động hoặc có admin_note thủ công
                $query->where(function($q) {
                    $q->whereNull('admin_note')
                      ->orWhere(function($subQ) {
                          $subQ->whereNotNull('admin_note')
                               ->where('admin_note', 'not like', 'Tự động%')
                               ->where('admin_note', 'not like', '%tự động%')
                               ->where('admin_note', 'not like', '%auto%');
                      });
                });
            }
        }

        // Tìm kiếm nâng cao - tìm kiếm linh hoạt hơn
        if ($request->filled('search')) {
            $search = trim($request->search);
            $searchTerms = explode(' ', $search); // Tách từ khóa
            
            $query->where(function($q) use ($search, $searchTerms) {
                // Tìm kiếm chính xác theo chuỗi đầy đủ
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('comment', 'like', "%{$search}%")
                ->orWhere('admin_note', 'like', "%{$search}%")
                ->orWhereHas('movie', function($movieQuery) use ($search) {
                    $movieQuery->where('name', 'like', "%{$search}%");
                });
                
                // Nếu có nhiều từ, tìm kiếm từng từ riêng lẻ
                if (count($searchTerms) > 1) {
                    foreach ($searchTerms as $term) {
                        $term = trim($term);
                        if (strlen($term) >= 2) { // Chỉ tìm từ có ít nhất 2 ký tự
                            $q->orWhereHas('user', function($userQuery) use ($term) {
                                $userQuery->where('name', 'like', "%{$term}%");
                            })
                            ->orWhere('comment', 'like', "%{$term}%")
                            ->orWhereHas('movie', function($movieQuery) use ($term) {
                                $movieQuery->where('name', 'like', "%{$term}%");
                            });
                        }
                    }
                }
            });
        }

        // Lọc theo khoảng thời gian (thêm mới)
        if ($request->filled('date_from')) {
            try {
                $dateFrom = \Carbon\Carbon::createFromFormat('Y-m-d', $request->date_from)->startOfDay();
                $query->where('created_at', '>=', $dateFrom);
            } catch (\Exception $e) {
                // Ignore invalid date format
            }
        }

        if ($request->filled('date_to')) {
            try {
                $dateTo = \Carbon\Carbon::createFromFormat('Y-m-d', $request->date_to)->endOfDay();
                $query->where('created_at', '<=', $dateTo);
            } catch (\Exception $e) {
                // Ignore invalid date format
            }
        }

        // Sắp xếp - mặc định theo thời gian mới nhất
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortFields = ['created_at', 'status', 'user_id', 'movie_id'];
        $allowedSortOrders = ['asc', 'desc'];
        
        if (in_array($sortBy, $allowedSortFields) && in_array($sortOrder, $allowedSortOrders)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Phân trang với số lượng có thể tùy chỉnh
        $perPage = $request->get('per_page', 15);
        $allowedPerPage = [10, 15, 25, 50];
        $perPage = in_array((int)$perPage, $allowedPerPage) ? (int)$perPage : 15;
        
        $reviews = $query->paginate($perPage);

        // Lấy danh sách phim để filter - chỉ lấy phim có reviews
        $movies = Movie::select('id', 'name')
                      ->whereHas('reviews')
                      ->orderBy('name')
                      ->get();

        // Thống kê chi tiết hơn
        $stats = [
            'total' => Review::count(),
            'approved' => Review::where('status', 'approved')->count(),
            'pending' => Review::where('status', 'pending')->count(),
            'rejected' => Review::where('status', 'rejected')->count(),
            'auto_processed' => Review::where('admin_note', 'like', '%tự động%')->count(),
            'manual_processed' => Review::whereNotNull('admin_note')
                                        ->where('admin_note', 'not like', '%tự động%')
                                        ->count(),
            'today' => Review::whereDate('created_at', today())->count(),
            'this_week' => Review::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        // Force refresh nếu có request refresh
        if ($request->has('refresh')) {
            // Clear any potential cache
            if (function_exists('opcache_reset')) {
                opcache_reset();
            }
        }

        return view('admin.reviews.index', compact('reviews', 'movies', 'stats'));
    }

    public function show($id)
    {
        $review = Review::with(['user', 'movie'])->findOrFail($id);
        
        return view('admin.reviews.show', compact('review'));
    }

        public function contentFilterSettings()
    {
        $sensitiveWords = $this->contentFilterService->listSensitiveWords();
        return view('admin.reviews.content-filter', compact('sensitiveWords'));
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

        // Đã loại bỏ chức năng cập nhật average rating

        return redirect()->route('admin.reviews.show', $id)
            ->with('success', 'Cập nhật trạng thái bình luận thành công!');
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
                'reviewed_at' => now(),
                'status' => $request->status,
            ]);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Cập nhật trạng thái cho ' . count($request->review_ids) . ' bình luận thành công!');
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


        return redirect()->route('admin.reviews.index')
            ->with('success', 'Xóa ' . count($request->review_ids) . ' bình luận thành công!');
    }

    // public function contentFilterSettings()
    // {
    //     // Sửa lỗi: gọi phương thức public thay vì private
    //     $sensitiveWords = $this->contentFilterService->listSensitiveWords();

    //     return view('admin.reviews.content-filter', compact('sensitiveWords'));
    // }

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

       

        return redirect()->route('admin.reviews.show', $id)
            ->with('warning', 'Đã ép duyệt bình luận. Lý do: ' . $request->force_reason);
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Đã xóa bình luận thành công!');
    }

}