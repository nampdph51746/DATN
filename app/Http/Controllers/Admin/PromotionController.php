<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PromotionDiscountType;
use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = Promotion::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('code', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('discount_type'), fn($q) => $q->where('discount_type', $request->discount_type))
            ->when($request->filled('name'), fn($q) => $q->where('name', 'like', '%' . $request->name . '%'))
            ->when($request->filled('start_date'), fn($q) => $q->whereDate('start_date', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn($q) => $q->whereDate('end_date', '<=', $request->end_date));

        $promotions = $query->orderBy('created_at', 'desc')->paginate(20);
        $discountTypes = PromotionDiscountType::cases();

        return view('admin.promotions.index', compact('promotions', 'discountTypes'));
    }

    public function show($id)
    {
        $promotion = Promotion::findOrFail($id);
        return view('admin.promotions.show', compact('promotion'));
    }

    public function create()
    {
        $discountTypes = PromotionDiscountType::cases();
        return view('admin.promotions.create', compact('discountTypes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:promotions,code',
            'description' => 'nullable|string',
            'discount_type' => ['required', Rule::in(array_column(PromotionDiscountType::cases(), 'value'))],
            'discount_value' => 'required|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'min_booking_value' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'quantity' => 'nullable|integer|min:0',
            'usage_limit_per_user' => 'nullable|integer|min:0',
            'applies_to' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:20|in:active,pending,inactive',
        ]);

        Promotion::create($data);
        return redirect()->route('promotions.index')->with('success', 'Khuyến mãi đã được tạo thành công.');
    }

    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);
        $discountTypes = PromotionDiscountType::cases();
        return view('admin.promotions.edit', compact('promotion', 'discountTypes'));
    }

    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', Rule::unique('promotions')->ignore($promotion->id)],
            'description' => 'nullable|string',
            'discount_type' => ['required', Rule::in(array_column(PromotionDiscountType::cases(), 'value'))],
            'discount_value' => 'required|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'min_booking_value' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'quantity' => 'nullable|integer|min:0',
            'usage_limit_per_user' => 'nullable|integer|min:0',
            'applies_to' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:20|in:active,pending,inactive',
        ]);

        try {
            $promotion->update($data);
            return redirect()->route('promotions.index')->with('success', 'Khuyến mãi đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Cập nhật thất bại: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete(); // Soft delete
        return redirect()->route('promotions.trashed')->with('success', 'Khuyến mãi đã được xóa mềm thành công.');
    }

    public function trashed()
    {
        $promotions = Promotion::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(20);
        $discountTypes = PromotionDiscountType::cases();
        return view('admin.promotions.trashed', compact('promotions', 'discountTypes'));
    }

    public function restore($id)
    {
        $promotion = Promotion::onlyTrashed()->findOrFail($id);
        $promotion->restore();
        return redirect()->route('promotions.trashed')->with('success', 'Khôi phục thành công!');
    }

    public function forceDelete($id)
    {
        $promotion = Promotion::onlyTrashed()->findOrFail($id);
        $promotion->forceDelete();
        return redirect()->route('promotions.trashed')->with('success', 'Đã xóa vĩnh viễn!');
    }
}
