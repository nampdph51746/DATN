<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $query = City::with('country')->latest();

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $cities = $query->paginate(10);
        $cities->appends($request->only('keyword'));

        return view('admin.cities.list', compact('cities'));
    }

    public function create()
    {
        $countries = Country::latest()->get();
        return view('admin.cities.add', compact('countries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
        ]);

        City::create($validated);

        return redirect()->route('admin.cities.index')->with('success', 'Đã thêm thành phố mới.');
    }

    public function edit($id)
    {
        $city = City::findOrFail($id);
        $countries = Country::latest()->get();

        return view('admin.cities.edit', compact('city', 'countries'));
    }

    public function update(Request $request, $id)
    {
        $city = City::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
        ]);

        $city->update($validated);

        return redirect()->route('admin.cities.index')->with('success', 'Cập nhật thành công.');
    }

    // ✅ XÓA MỀM
    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();

        return redirect()->route('admin.cities.trash')->with('success', 'Đã xóa thành phố (tạm thời).');
    }

    // ✅ XEM DANH SÁCH ĐÃ XÓA
    public function trash(Request $request)
    {
        $query = City::onlyTrashed()->with('country')->latest();

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $cities = $query->paginate(10);
        $cities->appends($request->only('keyword'));

        return view('admin.cities.trash', compact('cities'));
    }

    // ✅ KHÔI PHỤC
    public function restore($id)
    {
        $city = City::onlyTrashed()->findOrFail($id);
        $city->restore();

        return redirect()->route('admin.cities.trash')->with('success', 'Đã khôi phục thành phố.');
    }

    // ✅ XÓA VĨNH VIỄN
    public function forceDelete($id)
    {
        $city = City::onlyTrashed()->findOrFail($id);
        $city->forceDelete();

        return redirect()->route('admin.cities.trash')->with('success', 'Đã xóa vĩnh viễn thành phố.');
    }
}