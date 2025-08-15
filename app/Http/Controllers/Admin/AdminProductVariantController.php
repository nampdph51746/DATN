<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Attribute;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AttributeValue;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;
use App\Models\ProductVariantOption;
use Illuminate\Support\Facades\Storage;

use App\Models\Notification;
use App\Enums\NotificationType;
use Illuminate\Support\Facades\Auth;

class AdminProductVariantController extends Controller
{
        public function __construct()
    {
        $this->middleware(['auth', 'role:admin,staff']);
        $this->middleware('can:view product variant')->only('index');
        $this->middleware('can:create product variant')->only(['create', 'store']);
        $this->middleware('can:edit product variant')->only(['edit', 'update']);
        $this->middleware('can:delete product variant')->only('destroy');
    }
    public function index()
    {
        $productVariants = ProductVariant::with(['product', 'productVariantOptions.attributeValue.attribute'])->paginate(10);
        return view('admin.product_variants.index', compact('productVariants'));
    }

    public function create(Request $request)
    {
        $products = Product::all();
        $selectedProductId = $request->input('product_id');
        $attributes = Attribute::with('attributeValues')->get();
        
        // Thêm dòng này để lấy selectedProduct
        $selectedProduct = null;
        if ($selectedProductId) {
            $selectedProduct = Product::find($selectedProductId);
        }

        return view('admin.product_variants.create', compact('products', 'selectedProductId', 'selectedProduct', 'attributes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'default_price' => 'required|numeric|min:0',
            'default_stock_quantity' => 'required|integer|min:0',
            'image_url' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'is_active' => 'required|in:0,1',
            'attribute_values' => 'required|array|min:1',
            'attribute_values.*.*' => 'exists:attribute_values,id',
            'variant_prices' => 'required|array',
            'variant_prices.*' => 'required|numeric|min:0',
            'variant_stocks' => 'required|array',
            'variant_stocks.*' => 'required|integer|min:0',
        ], [
            'product_id.required' => 'Sản phẩm là bắt buộc.',
            'product_id.exists' => 'Sản phẩm không tồn tại.',
            'default_price.required' => 'Giá mặc định là bắt buộc.',
            'default_price.numeric' => 'Giá mặc định phải là số.',
            'default_price.min' => 'Giá mặc định không được nhỏ hơn 0.',
            'default_stock_quantity.required' => 'Số lượng tồn kho mặc định là bắt buộc.',
            'default_stock_quantity.integer' => 'Số lượng tồn kho mặc định phải là số nguyên.',
            'default_stock_quantity.min' => 'Số lượng tồn kho mặc định không được nhỏ hơn 0.',
            'image_url.image' => 'File tải lên phải là ảnh.',
            'image_url.mimes' => 'Ảnh phải có định dạng jpg, jpeg, png hoặc gif.',
            'image_url.max' => 'Ảnh không được vượt quá 2MB.',
            'is_active.required' => 'Trạng thái biến thể là bắt buộc.',
            'is_active.in' => 'Trạng thái biến thể phải là Hoạt động hoặc Không hoạt động.',
            'attribute_values.required' => 'Vui lòng chọn ít nhất một giá trị thuộc tính.',
            'attribute_values.*.*.exists' => 'Giá trị thuộc tính không hợp lệ.',
            'variant_prices.required' => 'Giá cho các biến thể là bắt buộc.',
            'variant_prices.*.required' => 'Giá cho biến thể là bắt buộc.',
            'variant_prices.*.numeric' => 'Giá cho biến thể phải là số.',
            'variant_prices.*.min' => 'Giá cho biến thể không được nhỏ hơn 0.',
            'variant_stocks.required' => 'Số lượng tồn kho cho các biến thể là bắt buộc.',
            'variant_stocks.*.required' => 'Số lượng tồn kho cho biến thể là bắt buộc.',
            'variant_stocks.*.integer' => 'Số lượng tồn kho cho biến thể phải là số nguyên.',
            'variant_stocks.*.min' => 'Số lượng tồn kho cho biến thể không được nhỏ hơn 0.',
        ]);

        $product = Product::findOrFail($request->product_id);
        $productSku = $product->sku ?? 'PRODUCT';

        $imageUrl = null;
        if ($request->hasFile('image_url')) {
            $imageUrl = $request->file('image_url')->store('product_variants', 'public');
        }

        // Tạo tổ hợp từ attribute_values
        $attributeValueGroups = $request->attribute_values;
        $combinations = $this->generateCombinations($attributeValueGroups);

        $createdVariants = [];
        foreach ($combinations as $index => $attributeValueIds) {
            if (empty($attributeValueIds) || count($attributeValueIds) !== count($attributeValueGroups)) {
                continue;
            }

            // Loại bỏ các ID trùng lặp
            $attributeValueIds = array_unique($attributeValueIds);

            // Lấy giá và tồn kho
            $price = $request->variant_prices[$index] ?? $request->default_price;
            $stock = $request->variant_stocks[$index] ?? $request->default_stock_quantity;

            // Lấy giá trị thuộc tính
            $attributeValues = AttributeValue::whereIn('id', $attributeValueIds)
                ->pluck('value')
                ->map(function ($value) {
                    return Str::slug($value, '-');
                })->toArray();

            // Tạo SKU
            $sku = $productSku . '-' . implode('-', $attributeValues);
            $sku = mb_strtoupper($sku);

            // Kiểm tra SKU đã tồn tại
            if (ProductVariant::where('sku', $sku)->exists()) {
                continue;
            }

            // Tạo biến thể sản phẩm
            $productVariant = ProductVariant::create([
                'product_id' => $request->product_id,
                'sku' => $sku,
                'price' => $price,
                'stock_quantity' => $stock,
                'image_url' => $imageUrl,
                'is_active' => $request->is_active ?? 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Lưu các tùy chọn biến thể
            $existingOptions = ProductVariantOption::where('product_variant_id', $productVariant->id)
                ->pluck('attribute_value_id')
                ->toArray();

            foreach ($attributeValueIds as $attributeValueId) {
                if (!in_array($attributeValueId, $existingOptions)) {
                    ProductVariantOption::create([
                        'product_variant_id' => $productVariant->id,
                        'attribute_value_id' => $attributeValueId,
                    ]);
                }
            }

            // Tạo thông báo khi tạo mới biến thể sản phẩm
            Notification::create([
                'user_id' => Auth::id(),
                'entity_type' => ProductVariant::class,
                'entity_id' => $productVariant->id,
                'title' => 'Tạo mới biến thể sản phẩm',
                'message' => 'Biến thể sản phẩm #' . $productVariant->id . ' đã được tạo mới.',
                'type' => NotificationType::System,
                'priority' => 'low',
                'old_status' => null,
                'new_status' => $productVariant->sku,
                'event_details' => json_encode([
                    'new' => $productVariant->getAttributes(),
                ]),
            ]);

            $createdVariants[] = $productVariant;
        }

        if (empty($createdVariants)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['attribute_values' => 'Tất cả biến thể với thuộc tính đã chọn đã tồn tại.']);
        }

        return redirect()->route('admin.product-variants.index')->with('success', 'Đã tạo thành công ' . count($createdVariants) . ' biến thể sản phẩm.');
    }

    private function generateCombinations($arrays)
    {
        if (empty($arrays)) {
            return [[]];
        }

        $result = [[]];
        foreach ($arrays as $array) {
            $newResult = [];
            foreach ($result as $existing) {
                foreach ($array as $item) {
                    $newResult[] = array_merge($existing, [(string)$item]);
                }
            }
            $result = $newResult;
        }

        // Loại bỏ tổ hợp không hợp lệ
        $validCombinations = array_filter($result, function ($combination) use ($arrays) {
            return count($combination) === count($arrays);
        });

        // Loại bỏ trùng lặp
        $uniqueCombinations = [];
        foreach ($validCombinations as $combination) {
            $key = implode(',', $combination);
            if (!isset($uniqueCombinations[$key])) {
                $uniqueCombinations[$key] = $combination;
            }
        }

        return array_values($uniqueCombinations);
    }

    public function edit($id)
    {
        $productVariant = ProductVariant::with('productVariantOptions.attributeValue.attribute')->findOrFail($id);
        $products = Product::all();
        $selectedProductId = $productVariant->product_id;
        $attributes = Attribute::with('attributeValues')->get();

        return view('admin.product_variants.edit', compact('productVariant', 'products', 'attributes', 'selectedProductId'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'is_active' => 'required|in:0,1',
            'attribute_values' => 'required|array|min:1',
            'attribute_values.*' => 'exists:attribute_values,id',
        ]);

        $productVariant = ProductVariant::findOrFail($id);

        // Xử lý ảnh
        $imageUrl = $productVariant->image_url;
        if ($request->hasFile('image')) {
            if ($imageUrl && Storage::disk('public')->exists($imageUrl)) {
                Storage::disk('public')->delete($imageUrl);
            }
            $imageUrl = $request->file('image')->store('product_variants', 'public');
        }

        // Tạo SKU mới nếu thay đổi thuộc tính hoặc sản phẩm
        $newAttributeValues = $request->attribute_values;
        $shouldRegenerateSku = (
            $productVariant->product_id != $request->product_id ||
            array_diff($productVariant->productVariantOptions->pluck('attribute_value_id')->toArray(), $newAttributeValues) ||
            array_diff($newAttributeValues, $productVariant->productVariantOptions->pluck('attribute_value_id')->toArray())
        );

        if ($shouldRegenerateSku) {
            $product = Product::findOrFail($request->product_id);
            $productSku = $product->sku;
            $attributeValues = \App\Models\AttributeValue::whereIn('id', $newAttributeValues)
                ->pluck('value')
                ->map(function ($value) {
                    return \Illuminate\Support\Str::slug($value, '-');
                })
                ->toArray();
            $sku = mb_strtoupper($productSku . '-' . implode('-', $attributeValues));
        } else {
            $sku = $productVariant->sku;
        }

        // Cập nhật biến thể
        $productVariant->update([
            'product_id' => $request->product_id,
            'sku' => $sku,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'image_url' => $imageUrl,
            'is_active' => $request->is_active,
        ]);

        // Xóa hết các option cũ và thêm lại mới
        \App\Models\ProductVariantOption::where('product_variant_id', $productVariant->id)->delete();
        foreach ($newAttributeValues as $attributeValueId) {
            \App\Models\ProductVariantOption::create([
                'product_variant_id' => $productVariant->id,
                'attribute_value_id' => $attributeValueId,
            ]);
        }

        // Tạo thông báo khi cập nhật biến thể sản phẩm
        Notification::create([
            'user_id' => Auth::id(),
            'entity_type' => ProductVariant::class,
            'entity_id' => $productVariant->id,
            'title' => 'Cập nhật biến thể sản phẩm',
            'message' => 'Biến thể sản phẩm #' . $productVariant->id . ' đã được cập nhật.',
            'type' => NotificationType::System,
            'priority' => 'low',
            'old_status' => $oldData['sku'] ?? null,
            'new_status' => $productVariant->sku,
            'event_details' => json_encode([
                'old' => $oldData,
                'new' => $productVariant->getAttributes(),
            ]),
        ]);

        return redirect()->route('admin.product-variants.index')->with('success', 'Biến thể sản phẩm đã được cập nhật thành công.');
    }

    public function show($id)
    {
        $productVariant = ProductVariant::with([
            'product.category',
            'productVariantOptions.attributeValue.attribute'
        ])->findOrFail($id);
        
        return view('admin.product_variants.show', compact('productVariant'));
    }
}
