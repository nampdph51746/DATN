<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

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

        $paymentMethod->update([
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('payment_methods.index')
            ->with('success', 'Trạng thái phương thức thanh toán đã được cập nhật.');
    }
}
