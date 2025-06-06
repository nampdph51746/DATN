<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerRankPromotion;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CustomerRankPromotions\StoreCustomerRankPromotionsRequest;
use App\Http\Requests\CustomerRankPromotions\UpdateCustomerRankPromotionsRequest;

class CustomerRankPromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomerRankPromotion::query();

        if ($request->filled('customer_rank_id')) {
            $query->where('customer_rank_id', $request->customer_rank_id);
        }
        if ($request->filled('promotion_id')) {
            $query->where('promotion_id', $request->promotion_id);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('customer_rank_id', 'like', '%' . $request->search . '%')
                    ->orWhere('promotion_id', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        $items = $query->paginate(20);
        return view('admin.customer_rank_promotions.index', compact('items'));
    }

    public function show($customer_rank_id, $promotion_id)
    {
        $item = CustomerRankPromotion::where('customer_rank_id', $customer_rank_id)
            ->where('promotion_id', $promotion_id)
            ->firstOrFail();
        return view('admin.customer_rank_promotions.show', compact('item'));
    }

    public function create()
    {
        $ranks = \App\Models\CustomerRank::pluck('name', 'id');
        $promotions = \App\Models\Promotion::pluck('code', 'id');
        return view('admin.customer_rank_promotions.create', compact('ranks', 'promotions'));
    }

    public function store(StoreCustomerRankPromotionsRequest $request)
    {
        $data = $request->validated();

        if (CustomerRankPromotion::where('customer_rank_id', $data['customer_rank_id'])
            ->where('promotion_id', $data['promotion_id'])
            ->exists()
        ) {
            throw ValidationException::withMessages([
                'customer_rank_id' => 'Cặp hạng khách hàng và khuyến mãi này đã tồn tại.',
            ]);
        }

        CustomerRankPromotion::create($data);
        return redirect()->route('customer_rank_promotions.index')->with('success', 'Thêm khuyến mãi theo hạng khách hàng thành công.');
    }

    public function edit($customer_rank_id, $promotion_id)
    {
        $item = CustomerRankPromotion::where('customer_rank_id', $customer_rank_id)
            ->where('promotion_id', $promotion_id)
            ->firstOrFail();
        return view('admin.customer_rank_promotions.edit', compact('item'));
    }

    public function update(UpdateCustomerRankPromotionsRequest $request, $customer_rank_id, $promotion_id)
    {
        // Tìm bản ghi
        $item = CustomerRankPromotion::where('customer_rank_id', $customer_rank_id)
            ->where('promotion_id', $promotion_id)
            ->firstOrFail();

        // Validate dữ liệu
        $data = $request->validated();

        // Cập nhật thủ công bằng query builder
        DB::table('customer_rank_promotions')
            ->where('customer_rank_id', $customer_rank_id)
            ->where('promotion_id', $promotion_id)
            ->update([
                'description' => $data['description'],
                'discount_code' => $data['discount_code'],
            ]);

        return redirect()->route('customer_rank_promotions.index')->with('success', 'Cập nhật khuyến mãi theo hạng khách hàng thành công.');
    }

    public function destroy($customer_rank_id, $promotion_id)
    {
        $item = CustomerRankPromotion::where('customer_rank_id', $customer_rank_id)
            ->where('promotion_id', $promotion_id)
            ->firstOrFail();

        DB::table('customer_rank_promotions')
            ->where('customer_rank_id', $customer_rank_id)
            ->where('promotion_id', $promotion_id)
            ->delete();

        return redirect()->route('customer_rank_promotions.index')->with('success', 'Xóa khuyến mãi theo hạng khách hàng thành công.');
    }
}
