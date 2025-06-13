<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AdminAttributeController extends Controller
{
   public function index(Request $request)
{
    $query = Attribute::query();

    // Xử lý tìm kiếm theo tên thuộc tính
    if ($request->has('keyword') && !empty($request->keyword)) {
        $query->where('name', 'like', '%' . $request->keyword . '%');
    }

    $attributes = $query->paginate(10);

    if ($request->has('keyword')) {
            $attributes->appends(['keyword' => $request->keyword]);
        }
    return view('admin.attributes.index', compact('attributes'));
}

    public function create()
    {
        return view('admin.attributes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:attributes,name',
        ], [
            'name.required' => 'Tên thuộc tính là bắt buộc.',
            'name.string' => 'Tên thuộc tính phải là chuỗi ký tự.',
            'name.max' => 'Tên thuộc tính không được vượt quá 100 ký tự.',
            'name.unique' => 'Tên thuộc tính đã tồn tại.',
        ]);

        Attribute::create([
            'name' => $request->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.attributes.index')->with('success', 'Thuộc tính đã được tạo thành công.');
    }

    public function show($id)
    {
    $attribute = Attribute::with('attributeValues')->findOrFail($id);
    return view('admin.attributes.show', compact('attribute'));
    }

    public function edit($id)
    {
        $attribute = Attribute::findOrFail($id);
        return view('admin.attributes.edit', compact('attribute'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:attributes,name,' . $id,
        ], [
            'name.required' => 'Tên thuộc tính là bắt buộc.',
            'name.string' => 'Tên thuộc tính phải là chuỗi ký tự.',
            'name.max' => 'Tên thuộc tính không được vượt quá 100 ký tự.',
            'name.unique' => 'Tên thuộc tính đã tồn tại.',
        ]);

        $attribute = Attribute::findOrFail($id);

        $attribute->update([
            'name' => $request->name,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.attributes.index')->with('success', 'Thuộc tính đã được cập nhật thành công.');
    }
}
