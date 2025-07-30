<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomerRank\StoreCustomerRankRequest;
use App\Http\Requests\Admin\CustomerRank\UpdateCustomerRankRequest;
use App\Models\CustomerRank;
use App\Models\Notification;
use App\Enums\NotificationType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CustomerRankController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,staff']);
        $this->middleware('can:view customer rank')->only('index');
        $this->middleware('can:create customer rank')->only(['create', 'store']);
        $this->middleware('can:edit customer rank')->only(['edit', 'update']);
        $this->middleware('can:delete customer rank')->only('destroy');
    }
    public function index(Request $request)
    {
        $query = CustomerRank::query();

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%')
                ->orWhere('id', $request->keyword)->orWhere('created_at', $request->keyword);
        }

        if ($request->filled('percentage_order') && in_array($request->percentage_order, ['asc', 'desc'])) {
            $query->orderBy('discount_percentage', $request->percentage_order);
        }

        $customerRanks = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        return view('admin.customersRank.list', compact('customerRanks'));
    }

    public function create()
    {
        return view('admin.customersRank.create');
    }

    public function store(StoreCustomerRankRequest $request)
    {
        $data = $request->validated();
        $customerRank = CustomerRank::create($data);
        // Notification for create
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => 'customer_rank',
            'entity_id' => $customerRank->id,
            'title' => 'Tạo hạng khách hàng mới',
            'message' => 'Hạng khách hàng "' . $customerRank->name . '" đã được tạo.',
            'type' => NotificationType::System,
            'priority' => 'medium',
            'event_details' => json_encode(['action' => 'create', 'data' => $data]),
        ]);
        return redirect()->route('customers-rank.index')->with('success', 'Customer Rank created successfully.');
    }

    public function show(string $id)
    {
        $customerRank = CustomerRank::findOrFail($id);
        return view('admin.customersRank.show', compact('customerRank'));
    }

    public function edit(string $id)
    {
        $customerRank = CustomerRank::find($id);
        return view('admin.customersRank.edit', compact('customerRank'));
    }

    public function update(UpdateCustomerRankRequest $request, string $id)
    {
        $data = $request->validated();
        $customerRank = CustomerRank::find($id);
        $oldData = $customerRank->getOriginal();
        $customerRank->update($data);
        // Notification for update
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => 'customer_rank',
            'entity_id' => $customerRank->id,
            'title' => 'Cập nhật hạng khách hàng',
            'message' => 'Hạng khách hàng "' . $customerRank->name . '" đã được cập nhật.',
            'type' => NotificationType::System,
            'priority' => 'medium',
            'event_details' => json_encode(['action' => 'update', 'old' => $oldData, 'new' => $data]),
        ]);
        return redirect()->route('customers-rank.index')->with('success', 'Customer Rank updated successfully.');
    }

    public function forceDelete(string $id)
    {
        $customerRank = CustomerRank::withTrashed()->findOrFail($id);
        $oldData = $customerRank->toArray();
        $customerRank->forceDelete();
        // Notification for force delete
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => 'customer_rank',
            'entity_id' => $id,
            'title' => 'Xóa vĩnh viễn hạng khách hàng',
            'message' => 'Hạng khách hàng "' . $oldData['name'] . '" đã bị xóa vĩnh viễn.',
            'type' => NotificationType::System,
            'priority' => 'medium',
            'event_details' => json_encode(['action' => 'forceDelete', 'old' => $oldData]),
        ]);
        return redirect()->route('customers-rank.index')->with('success', 'Customer Rank deleted permanently.');
    }

    public function softDelete(CustomerRank $customerRank)
    {
        if($customerRank->users()->count() > 0) {
            return redirect()->route('customers-rank.index')->with('error', 'Cannot delete this rank as it is assigned to customers.');
        }
        $oldData = $customerRank->toArray();
        $customerRank->delete();
        // Notification for soft delete
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => 'customer_rank',
            'entity_id' => $customerRank->id,
            'title' => 'Xóa hạng khách hàng',
            'message' => 'Hạng khách hàng "' . $oldData['name'] . '" đã bị xóa.',
            'type' => NotificationType::System,
            'priority' => 'medium',
            'event_details' => json_encode(['action' => 'softDelete', 'old' => $oldData]),
        ]);
        return redirect()->route('customers-rank.index')->with('success', 'Customer Rank deleted successfully.');
    }

    public function deleted(Request $request)
    {
        $query = CustomerRank::onlyTrashed();

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $customerRanks = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('admin.customersRank.deleted', compact('customerRanks'));
    }

    public function deletedShow($id)
    {
        $customerRank  = CustomerRank::withTrashed()->findOrFail($id);
        return view('admin.customersRank.deleted-show', compact('customerRank'));
    }

    public function restore($id)
    {
        $customerRank = CustomerRank::withTrashed()->findOrFail($id);
        $oldData = $customerRank->toArray();
        $customerRank->restore();
        // Notification for restore
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => 'customer_rank',
            'entity_id' => $customerRank->id,
            'title' => 'Khôi phục hạng khách hàng',
            'message' => 'Hạng khách hàng "' . $oldData['name'] . '" đã được khôi phục.',
            'type' => NotificationType::System,
            'priority' => 'medium',
            'event_details' => json_encode(['action' => 'restore', 'old' => $oldData]),
        ]);
        return redirect()->route('customers-rank.deleted')->with('success', 'Customer Rank restored successfully.');
    }
}
