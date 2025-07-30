<?php

namespace App\Http\Controllers\Admin;

use App\Models\Promotion;
use App\Models\CustomerRank;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Enums\PromotionDiscountType;
use App\Http\Controllers\Controller;
use App\Models\CustomerRankPromotion;
use Illuminate\Contracts\Cache\Store;
use App\Http\Requests\Promotions\StorePromotionsRequest;
use App\Http\Requests\Promotions\UpdatePromotionsRequest;
use \App\Models\Notification;
use \App\Enums\NotificationType;
use \Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,staff']);
        $this->middleware('can:view promotion')->only('index');
        $this->middleware('can:create promotion')->only(['create', 'store']);
        $this->middleware('can:edit promotion')->only(['edit', 'update']);
        $this->middleware('can:delete promotion')->only('destroy');
    }
    
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
        $ranks = CustomerRank::pluck('name', 'id'); // Thêm dòng này
        return view('admin.promotions.create', compact('discountTypes', 'ranks'));
    }

    public function store(StorePromotionsRequest $request)
    {
        $data = $request->validated();

        $promotion = Promotion::create($data);

        // Nếu có chọn hạng khách hàng, tạo liên kết trong bảng customer_rank_promotions
        if (!empty($data['rank_id'])) {
            CustomerRankPromotion::create([
                'customer_rank_id' => $data['rank_id'],
                'promotion_id' => $promotion->id,
                'description' => 'Khuyến mãi dành cho hạng ' . \App\Models\CustomerRank::find($data['rank_id'])->name
            ]);
        }

        // Tạo thông báo mức độ cao khi thêm khuyến mãi
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => Promotion::class,
            'entity_id' => $promotion->id,
            'title' => 'Thêm khuyến mãi',
            'message' => 'Khuyến mãi #' . $promotion->id . ' đã được tạo.',
            'type' => NotificationType::Promotion,
            'priority' => 'high',
            'old_status' => null,
            'new_status' => $promotion->status,
            'event_details' => json_encode([
                'new' => $promotion->getAttributes(),
            ]),
        ]);

        return redirect()->route('promotions.index')->with('success', 'Khuyến mãi đã được tạo thành công.');
    }

    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);
        $discountTypes = PromotionDiscountType::cases();
        $ranks = CustomerRank::pluck('name', 'id'); // Thêm dòng này
        return view('admin.promotions.edit', compact('promotion', 'discountTypes', 'ranks'));
    }

    public function update(UpdatePromotionsRequest $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

        $data = $request->validated();

        try {
            $oldData = $promotion->getOriginal();
            $promotion->update($data);

            // Xử lý cập nhật liên kết customer_rank_promotions
            if (!empty($data['rank_id'])) {
                // Xóa liên kết cũ nếu có
                CustomerRankPromotion::where('promotion_id', $id)->delete();
                // Tạo liên kết mới
                CustomerRankPromotion::create([
                    'customer_rank_id' => $data['rank_id'],
                    'promotion_id' => $promotion->id,
                    'description' => 'Khuyến mãi dành cho hạng ' . CustomerRank::find($data['rank_id'])->name
                ]);
            } else {
                // Nếu không chọn hạng nào, xóa tất cả liên kết
                CustomerRankPromotion::where('promotion_id', $id)->delete();
            }

            // Tạo thông báo mức độ cao khi cập nhật khuyến mãi
            Notification::create([
                'user_id' => Auth::id(),
                'entity_type' => Promotion::class,
                'entity_id' => $promotion->id,
                'title' => 'Cập nhật khuyến mãi',
                'message' => 'Khuyến mãi #' . $promotion->id . ' đã được cập nhật.',
                'type' => NotificationType::Promotion,
                'priority' => 'high',
                'old_status' => $oldData['status'] ?? null,
                'new_status' => $promotion->status,
                'event_details' => json_encode([
                    'old' => $oldData,
                    'new' => $promotion->getAttributes(),
                ]),
            ]);

            return redirect()->route('promotions.index')->with('success', 'Khuyến mãi đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Cập nhật thất bại: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $oldData = $promotion->getOriginal();

        // Xóa tất cả liên kết trong customer_rank_promotions trước khi xóa promotion
        CustomerRankPromotion::where('promotion_id', $id)->delete();

        $promotion->delete(); // Soft delete

        // Tạo thông báo mức độ cao khi xóa mềm khuyến mãi
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => Promotion::class,
            'entity_id' => $promotion->id,
            'title' => 'Xóa khuyến mãi',
            'message' => 'Khuyến mãi #' . $promotion->id . ' đã bị xóa mềm.',
            'type' => NotificationType::Promotion,
            'priority' => 'high',
            'old_status' => $oldData['status'] ?? null,
            'new_status' => null,
            'event_details' => json_encode([
                'old' => $oldData,
            ]),
        ]);

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
        $oldData = $promotion->getOriginal();
        $promotion->restore();

        // Tạo thông báo mức độ cao khi khôi phục khuyến mãi
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => Promotion::class,
            'entity_id' => $promotion->id,
            'title' => 'Khôi phục khuyến mãi',
            'message' => 'Khuyến mãi #' . $promotion->id . ' đã được khôi phục.',
            'type' => NotificationType::Promotion,
            'priority' => 'high',
            'old_status' => null,
            'new_status' => $promotion->status,
            'event_details' => json_encode([
                'old' => $oldData,
                'new' => $promotion->getAttributes(),
            ]),
        ]);

        return redirect()->route('promotions.trashed')->with('success', 'Khôi phục thành công!');
    }

    public function forceDelete($id)
    {
        $promotion = Promotion::onlyTrashed()->findOrFail($id);
        $oldData = $promotion->getOriginal();

        // Xóa tất cả liên kết trong customer_rank_promotions trước khi xóa vĩnh viễn
        CustomerRankPromotion::where('promotion_id', $id)->delete();

        // Lưu id trước khi xóa vĩnh viễn
        $promotionId = $promotion->id;
        $promotion->forceDelete();

        // Tạo thông báo mức độ cao khi xóa cứng khuyến mãi
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => Promotion::class,
            'entity_id' => $promotionId,
            'title' => 'Xóa vĩnh viễn khuyến mãi',
            'message' => 'Khuyến mãi #' . $promotionId . ' đã bị xóa vĩnh viễn.',
            'type' => NotificationType::Promotion,
            'priority' => 'high',
            'old_status' => $oldData['status'] ?? null,
            'new_status' => null,
            'event_details' => json_encode([
                'old' => $oldData,
            ]),
        ]);

        return redirect()->route('promotions.trashed')->with('success', 'Đã xóa vĩnh viễn!');
    }
}