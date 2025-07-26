<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Models\Notification;
use App\Enums\NotificationType;
use Illuminate\Support\Facades\Auth;

class AdminAttributeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,staff']);
        $this->middleware('can:view attributes')->only('index');
        $this->middleware('can:create attributes')->only(['create', 'store']);
        $this->middleware('can:edit attributes')->only(['edit', 'update']);
        $this->middleware('can:delete attributes')->only('destroy');
    }
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

        $attribute = Attribute::create([
            'name' => $request->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Tạo thông báo khi thêm mới thuộc tính
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => Attribute::class,
            'entity_id' => $attribute->id,
            'title' => 'Tạo mới thuộc tính',
            'message' => 'Thuộc tính #' . $attribute->id . ' đã được tạo mới.',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => null,
            'new_status' => $attribute->name,
            'event_details' => json_encode([
                'new' => $attribute->getAttributes(),
            ]),
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
        $oldData = $attribute->getOriginal();
        $attribute->update([
            'name' => $request->name,
            'updated_at' => now(),
        ]);

        // Tạo thông báo mức độ cao khi cập nhật thuộc tính
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => Attribute::class,
            'entity_id' => $attribute->id,
            'title' => 'Cập nhật thuộc tính',
            'message' => 'Thuộc tính #' . $attribute->id . ' đã được cập nhật.',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => $oldData['name'] ?? null,
            'new_status' => $attribute->name,
            'event_details' => json_encode([
                'old' => $oldData,
                'new' => $attribute->getAttributes(),
            ]),
        ]);

        return redirect()->route('admin.attributes.index')->with('success', 'Thuộc tính đã được cập nhật thành công.');
    }
}
