<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use function Laravel\Prompts\alert;

class CountryController extends Controller
{
    // Hiển thị danh sách quốc gia chưa xóa
    public function index(Request $request)
    {
        $query = Country::orderBy('created_at', 'desc');

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $countries = $query->paginate(10);
        $countries->appends($request->only('keyword'));

        return view('admin.countries.list', compact('countries'));
    }

    // Hiển thị danh sách quốc gia đã xóa mềm (trashed)
    public function trash(Request $request)
    {
        $query = Country::onlyTrashed()->orderBy('deleted_at', 'desc');

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $countries = $query->paginate(10);
        $countries->appends($request->only('keyword'));

        return view('admin.countries.trash', compact('countries'));
    }

    // Hiển thị form thêm mới
    public function create()
    {
        return view('admin.countries.add');
    }

    // Lưu quốc gia mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:countries,code',
        ]);

        Country::create($validated);

        return redirect()->route('admin.countries.index')->with('success', 'Đã thêm quốc gia mới.');
    }

    // Hiển thị form sửa
    public function edit($id)
    {
        $country = Country::findOrFail($id);
        return view('admin.countries.edit', compact('country'));
    }

    // Cập nhật quốc gia
    public function update(Request $request, $id)
    {
        $country = Country::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:countries,code,' . $id,
        ]);

        $country->update($validated);

        return redirect()->route('admin.countries.index')->with('success', 'Cập nhật thành công.');
    }

    // Xóa mềm quốc gia
    public function destroy($id)
    {
        $country = Country::findOrFail($id);
        $country->delete();

        alert('Quốc gia đã được xóa thành công.');
        return redirect()->route('admin.countries.index')->with('success', 'Đã xóa quốc gia.');
    }

    // Khôi phục quốc gia đã xóa mềm
    public function restore($id)
    {
        $country = Country::onlyTrashed()->findOrFail($id);
        $country->restore();

        alert('Quốc gia đã được khôi phục thành công.');
        return redirect()->route('admin.countries.trash')->with('success', 'Quốc gia đã được khôi phục.');
    }

    // Xóa vĩnh viễn quốc gia
    public function forceDelete($id)
    {
        $country = Country::onlyTrashed()->findOrFail($id);
        $country->forceDelete();

        alert('Quốc gia đã bị xóa vĩnh viễn.');
        return redirect()->route('admin.countries.trash')->with('success', 'Đã xóa quốc gia vĩnh viễn.');
    }
}