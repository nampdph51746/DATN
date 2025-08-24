<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ComboPackageItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ComboController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,staff']);
        $this->middleware('can:view combo')->only('index');
        $this->middleware('can:create combo')->only(['create', 'store']);
        $this->middleware('can:edit combo')->only(['edit', 'update']);
        $this->middleware('can:delete combo')->only('destroy');
    }
    public function index(Request $request)
    {
        $products = Product::with('productVariants')->get();
        $query = \App\Models\Combo::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->filled('product_id')) {
            $query->whereHas('comboProductVariant', function ($q) use ($request) {
                $q->where('product_id', $request->input('product_id'));
            });
        }

        if ($request->filled('min_quantity')) {
            $query->whereHas('comboPackageItems', function ($q) use ($request) {
                $q->havingRaw('SUM(quantity) >= ?', [$request->input('min_quantity')]);
            });
        }

        $combos = $query->with(['comboProductVariant.product', 'comboPackageItems.itemProductVariant.product'])->paginate(10);

        return view('admin.combos.index', compact('products', 'combos'));
    }

    public function create(Request $request)
    {
        $comboProductVariantId = $request->input('combo_product_variant_id');
        $productId = $request->input('product_id');
        $products = Product::with('productVariants')->where('is_active', true)->get();

        $selectedProduct = null;
        $selectedVariant = null;
        if ($comboProductVariantId) {
            $selectedVariant = \App\Models\ProductVariant::with('product')->find($comboProductVariantId);
            if ($selectedVariant) {
                $selectedProduct = $selectedVariant->product;
                $productId = $selectedProduct ? $selectedProduct->getKey() : $productId;
            }
        } elseif ($productId) {
            $selectedProduct = \App\Models\Product::find($productId);
        }

        return view('admin.combos.create', compact('products', 'selectedProduct', 'selectedVariant', 'comboProductVariantId', 'productId'));
    }

    public function store(Request $request)
    {
        \Log::debug('ComboController@store - Request data:', $request->all());
        \Log::debug('ComboController@store - combo_product_variant_id:', ['combo_product_variant_id' => $request->input('combo_product_variant_id')]);
        \Log::debug('ComboController@store - items:', ['items' => $request->input('items')]);
        \Log::debug('ComboController@store - name:', ['name' => $request->input('name')]);
        \Log::debug('ComboController@store - price:', ['price' => $request->input('price')]);
        \Log::debug('ComboController@store - stock_quantity:', ['stock_quantity' => $request->input('stock_quantity')]);
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'combo_product_variant_id' => 'exists:product_variants,id',
                'price' => 'required|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
                'items' => 'required|array|min:1',
                'items.*.item_product_variant_id' => 'exists:product_variants,id',
                'items.*.quantity' => 'required|integer|min:1',
            ], [
                'name.required' => 'Vui lòng nhập tên combo.',
                'name.max' => 'Tên combo không được vượt quá 255 ký tự.',
                'price.required' => 'Vui lòng nhập giá combo.',
                'price.numeric' => 'Giá combo phải là số.',
                'price.min' => 'Giá combo phải >= 0.',
                'stock_quantity.required' => 'Vui lòng nhập số lượng tồn kho.',
                'stock_quantity.integer' => 'Số lượng tồn kho phải là số nguyên.',
                'stock_quantity.min' => 'Số lượng tồn kho phải >= 0.',
                'items.required' => 'Vui lòng thêm ít nhất một mục vào combo.',
                'items.min' => 'Vui lòng thêm ít nhất một mục vào combo.',
                'items.*.item_product_variant_id.exists' => 'Biến thể không tồn tại.',
                'items.*.quantity.required' => 'Vui lòng nhập số lượng.',
                'items.*.quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 1.',
            ]);
            \Log::debug('ComboController@store - Dữ liệu đã validate:', $validated);
        } catch (\Exception $e) {
            \Log::error('ComboController@store - Validation error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }

        try {
            DB::beginTransaction();

            $comboProductVariantId = $request->input('combo_product_variant_id');
            $items = $request->input('items');
            $comboName = $request->input('name');
            $comboPrice = $request->input('price');
            $comboStock = $request->input('stock_quantity');

            $comboVariant = ProductVariant::find($comboProductVariantId);

            // Thêm kiểm tra tồn tại biến thể đại diện
            if (!$comboVariant) {
                Log::error('ComboController@store - Không tìm thấy comboVariant');
                return back()->withErrors(['combo_product_variant_id' => 'Vui lòng chọn biến thể đại diện hợp lệ.'])->withInput();
            }

            // Tính giá combo bằng tổng giá của toàn bộ sản phẩm biến thể trong combo rồi giảm đi 10%
            $variantIds = $request->combo_product_variant_ids ?? [];
            $totalPrice = \App\Models\ProductVariant::whereIn('id', $variantIds)->sum('price');
            $comboPrice = round($totalPrice * 0.9);

            // Tạo combo mới
            $combo = \App\Models\Combo::create([
                'name' => $comboName ?? $comboVariant->sku,
                'combo_product_variant_id' => $comboVariant->id, // Sử dụng $comboVariant->id đã kiểm tra tồn tại
                'price' => $comboPrice,
                'stock_quantity' => $comboStock ?? $comboVariant->stock_quantity,
            ]);
            Log::info('ComboController@store - Combo created', ['combo_id' => $combo->getKey()]);


            // Kiểm tra combo đã tồn tại chưa
            // Chỉ báo lỗi nếu combo mới thực sự trùng với combo đã tồn tại (cùng biến thể đại diện và cùng danh sách mục, số lượng)
            $normalized = collect($items)->map(function($item) {
                return [
                    'variant_id' => (string)($item['item_product_variant_id'] ?? ''),
                    'quantity' => (int)($item['quantity'] ?? 1)
                ];
            })->sortBy('variant_id')->values()->toArray();
            $combos = \App\Models\Combo::where('combo_product_variant_id', $comboProductVariantId)->get();
            $isDuplicate = false;
            foreach ($combos as $comboCheck) {
                $dbItems = $comboCheck->comboPackageItems()->get()->map(function($item) {
                    return [
                        'variant_id' => (string)$item->item_product_variant_id,
                        'quantity' => (int)$item->quantity
                    ];
                })->sortBy('variant_id')->values()->toArray();
                if (count($dbItems) === count($normalized) && $dbItems == $normalized) {
                    $isDuplicate = true;
                    break;
                }
            }
            \Log::debug('ComboController@store - Kết quả kiểm tra duplicate:', ['isDuplicate' => $isDuplicate]);
            if ($isDuplicate) {
                DB::rollBack();
                \Log::debug('ComboController@store - Lỗi: combo duplicate');
                return back()->withErrors(['error' => 'Combo này đã tồn tại. Vui lòng chọn sản phẩm hoặc biến thể khác.'])->withInput();
            }

            // Luôn thêm biến thể đại diện vào ComboPackageItem
            \App\Models\ComboPackageItem::create([
                'combo_id' => $combo->getKey(),
                'combo_product_variant_id' => $comboVariant->id,
                'item_product_variant_id' => $comboVariant->id,
                'quantity' => 1,
            ]);

            // Lưu các mục còn lại vào combo
            foreach ($items as $index => $item) {
                if (!isset($item['item_product_variant_id'])) {
                    \Log::warning('ComboController@store - item không có item_product_variant_id', ['item' => $item]);
                    continue;
                }
                // Bỏ qua nếu là biến thể đại diện (tránh trùng lặp)
                if ($item['item_product_variant_id'] == $comboProductVariantId) continue;
                $itemVariant = ProductVariant::find($item['item_product_variant_id']);
                if (!$itemVariant) continue;
                \App\Models\ComboPackageItem::create([
                    'combo_id' => $combo->getKey(),
                    'combo_product_variant_id' => $comboVariant->id,
                    'item_product_variant_id' => $item['item_product_variant_id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            DB::commit();
            \Log::info('ComboController@store - Combo created successfully for combo ID', ['id' => $combo->getKey()]);
            return redirect()->route('admin.combos.index')->with('success', 'Combo đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ComboController@store - Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return back()->withErrors(['error' => 'Đã xảy ra lỗi khi tạo combo: ' . $e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $combo = \App\Models\Combo::with([
            'comboProductVariant.product',
            'comboPackageItems.itemProductVariant.product'
        ])->findOrFail($id);

        if (!$combo->comboPackageItems()->exists()) {
            abort(404, 'Combo không tồn tại.');
        }

        return view('admin.combos.show', compact('combo'));
    }

    public function edit($id)
    {
        $combo = \App\Models\Combo::with([
            'comboProductVariant.product',
            'comboPackageItems.itemProductVariant.product'
        ])->findOrFail($id);

        if (!$combo->comboPackageItems()->exists()) {
            abort(404, 'Combo không tồn tại.');
        }

        $products = Product::with('productVariants')->where('is_active', true)->get();
        return view('admin.combos.edit', compact('combo', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'combo_product_variant_id' => 'required|exists:product_variants,id',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'items.*.item_product_variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $combo = \App\Models\Combo::findOrFail($id);

            $combo->update([
                'name' => $request->input('name'),
                'combo_product_variant_id' => $request->input('combo_product_variant_id'),
                'price' => $request->input('price'),
                'stock_quantity' => $request->input('stock_quantity'),
            ]);

            // Xóa các mục cũ
            \App\Models\ComboPackageItem::where('combo_id', $combo->getKey())->delete();

            $items = $request->input('items');
            foreach ($items as $item) {
                \App\Models\ComboPackageItem::create([
                    'combo_id' => $combo->getKey(),
                    'combo_product_variant_id' => $combo->combo_product_variant_id,
                    'item_product_variant_id' => $item['item_product_variant_id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            // Tính giá combo bằng tổng giá của toàn bộ sản phẩm biến thể trong combo rồi giảm đi 10%
            $variantIds = $request->combo_product_variant_ids ?? [];
            $totalPrice = \App\Models\ProductVariant::whereIn('id', $variantIds)->sum('price');
            $comboPrice = round($totalPrice * 0.9);

            $combo = Combo::findOrFail($id);
            $combo->update([
                'price' => $comboPrice,
            ]);

            DB::commit();
            return redirect()->route('admin.combos.index')->with('success', 'Combo đã được cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Đã xảy ra lỗi khi cập nhật combo: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $combo = \App\Models\Combo::findOrFail($id);

            \App\Models\ComboPackageItem::where('combo_id', $combo->getKey())->delete();
            $combo->delete();

            DB::commit();
            return redirect()->route('admin.combos.index')->with('success', 'Combo đã được xóa thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Đã xảy ra lỗi khi xóa combo: ' . $e->getMessage()]);
        }
    }

    public function checkDuplicate(Request $request)
    {
        $comboProductVariantId = $request->input('product_variant_id');
        $items = $request->input('items');
        if (!$comboProductVariantId || !is_array($items) || count($items) < 2) {
            // Nếu chỉ có sản phẩm đại diện thì không hợp lệ
            return response()->json(['duplicate' => false]);
        }

        // Chuẩn hóa danh sách item: chỉ lấy variant_id và quantity
        $normalized = collect($items)->map(function($item) {
            return [
                'variant_id' => (string)($item['variantId'] ?? $item['variant_id'] ?? $item['item_product_variant_id'] ?? ''),
                'quantity' => (int)($item['quantity'] ?? 1)
            ];
        })->sortBy('variant_id')->values()->toArray();

        // Tìm các combo cùng combo_product_variant_id
        $combos = \App\Models\Combo::where('combo_product_variant_id', $comboProductVariantId)->get();
        foreach ($combos as $combo) {
            $dbItems = $combo->comboPackageItems()->get()->map(function($item) {
                return [
                    'variant_id' => (string)$item->item_product_variant_id,
                    'quantity' => (int)$item->quantity
                ];
            })->sortBy('variant_id')->values()->toArray();
            if ($dbItems == $normalized) {
                return response()->json(['duplicate' => true]);
            }
        }
        return response()->json(['duplicate' => false]);
    }
}