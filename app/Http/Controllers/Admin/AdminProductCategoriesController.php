<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductCategory;
use App\Models\Product;

use App\Models\Notification;
use App\Enums\NotificationType;
use Illuminate\Support\Facades\Auth;

class AdminProductCategoriesController extends Controller
{
        public function __construct()
    {
        $this->middleware(['auth', 'role:admin,staff']);
        $this->middleware('can:view product category')->only('index');
        $this->middleware('can:create product category')->only(['create', 'store']);
        $this->middleware('can:edit product category')->only(['edit', 'update']);
        $this->middleware('can:delete product category')->only('destroy');
    }
    public function index()
    {
        $categories = ProductCategory::paginate(10);
        return view('admin.product_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.product_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:product_categories,name',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Tên danh mục là bắt buộc.',
            'name.string' => 'Tên danh mục phải là chuỗi ký tự.',
            'name.max' => 'Tên danh mục không được vượt quá 100 ký tự.',
            'name.unique' => 'Tên danh mục đã tồn tại, vui lòng chọn tên khác.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
        ]);

        $category = ProductCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Tạo thông báo khi thêm mới danh mục sản phẩm
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => ProductCategory::class,
            'entity_id' => $category->id,
            'title' => 'Tạo mới danh mục sản phẩm',
            'message' => 'Danh mục sản phẩm #' . $category->id . ' đã được tạo mới.',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => null,
            'new_status' => $category->name,
            'event_details' => json_encode([
                'new' => $category->getAttributes(),
            ]),
        ]);

        return redirect()->route('admin.product-categories.index')->with('success', 'Danh mục sản phẩm đã được tạo thành công.');
    }

    public function edit($id)
    {
        $category = ProductCategory::findOrFail($id);
        return view('admin.product_categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:product_categories,name,' . $id,
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Tên danh mục là bắt buộc.',
            'name.string' => 'Tên danh mục phải là chuỗi ký tự.',
            'name.max' => 'Tên danh mục không được vượt quá 100 ký tự.',
            'name.unique' => 'Tên danh mục đã tồn tại, vui lòng chọn tên khác.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
        ]);

        $category = ProductCategory::findOrFail($id);
        $oldData = $category->getOriginal();
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'updated_at' => now(),
        ]);

        // Tạo thông báo mức độ cao khi cập nhật danh mục sản phẩm
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => ProductCategory::class,
            'entity_id' => $category->id,
            'title' => 'Cập nhật danh mục sản phẩm',
            'message' => 'Danh mục sản phẩm #' . $category->id . ' đã được cập nhật.',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => $oldData['name'] ?? null,
            'new_status' => $category->name,
            'event_details' => json_encode([
                'old' => $oldData,
                'new' => $category->getAttributes(),
            ]),
        ]);

        return redirect()->route('admin.product-categories.index')->with('success', 'Danh mục sản phẩm đã được cập nhật thành công.');
    }

    public function destroy($id)
    {
        $category = ProductCategory::findOrFail($id);
        
        // Sửa tên cột từ product_category_id thành category_id
        $productCount = Product::where('category_id', $id)->count();

        if ($productCount > 0) {
            return redirect()->route('admin.product-categories.index')
                ->with('error', 'Không thể xóa danh mục vì vẫn còn ' . $productCount . ' sản phẩm thuộc danh mục này.');
        }

        $oldData = $category->getOriginal();
        $category->delete();
        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Danh mục sản phẩm đã được xóa thành công.');
    }

    public function trash()
    {
        $categories = ProductCategory::onlyTrashed()->paginate(10);
        return view('admin.product_categories.trash', compact('categories'));
    }

    public function restore($id)
    {
        $category = ProductCategory::withTrashed()->findOrFail($id);
        $category->restore();
        return redirect()->route('admin.product-categories.index')->with('success', 'Danh mục sản phẩm đã được khôi phục thành công.');
    }

    public function forceDelete($id)
    {
        $category = ProductCategory::onlyTrashed()->findOrFail($id);
        $oldData = $category->getOriginal();
        $category->forceDelete();

        // Tạo thông báo khi xóa cứng danh mục sản phẩm
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => ProductCategory::class,
            'entity_id' => $category->id,
            'title' => 'Xóa vĩnh viễn danh mục sản phẩm',
            'message' => 'Danh mục sản phẩm #' . $category->id . ' đã bị xóa vĩnh viễn.',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => $oldData['name'] ?? null,
            'new_status' => null,
            'event_details' => json_encode([
                'old' => $oldData,
            ]),
        ]);

        return redirect()->route('admin.product-categories.trash')->with('success', 'Danh mục sản phẩm đã được xóa vĩnh viễn.');
    }
}
