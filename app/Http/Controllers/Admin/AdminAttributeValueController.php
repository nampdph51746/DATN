<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Models\AttributeValue;
use App\Http\Controllers\Controller;

class AdminAttributeValueController extends Controller
{
    public function index(Request $request)
    {
        $query = AttributeValue::with('attribute');

        // Xử lý tìm kiếm theo giá trị thuộc tính
        if ($request->has('keyword') && !empty($request->keyword)) {
            $query->where('value', 'like', '%' . $request->keyword . '%');
        }

        $attributeValues = $query->paginate(10);

        // Thêm tham số keyword vào các liên kết phân trang
        if ($request->has('keyword')) {
            $attributeValues->appends(['keyword' => $request->keyword]);
        }

        return view('admin.attribute_values.index', compact('attributeValues'));
    }

    public function create()
    {
        $attributes = Attribute::all();
        return view('admin.attribute_values.create', compact('attributes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:100',
        ], [
            'attribute_id.required' => 'Thuộc tính là bắt buộc.',
            'attribute_id.exists' => 'Thuộc tính không tồn tại.',
            'value.required' => 'Giá trị thuộc tính là bắt buộc.',
            'value.string' => 'Giá trị thuộc tính phải là chuỗi ký tự.',
            'value.max' => 'Giá trị thuộc tính không được vượt quá 100 ký tự.',
        ]);

        AttributeValue::create([
            'attribute_id' => $request->attribute_id,
            'value' => $request->value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.attribute-values.index')->with('success', 'Giá trị thuộc tính đã được tạo thành công.');
    }

    public function show($id)
    {
        $attributeValue = AttributeValue::with('attribute')->findOrFail($id);
        return view('admin.attribute_values.show', compact('attributeValue'));
    }

    public function edit($id)
    {
        $attributeValue = AttributeValue::findOrFail($id);
        $attributes = Attribute::all();
        return view('admin.attribute_values.edit', compact('attributeValue', 'attributes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:100',
        ], [
            'attribute_id.required' => 'Thuộc tính là bắt buộc.',
            'attribute_id.exists' => 'Thuộc tính không tồn tại.',
            'value.required' => 'Giá trị thuộc tính là bắt buộc.',
            'value.string' => 'Giá trị thuộc tính phải là chuỗi ký tự.',
            'value.max' => 'Giá trị thuộc tính không được vượt quá 100 ký tự.',
        ]);

        $attributeValue = AttributeValue::findOrFail($id);

        $attributeValue->update([
            'attribute_id' => $request->attribute_id,
            'value' => $request->value,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.attribute-values.index')->with('success', 'Giá trị thuộc tính đã được cập nhật thành công.');
    }
}
