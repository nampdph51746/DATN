<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PromotionDiscountType;
use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\Promotions\StorePromotionsRequest;
use App\Http\Requests\Promotions\UpdatePromotionsRequest;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = Promotion::with('rank')
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
        $promotion = Promotion::with('rank')->findOrFail($id);
        return view('admin.promotions.show', compact('promotion'));
    }

    public function create()
    {
        $discountTypes = PromotionDiscountType::cases();
        $ranks = \App\Models\CustomerRank::pluck('name', 'id'); // Thêm dòng này
        return view('admin.promotions.create', compact('discountTypes', 'ranks'));
    }

    public function store(StorePromotionsRequest $request)
    {
        $data = $request->validated();

        $promotion = Promotion::create($data);
        
        // Nếu có chọn hạng khách hàng, tạo liên kết trong bảng customer_rank_promotions
        if (!empty($data['rank_id'])) {
            \App\Models\CustomerRankPromotion::create([
                'customer_rank_id' => $data['rank_id'],
                'promotion_id' => $promotion->id,
                'description' => 'Khuyến mãi dành cho hạng ' . \App\Models\CustomerRank::find($data['rank_id'])->name
            ]);
        }
        
        return redirect()->route('promotions.index')->with('success', 'Khuyến mãi đã được tạo thành công.');
    }

    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);
        $discountTypes = PromotionDiscountType::cases();
        $ranks = \App\Models\CustomerRank::pluck('name', 'id'); // Thêm dòng này
        return view('admin.promotions.edit', compact('promotion', 'discountTypes', 'ranks'));
    }

    public function update(UpdatePromotionsRequest $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

        $data = $request->validated();

        try {
            $promotion->update($data);
            
            // Xử lý cập nhật liên kết customer_rank_promotions
            if (!empty($data['rank_id'])) {
                // Xóa liên kết cũ nếu có
                \App\Models\CustomerRankPromotion::where('promotion_id', $id)->delete();
                
                // Tạo liên kết mới
                \App\Models\CustomerRankPromotion::create([
                    'customer_rank_id' => $data['rank_id'],
                    'promotion_id' => $promotion->id,
                    'description' => 'Khuyến mãi dành cho hạng ' . \App\Models\CustomerRank::find($data['rank_id'])->name
                ]);
            } else {
                // Nếu không chọn hạng nào, xóa tất cả liên kết
                \App\Models\CustomerRankPromotion::where('promotion_id', $id)->delete();
            }
            
            return redirect()->route('promotions.index')->with('success', 'Khuyến mãi đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Cập nhật thất bại: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        
        // Xóa tất cả liên kết trong customer_rank_promotions trước khi xóa promotion
        \App\Models\CustomerRankPromotion::where('promotion_id', $id)->delete();
        
        $promotion->delete(); // Soft delete
        return redirect()->route('promotions.trashed')->with('success', 'Khuyến mãi đã được xóa mềm thành công.');
    }

    public function trashed()
    {
        $promotions = Promotion::onlyTrashed()->with('rank')->orderBy('deleted_at', 'desc')->paginate(20);
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
        
        // Xóa tất cả liên kết trong customer_rank_promotions trước khi xóa vĩnh viễn
        \App\Models\CustomerRankPromotion::where('promotion_id', $id)->delete();
        
        $promotion->forceDelete();
        return redirect()->route('promotions.trashed')->with('success', 'Đã xóa vĩnh viễn!');
    }
}