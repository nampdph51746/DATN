<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\Notification;
use App\Enums\NotificationType;
use Illuminate\Support\Facades\Auth;

class AdminProductController extends Controller
{
            public function __construct()
    {
        $this->middleware(['auth', 'role:admin,staff']);
        $this->middleware('can:view product')->only('index');
        $this->middleware('can:create product')->only(['create', 'store']);
        $this->middleware('can:edit product')->only(['edit', 'update']);
        $this->middleware('can:delete product')->only('destroy');
    }
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
            'sku' => 'nullable|string|max:100|unique:products,sku',
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
            'sku.unique' => 'SKU đã tồn tại.',
            'sku.max' => 'SKU không được vượt quá 100 ký tự.',
            'sku.string' => 'SKU phải là chuỗi ký tự.',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageUrl = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'sku' => $request->sku,
            'description' => $request->description,
            'image_url' => $imageUrl,
            'product_type' => $request->product_type,
            'is_active' => $request->is_active ?? 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Tạo thông báo khi thêm mới sản phẩm
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => Product::class,
            'entity_id' => $product->id,
            'title' => 'Tạo mới sản phẩm',
            'message' => 'Sản phẩm #' . $product->id . ' đã được tạo mới.',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => null,
            'new_status' => $product->name,
            'event_details' => json_encode([
                'new' => $product->getAttributes(),
            ]),
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
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $id,
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
            'sku.unique' => 'SKU đã tồn tại.',
            'sku.max' => 'SKU không được vượt quá 100 ký tự.',
            'sku.string' => 'SKU phải là chuỗi ký tự.',
        ]);

        $product = Product::findOrFail($id);
        $oldData = $product->getOriginal();

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
            'sku' => $request->sku,
            'description' => $request->description,
            'image_url' => $imageUrl, // Đảm bảo trường này đúng tên trong DB
            'product_type' => $request->product_type,
            'is_active' => $request->is_active,
            'updated_at' => now(),
        ]);

        // Tạo thông báo mức độ cao khi cập nhật sản phẩm
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => Product::class,
            'entity_id' => $product->id,
            'title' => 'Cập nhật sản phẩm',
            'message' => 'Sản phẩm #' . $product->id . ' đã được cập nhật.',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => $oldData['name'] ?? null,
            'new_status' => $product->name,
            'event_details' => json_encode([
                'old' => $oldData,
                'new' => $product->getAttributes(),
            ]),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật thành công.');
    }

    public function getVariants($id)
    {
        $product = Product::with('productVariants')->find($id);

        if (!$product) {
            return response()->json(['error' => 'Sản phẩm không tồn tại'], 404);
        }

        $variants = $product->productVariants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'sku' => $variant->sku,
                'image_url' => $variant->image_url ?? null,
            ];
        });

        return response()->json($variants);
    }
}
