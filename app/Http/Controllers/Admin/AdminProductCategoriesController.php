<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductCategory;
use App\Models\Product;

class AdminProductCategoriesController extends Controller
{
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

        ProductCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('product-categories.index')->with('success', 'Danh mục sản phẩm đã được tạo thành công.');
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
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'updated_at' => now(),
        ]);

        return redirect()->route('product-categories.index')->with('success', 'Danh mục sản phẩm đã được cập nhật thành công.');
    }

    public function destroy($id)
    {
        $category = ProductCategory::findOrFail($id);
        $productCount = Product::where('category_id', $id)->count();

        if ($productCount > 0) {
            return redirect()->route('product-categories.index')->with('error', 'Không thể xóa danh mục vì vẫn còn ' . $productCount . ' sản phẩm thuộc danh mục này.');
        }

        $category->delete();
        return redirect()->route('product-categories.index')->with('success', 'Danh mục sản phẩm đã được xóa thành công.');
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
        return redirect()->route('product-categories.index')->with('success', 'Danh mục sản phẩm đã được khôi phục thành công.');
    }

    public function forceDelete($id)
    {
        $category = ProductCategory::onlyTrashed()->findOrFail($id);
        $category->forceDelete();
        return redirect()->route('product-categories.trash')->with('success', 'Danh mục sản phẩm đã được xóa vĩnh viễn.');
    }
}
