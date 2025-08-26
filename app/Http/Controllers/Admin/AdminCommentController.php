<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Movie;
use App\Models\User;
use App\Services\ContentFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminCommentController extends Controller
{
    protected $contentFilterService;

    public function __construct(ContentFilterService $contentFilterService)
    {
        $this->contentFilterService = $contentFilterService;
    }
    
    public function index(Request $request)
    {
        $query = Comment::with(['user', 'movie']);

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

        // Lọc theo loại xử lý (tự động/thủ công)
        if ($request->filled('auto_type') && in_array($request->auto_type, ['auto', 'manual'])) {
            if ($request->auto_type === 'auto') {
                $query->where('admin_note', 'like', 'Tự động chờ duyệt%');
            } elseif ($request->auto_type === 'manual') {
                $query->where(function ($q) {
                    $q->whereNull('admin_note')
                        ->orWhere('admin_note', 'not like', 'Tự động chờ duyệt%');
                });
            }
        }

        // Tìm kiếm theo tên user hoặc comment
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })
                    ->orWhere('comment', 'like', "%{$search}%");
            });
        }

        $comments = $query->orderBy('created_at', 'desc')->paginate(15);

        // Lấy danh sách phim để filter
        $movies = Movie::select('id', 'name')->orderBy('name')->get();

        // Thống kê (refresh mỗi lần load)
        $stats = [
            'total' => Comment::count(),
            'approved' => Comment::where('status', 'approved')->count(),
            'pending' => Comment::where('status', 'pending')->count(),
            'rejected' => Comment::where('status', 'rejected')->count(),
        ];

        return view('admin.comments.index', compact('comments', 'movies', 'stats'));
    }

    public function show($id)
    {
        $comment = Comment::with(['user', 'movie'])->findOrFail($id);

        return view('admin.comments.show', compact('comment'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,pending,rejected',
            'admin_note' => 'nullable|string|max:500'
        ]);

        $comment = Comment::findOrFail($id);

        // Kiểm tra nội dung nhạy cảm khi admin muốn duyệt
        if ($request->status === 'approved' && $comment->comment) {
            $contentCheck = $this->contentFilterService->checkContent($comment->comment, $comment->user_id);

            if ($contentCheck['auto_flagged']) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Không thể duyệt bình luận này vì chứa nội dung không phù hợp: ' .
                        implode(', ', $contentCheck['reasons']) .
                        '. Vui lòng từ chối hoặc để ở trạng thái chờ duyệt.');
            }
        }

        $comment->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now()
        ]);

        return redirect()->route('admin.comments.show', $id)
            ->with('success', 'Cập nhật trạng thái bình luận thành công!');
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'exists:comments,id',
            'status' => 'required|in:approved,pending,rejected'
        ]);

        $comments = Comment::whereIn('id', $request->comment_ids)->get();

        // Kiểm tra nội dung nhạy cảm khi admin muốn duyệt hàng loạt
        if ($request->status === 'approved') {
            $flaggedComments = [];

            foreach ($comments as $comment) {
                if ($comment->comment) {
                    $contentCheck = $this->contentFilterService->checkContent($comment->comment, $comment->user_id);

                    if ($contentCheck['auto_flagged']) {
                        $flaggedComments[] = [
                            'id' => $comment->id,
                            'user' => $comment->user->name,
                            'reasons' => $contentCheck['reasons']
                        ];
                    }
                }
            }

            if (!empty($flaggedComments)) {
                $errorMessage = 'Không thể duyệt một số bình luận vì chứa nội dung không phù hợp:<br>';
                foreach ($flaggedComments as $flagged) {
                    $errorMessage .= "- ID #{$flagged['id']} ({$flagged['user']}): " . implode(', ', $flagged['reasons']) . '<br>';
                }
                $errorMessage .= 'Vui lòng xem xét từng bình luận riêng lẻ.';

                return redirect()->back()
                    ->with('error', $errorMessage);
            }
        }

        Comment::whereIn('id', $request->comment_ids)->update([
            'status' => $request->status,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now()
        ]);

        return redirect()->route('admin.comments.index')
            ->with('success', 'Cập nhật trạng thái cho ' . count($request->comment_ids) . ' bình luận thành công!');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return redirect()->route('admin.comments.index')
            ->with('success', 'Xóa bình luận thành công!');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'exists:comments,id'
        ]);

        Comment::whereIn('id', $request->comment_ids)->delete();

        return redirect()->route('admin.comments.index')
            ->with('success', 'Xóa ' . count($request->comment_ids) . ' bình luận thành công!');
    }

    public function contentFilterSettings()
    {
        $sensitiveWords = $this->contentFilterService->getSensitiveWords();

        return view('admin.comments.content-filter', compact('sensitiveWords'));
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

        return redirect()->route('admin.comments.content-filter')
            ->with('success', 'Đã thêm từ khóa nhạy cảm: ' . $request->word);
    }

    public function removeSensitiveWord(Request $request)
    {
        $request->validate([
            'word' => 'required|string'
        ]);

        $this->contentFilterService->removeSensitiveWord($request->word);

        return redirect()->route('admin.comments.content-filter')
            ->with('success', 'Đã xóa từ khóa nhạy cảm: ' . $request->word);
    }

    public function recheckComment($id)
    {
        $comment = Comment::findOrFail($id);

        if (!$comment->comment) {
            return redirect()->back()
                ->with('error', 'Không thể kiểm tra lại bình luận không có nội dung.');
        }

        $contentCheck = $this->contentFilterService->checkContent($comment->comment, $comment->user_id);

        // Đề xuất trạng thái mới dựa trên kết quả kiểm tra
        $suggestedStatus = $contentCheck['auto_flagged'] ? 'pending' : 'approved';

        $comment->update([
            'admin_note' => $contentCheck['auto_flagged']
                ? 'Kiểm tra lại: ' . implode(', ', $contentCheck['reasons'])
                : 'Kiểm tra lại: Nội dung hợp lệ',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now()
        ]);

        $statusText = $contentCheck['auto_flagged'] ? 'Chờ duyệt (có vấn đề)' : 'Có thể duyệt (sạch)';

        return redirect()->back()
            ->with('success', 'Đã kiểm tra lại bình luận. Kết quả: ' . $statusText .
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

        $comment = Comment::findOrFail($id);

        $comment->update([
            'status' => 'approved',
            'admin_note' => 'ÉP DUYỆT - Lý do: ' . $request->force_reason,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now()
        ]);

        return redirect()->route('admin.comments.show', $id)
            ->with('warning', 'Đã ép duyệt bình luận. Lý do: ' . $request->force_reason);
    }
}
