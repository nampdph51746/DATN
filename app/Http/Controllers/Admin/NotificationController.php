<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     */
    public function index(Request $request)
    {
        $query = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $notifications = $query->paginate(15)->withQueryString();
        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Đánh dấu thông báo đã đọc (AJAX)
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        if (!$notification->read_at) {
            $notification->read_at = now();
            $notification->save();
        }
        return response()->json([
            'success' => true,
            'read_at' => $notification->read_at ? $notification->read_at->format('H:i d/m/Y') : null,
        ]);
    }
}
