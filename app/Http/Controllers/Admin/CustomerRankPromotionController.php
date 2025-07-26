<?php

namespace App\Http\Controllers\Admin;

use App\Models\Promotion;
use App\Models\CustomerRank;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Enums\NotificationType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\CustomerRankPromotion;
use Illuminate\Validation\ValidationException;

class CustomerRankPromotionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,staff']);
        $this->middleware('can:view customer rank promotion')->only('index');
        $this->middleware('can:create customer rank promotion')->only(['create', 'store']);
        $this->middleware('can:edit customer rank promotion')->only(['edit', 'update']);
        $this->middleware('can:delete customer rank promotion')->only('destroy');
    }
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
        $ranks = CustomerRank::pluck('name', 'id');
        $promotions = Promotion::pluck('code', 'id');
        return view('admin.customer_rank_promotions.create', compact('ranks', 'promotions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_rank_id' => 'required|exists:customer_ranks,id',
            'promotion_id' => 'required|exists:promotions,id',
            'description' => 'nullable|string',
        ]);

        if (CustomerRankPromotion::where('customer_rank_id', $data['customer_rank_id'])
            ->where('promotion_id', $data['promotion_id'])
            ->exists()) {
            throw ValidationException::withMessages([
                'customer_rank_id' => 'Cặp hạng khách hàng và khuyến mãi này đã tồn tại.',
            ]);
        }

        $item = CustomerRankPromotion::create($data);
        // Notification for create
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => 'customer_rank_promotion',
            'entity_id' => $item->id ?? null,
            'title' => 'Thêm khuyến mãi theo hạng khách hàng',
            'message' => 'Đã thêm khuyến mãi cho hạng khách hàng ID: ' . $data['customer_rank_id'] . ', khuyến mãi ID: ' . $data['promotion_id'],
            'type' => NotificationType::System,
            'priority' => 'low',
            'event_details' => json_encode(['action' => 'create', 'data' => $data]),
        ]);
        return redirect()->route('customer_rank_promotions.index')->with('success', 'Thêm khuyến mãi theo hạng khách hàng thành công.');
    }

    public function edit($customer_rank_id, $promotion_id)
    {
        $item = CustomerRankPromotion::where('customer_rank_id', $customer_rank_id)
            ->where('promotion_id', $promotion_id)
            ->firstOrFail();
        return view('admin.customer_rank_promotions.edit', compact('item'));
    }

    public function update(Request $request, $customer_rank_id, $promotion_id)
    {
        // Tìm bản ghi
        $item = CustomerRankPromotion::where('customer_rank_id', $customer_rank_id)
            ->where('promotion_id', $promotion_id)
            ->firstOrFail();

        // Validate dữ liệu
        $data = $request->validate([
            'description' => 'nullable|string',
        ]);

        // Cập nhật thủ công bằng query builder
        $oldData = $item->toArray();
        DB::table('customer_rank_promotions')
            ->where('customer_rank_id', $customer_rank_id)
            ->where('promotion_id', $promotion_id)
            ->update([
                'description' => $data['description'],
            ]);
        // Notification for update
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => 'customer_rank_promotion',
            'entity_id' => $item->id ?? null,
            'title' => 'Cập nhật khuyến mãi theo hạng khách hàng',
            'message' => 'Đã cập nhật khuyến mãi cho hạng khách hàng ID: ' . $customer_rank_id . ', khuyến mãi ID: ' . $promotion_id,
            'type' => NotificationType::System,
            'priority' => 'low',
            'event_details' => json_encode(['action' => 'update', 'old' => $oldData, 'new' => $data]),
        ]);
        return redirect()->route('customer_rank_promotions.index')->with('success', 'Cập nhật khuyến mãi theo hạng khách hàng thành công.');
    }

    public function destroy($customer_rank_id, $promotion_id)
    {
        $item = CustomerRankPromotion::where('customer_rank_id', $customer_rank_id)
            ->where('promotion_id', $promotion_id)
            ->firstOrFail();

        $oldData = $item->toArray();
        DB::table('customer_rank_promotions')
            ->where('customer_rank_id', $customer_rank_id)
            ->where('promotion_id', $promotion_id)
            ->delete();
        // Notification for delete
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => 'customer_rank_promotion',
            'entity_id' => $item->id ?? null,
            'title' => 'Xóa khuyến mãi theo hạng khách hàng',
            'message' => 'Đã xóa khuyến mãi cho hạng khách hàng ID: ' . $customer_rank_id . ', khuyến mãi ID: ' . $promotion_id,
            'type' => NotificationType::System,
            'priority' => 'low',
            'event_details' => json_encode(['action' => 'delete', 'old' => $oldData]),
        ]);
        return redirect()->route('customer_rank_promotions.index')->with('success', 'Xóa khuyến mãi theo hạng khách hàng thành công.');
    }
}