<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'product_type' => 'required|in:food,drink,combo',
            'is_active' => 'required|in:0,1',
        ], [
            'category_id.required' => 'Danh mục sản phẩm là bắt buộc.',
            'category_id.exists' => 'Danh mục sản phẩm không tồn tại.',
            'name.required' => 'Tên sản phẩm là bắt buộc.',
            'name.string' => 'Tên sản phẩm phải là chuỗi ký tự.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'image_url.required' => 'Bạn phải tải lên ảnh sản phẩm.',
            'product_type.required' => 'Loại sản phẩm là bắt buộc.',
            'product_type.in' => 'Loại sản phẩm không hợp lệ.',
            'is_active.required' => 'Trạng thái sản phẩm là bắt buộc.',
            'is_active.in' => 'Trạng thái sản phẩm phải là Hoạt động hoặc không hoạt động.',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageUrl = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'image_url' => $imageUrl,
            'product_type' => $request->product_type,
            'is_active' => $request->is_active ?? 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được tạo thành công.');
    }

    public function show($id)
    {
    $product = Product::with(['category', 'productVariants'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        
        $categories = ProductCategory::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'product_type' => 'required|in:food,drink,combo',
            'is_active' => 'required|in:0,1',
        ], [
            'category_id.required' => 'Danh mục sản phẩm là bắt buộc.',
            'category_id.exists' => 'Danh mục sản phẩm không tồn tại.',
            'name.required' => 'Tên sản phẩm là bắt buộc.',
            'name.string' => 'Tên sản phẩm phải là chuỗi ký tự.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'image.image' => 'File tải lên phải là ảnh.',
            'image.mimes' => 'Ảnh phải có định dạng jpg, jpeg, png hoặc gif.',
            'image.max' => 'Ảnh không được vượt quá 2MB.',
            'product_type.required' => 'Loại sản phẩm là bắt buộc.',
            'product_type.in' => 'Loại sản phẩm không hợp lệ.',
            'is_active.required' => 'Trạng thái sản phẩm là bắt buộc.',
            'is_active.in' => 'Trạng thái sản phẩm phải là Hoạt động hoặc Không hoạt động.',
        ]);

        $product = Product::findOrFail($id);

        // Xử lý ảnh
        $imageUrl = $product->image_url;
        if ($request->hasFile('image')) {
            if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
                Storage::disk('public')->delete($product->image_url);
            }
            $imageUrl = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'image_url' => $imageUrl, // Đảm bảo trường này đúng tên trong DB
            'product_type' => $request->product_type,
            'is_active' => $request->is_active,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật thành công.');
    }
}
