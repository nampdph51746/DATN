<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgeLimit;

class AgeLimitController extends Controller
{
    public function index()
    {
        $ageLimits = AgeLimit::orderBy('min_age')->paginate(10);
        return view('admin.ageLimit.index', compact('ageLimits'));
    }

    public function create()
    {
        return view('admin.ageLimit.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:age_limits,name',
            'description' => 'nullable|string',
            'min_age' => 'nullable|integer|min:0',
        ]);
        AgeLimit::create($request->only('name', 'description', 'min_age'));
        return redirect()->route('admin.age_limits.index')->with('success', 'Thêm giới hạn độ tuổi thành công!');
    }

    public function edit($id)
    {
        $ageLimit = AgeLimit::findOrFail($id);
        return view('admin.movies.ageLimit.edit', compact('ageLimit'));
    }

    public function update(Request $request, $id)
    {
        $ageLimit = AgeLimit::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:50|unique:age_limits,name,' . $id,
            'description' => 'nullable|string',
            'min_age' => 'nullable|integer|min:0',
        ]);
        $ageLimit->update($request->only('name', 'description', 'min_age'));
        return redirect()->route('admin.age_limits.index')->with('success', 'Cập nhật thành công!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->ids);
        AgeLimit::whereIn('id', $ids)->delete();
        return redirect()->route('admin.age_limits.index')->with('success', 'Đã xóa các giới hạn độ tuổi đã chọn!');
    }

    public function destroy($id)
    {
        AgeLimit::destroy($id);
        return redirect()->route('admin.age_limits.index')->with('success', 'Đã xóa!');
    }
}