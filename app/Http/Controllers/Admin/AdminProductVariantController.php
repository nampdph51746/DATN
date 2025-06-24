<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductVariant;
use App\Models\ProductVariantOption;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProductVariantController extends Controller
{
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

        return view('admin.product_variants.create', compact('products', 'selectedProductId', 'attributes'));
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
            'image_url' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'is_active' => 'required|in:0,1',
            'attribute_values' => 'required|array|min:1',
            'attribute_values.*' => 'exists:attribute_values,id',
        ], [
            'product_id.required' => 'Sản phẩm là bắt buộc.',
            'product_id.exists' => 'Sản phẩm không tồn tại.',
            'price.required' => 'Giá bán là bắt buộc.',
            'price.numeric' => 'Giá bán phải là số.',
            'price.min' => 'Giá bán không được nhỏ hơn 0.',
            'stock_quantity.required' => 'Số lượng tồn kho là bắt buộc.',
            'stock_quantity.integer' => 'Số lượng tồn kho phải là số nguyên.',
            'stock_quantity.min' => 'Số lượng tồn kho không được nhỏ hơn 0.',
            'image_url.image' => 'File tải lên phải là ảnh.',
            'image_url.mimes' => 'Ảnh phải có định dạng jpg, jpeg, png hoặc gif.',
            'image_url.max' => 'Ảnh không được vượt quá 2MB.',
            'is_active.required' => 'Trạng thái biến thể là bắt buộc.',
            'is_active.in' => 'Trạng thái biến thể phải là Hoạt động hoặc Không hoạt động.',
            'attribute_values.required' => 'Vui lòng chọn ít nhất một giá trị thuộc tính.',
            'attribute_values.*.exists' => 'Giá trị thuộc tính không hợp lệ.',
        ]);

        $productVariant = ProductVariant::findOrFail($id);

        // Lấy danh sách attribute_value_id hiện tại
        $existingOptions = ProductVariantOption::where('product_variant_id', $productVariant->id)
            ->pluck('attribute_value_id')->toArray();

        $newAttributeValues = $request->attribute_values;

        // So sánh thuộc tính và giá trị thuộc tính
        $shouldRegenerateSku = (
            $productVariant->product_id != $request->product_id ||
            count($existingOptions) !== count($newAttributeValues) ||
            array_diff($existingOptions, $newAttributeValues) ||
            array_diff($newAttributeValues, $existingOptions)
        );

        if ($shouldRegenerateSku) {
            // Lấy sản phẩm để lấy SKU
            $product = Product::findOrFail($request->product_id);
            $productSku = $product->sku;

            // Lấy các giá trị thuộc tính được chọn
            $attributeValues = AttributeValue::whereIn('id', $newAttributeValues)
                ->pluck('value')
                ->map(function ($value) {
                    return Str::slug($value, '-');
                })
                ->toArray();

            // Tạo SKU: product_sku + các giá trị thuộc tính
            $sku = $productSku . '-' . implode('-', $attributeValues);
            $sku = mb_strtoupper($sku);

        
        } else {
            $sku = $productVariant->sku;
        }

        $imageUrl = $productVariant->image_url;
        if ($request->hasFile('image')) {
            if ($productVariant->image_url && Storage::disk('public')->exists($productVariant->image_url)) {
                Storage::disk('public')->delete($productVariant->image_url);
            }
            $imageUrl = $request->file('image')->store('product_variants', 'public');
        }

        $productVariant->update([
            'product_id' => $request->product_id,
            'sku' => $sku,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'image_url' => $imageUrl,
            'is_active' => $request->is_active,
            'updated_at' => now(),
        ]);

        // Xóa các bản ghi không còn trong request
        $optionsToDelete = array_diff($existingOptions, $newAttributeValues);
        if ($optionsToDelete) {
            ProductVariantOption::where('product_variant_id', $productVariant->id)
                ->whereIn('attribute_value_id', $optionsToDelete)
                ->delete();
        }

        // Thêm các bản ghi mới
        $optionsToAdd = array_diff($newAttributeValues, $existingOptions);
        foreach ($optionsToAdd as $attributeValueId) {
            ProductVariantOption::create([
                'product_variant_id' => $productVariant->id,
                'attribute_value_id' => $attributeValueId,
            ]);
        }

        return redirect()->route('admin.product-variants.index')->with('success', 'Biến thể sản phẩm đã được cập nhật thành công.');
    }
}
