<?php

namespace App\Http\Controllers\Admin;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

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

    // Hiển thị form chỉnh sửa
    public function edit($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        return view('admin.payment_methods.edit', compact('paymentMethod'));
    }

    // Cập nhật phương thức thanh toán
    public function update(Request $request, $id)
    {
        // Debug
        \Log::info('PaymentMethod Update - ID received: ' . $id);
        \Log::info('PaymentMethod Update - Request data: ' . json_encode($request->all()));
        
        $paymentMethod = PaymentMethod::findOrFail($id);
        
        // Chỉ validate logo và trạng thái
        $request->validate([
            'logo_url' => 'nullable|url|max:500',
            'logo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
        ]);

        // Chỉ cập nhật trạng thái
        $data = [
            'is_active' => $request->has('is_active'),
        ];

        // Xử lý logo
        if ($request->hasFile('logo_file')) {
            // Xóa logo cũ nếu có
            if ($paymentMethod->logo_url && str_starts_with($paymentMethod->logo_url, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $paymentMethod->logo_url);
                Storage::disk('public')->delete($oldPath);
            }

            // Lưu logo mới
            $logoPath = $request->file('logo_file')->store('payment_logos', 'public');
            $data['logo_url'] = '/storage/' . $logoPath;
        } elseif ($request->filled('logo_url')) {
            // Nếu có URL logo, xóa file cũ nếu có
            if ($paymentMethod->logo_url && str_starts_with($paymentMethod->logo_url, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $paymentMethod->logo_url);
                Storage::disk('public')->delete($oldPath);
            }
            $data['logo_url'] = $request->logo_url;
        }

        $paymentMethod->update($data);

        return redirect()->route('admin.payment_methods.index')
            ->with('success', 'Logo và trạng thái phương thức thanh toán đã được cập nhật thành công.');
    }

    // Backward compatibility - redirect to edit
    public function editStatus(PaymentMethod $paymentMethod)
    {
        return redirect()->route('admin.payment_methods.edit', $paymentMethod);
    }

    // Backward compatibility - redirect to edit
    public function updateStatus(Request $request, PaymentMethod $paymentMethod)
    {
        return redirect()->route('admin.payment_methods.edit', $paymentMethod);
    }
}
