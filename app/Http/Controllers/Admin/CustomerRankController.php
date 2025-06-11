<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomerRank\StoreCustomerRankRequest;
use App\Http\Requests\Admin\CustomerRank\UpdateCustomerRankRequest;
use App\Models\CustomerRank;
use Illuminate\Http\Request;

class CustomerRankController extends Controller
{
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
        CustomerRank::create($data);
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
        $customerRank->update($data);
        return redirect()->route('customers-rank.index')->with('success', 'Customer Rank updated successfully.');
    }

    public function forceDelete(string $id)
    {
        $customerRank = CustomerRank::withTrashed()->findOrFail($id);
        $customerRank->forceDelete();
        return redirect()->route('customers-rank.index')->with('success', 'Customer Rank deleted permanently.');
    }

    public function softDelete(CustomerRank $customerRank)
    {
        if($customerRank->users()->count() > 0) {
            return redirect()->route('customers-rank.index')->with('error', 'Cannot delete this rank as it is assigned to customers.');
        }
        $customerRank->delete();
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
        $customerRank->restore();
        return redirect()->route('customers-rank.deleted')->with('success', 'Customer Rank restored successfully.');
    }
}
