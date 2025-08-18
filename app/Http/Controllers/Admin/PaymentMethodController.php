<?php

namespace App\Http\Controllers\Admin;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Notification;
use App\Enums\NotificationType;
use Illuminate\Support\Facades\Auth;
class PaymentMethodController extends Controller
{
    // Danh sách tìm kiếm và lọc
    public function index(Request $request)
    {
        $query = PaymentMethod::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $paymentMethods = $query->paginate(10)->withQueryString();

        return view('admin.payment_methods.index', compact('paymentMethods'));
    }

    // Xem chi tiết
    public function show($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        return view('admin.payment_methods.show', compact('paymentMethod'));
    }

    public function editStatus(PaymentMethod $paymentMethod)
    {
        return view('admin.payment_methods.edit_status', compact('paymentMethod'));
    }

    public function updateStatus(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $oldData = $paymentMethod->getOriginal();
        $paymentMethod->update([
            'is_active' => $request->is_active,
        ]);

        // Tạo thông báo mức độ thấp khi cập nhật trạng thái phương thức thanh toán
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => PaymentMethod::class,
            'entity_id' => $paymentMethod->id,
            'title' => 'Cập nhật trạng thái phương thức thanh toán',
            'message' => 'Phương thức thanh toán #' . $paymentMethod->id . ' đã được cập nhật trạng thái.',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => $oldData['is_active'] ?? null,
            'new_status' => $paymentMethod->is_active,
            'event_details' => json_encode([
                'old' => $oldData,
                'new' => $paymentMethod->getAttributes(),
            ]),
        ]);

        return redirect()->route('payment_methods.index')
            ->with('success', 'Trạng thái phương thức thanh toán đã được cập nhật.');
    }
}
