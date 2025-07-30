<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Models\AttributeValue;
use App\Http\Controllers\Controller;

use App\Models\Notification;
use App\Enums\NotificationType;
use Illuminate\Support\Facades\Auth;

class AdminAttributeValueController extends Controller
{
            public function __construct()
    {
        $this->middleware(['auth', 'role:admin,staff']);
        $this->middleware('can:view attribute value')->only('index');
        $this->middleware('can:create attribute value')->only(['create', 'store']);
        $this->middleware('can:edit attribute value')->only(['edit', 'update']);
        $this->middleware('can:delete attribute value')->only('destroy');
    }
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

        $attributeValue = AttributeValue::create([
            'attribute_id' => $request->attribute_id,
            'value' => $request->value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Tạo thông báo khi thêm mới giá trị thuộc tính
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => AttributeValue::class,
            'entity_id' => $attributeValue->id,
            'title' => 'Tạo mới giá trị thuộc tính',
            'message' => 'Giá trị thuộc tính #' . $attributeValue->id . ' đã được tạo mới.',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => null,
            'new_status' => $attributeValue->value,
            'event_details' => json_encode([
                'new' => $attributeValue->getAttributes(),
            ]),
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
        $oldData = $attributeValue->getOriginal();
        $attributeValue->update([
            'attribute_id' => $request->attribute_id,
            'value' => $request->value,
            'updated_at' => now(),
        ]);

        // Tạo thông báo mức độ cao khi cập nhật giá trị thuộc tính
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => AttributeValue::class,
            'entity_id' => $attributeValue->id,
            'title' => 'Cập nhật giá trị thuộc tính',
            'message' => 'Giá trị thuộc tính #' . $attributeValue->id . ' đã được cập nhật.',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => $oldData['value'] ?? null,
            'new_status' => $attributeValue->value,
            'event_details' => json_encode([
                'old' => $oldData,
                'new' => $attributeValue->getAttributes(),
            ]),
        ]);

        return redirect()->route('admin.attribute-values.index')->with('success', 'Giá trị thuộc tính đã được cập nhật thành công.');
    }
}
