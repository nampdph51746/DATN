<?php

namespace App\Http\Controllers\Admin;

use App\Models\Combo;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\ComboPackageItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

use App\Models\Notification;
use App\Enums\NotificationType;
use Illuminate\Support\Facades\Auth;

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
        $query = Combo::query();

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
            $selectedVariant = ProductVariant::with('product')->find($comboProductVariantId);
            if ($selectedVariant) {
                $selectedProduct = $selectedVariant->product;
                $productId = $selectedProduct ? $selectedProduct->getKey() : $productId;
            }
        } elseif ($productId) {
            $selectedProduct = Product::find($productId);
        }

        return view('admin.combos.create', compact('products', 'selectedProduct', 'selectedVariant', 'comboProductVariantId', 'productId'));
    }

    public function store(Request $request)
    {
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
        } catch (\Exception $e) {
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

            if (!$comboVariant) {
                throw new \Exception('Không tìm thấy biến thể đại diện cho combo');
            }

            if (!$comboVariant->is_active) {
                return back()->withErrors(['combo_product_variant_id' => 'Biến thể không ở trạng thái hoạt động.'])->withInput();
            }

            // Tạo combo mới
            $combo = Combo::create([
                'name' => $comboName ?? $comboVariant->sku,
                'combo_product_variant_id' => $comboProductVariantId,
                'price' => $comboPrice ?? $comboVariant->price,
                'stock_quantity' => $comboStock ?? $comboVariant->stock_quantity,
            ]);

            // Tạo thông báo khi thêm mới combo
            Notification::create([
                'user_id' => Auth::id(),
                'entity_type' => Combo::class,
                'entity_id' => $combo->id,
                'title' => 'Thêm mới combo',
                'message' => 'Combo "' . $combo->name . '" đã được thêm mới.',
                'type' => NotificationType::System,
                'priority' => 'low',
                'old_status' => null,
                'new_status' => json_encode($combo->getAttributes()),
                'event_details' => json_encode(['action' => 'create', 'combo_id' => $combo->id]),
            ]);


            // Kiểm tra combo đã tồn tại chưa
            // Chỉ báo lỗi nếu combo mới thực sự trùng với combo đã tồn tại (cùng biến thể đại diện và cùng danh sách mục, số lượng)
            $normalized = collect($items)->map(function($item) {
                return [
                    'variant_id' => (string)($item['item_product_variant_id'] ?? ''),
                    'quantity' => (int)($item['quantity'] ?? 1)
                ];
            })->sortBy('variant_id')->values()->toArray();
            $combos = Combo::where('combo_product_variant_id', $comboProductVariantId)->get();
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
            if ($isDuplicate) {
                DB::rollBack();
                return back()->withErrors(['error' => 'Combo này đã tồn tại. Vui lòng chọn sản phẩm hoặc biến thể khác.'])->withInput();
            }

            // Luôn thêm biến thể đại diện vào ComboPackageItem
            ComboPackageItem::create([
                'combo_id' => $combo->getKey(),
                'combo_product_variant_id' => $comboProductVariantId,
                'item_product_variant_id' => $comboProductVariantId,
                'quantity' => 1,
            ]);

            // Lưu các mục còn lại vào combo
            foreach ($items as $index => $item) {
                if (!isset($item['item_product_variant_id'])) {
                    continue;
                }
                // Bỏ qua nếu là biến thể đại diện (tránh trùng lặp)
                if ($item['item_product_variant_id'] == $comboProductVariantId) continue;
                $itemVariant = ProductVariant::find($item['item_product_variant_id']);
                if (!$itemVariant) continue;
                ComboPackageItem::create([
                    'combo_id' => $combo->getKey(),
                    'combo_product_variant_id' => $comboProductVariantId,
                    'item_product_variant_id' => $item['item_product_variant_id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            DB::commit();
            return redirect()->route('admin.combos.index')->with('success', 'Combo đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Đã xảy ra lỗi khi tạo combo: ' . $e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $combo = Combo::with([
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
        $combo = Combo::with([
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

            $combo = Combo::findOrFail($id);
            $oldData = $combo->getOriginal();

            $combo->update([
                'name' => $request->input('name'),
                'combo_product_variant_id' => $request->input('combo_product_variant_id'),
                'price' => $request->input('price'),
                'stock_quantity' => $request->input('stock_quantity'),
            ]);

            // Tạo thông báo khi cập nhật combo
            Notification::create([
                'user_id' => Auth::id(),
                'entity_type' => Combo::class,
                'entity_id' => $combo->id,
                'title' => 'Cập nhật combo',
                'message' => 'Combo "' . $combo->name . '" đã được cập nhật.',
                'type' => NotificationType::System,
                'priority' => 'low',
                'old_status' => json_encode($oldData),
                'new_status' => json_encode($combo->getAttributes()),
                'event_details' => json_encode(['action' => 'update', 'combo_id' => $combo->id]),
            ]);

            // Xóa các mục cũ
            ComboPackageItem::where('combo_id', $combo->getKey())->delete();

            $items = $request->input('items');
            foreach ($items as $item) {
                ComboPackageItem::create([
                    'combo_id' => $combo->getKey(),
                    'combo_product_variant_id' => $combo->combo_product_variant_id,
                    'item_product_variant_id' => $item['item_product_variant_id'],
                    'quantity' => $item['quantity'],
                ]);
            }

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

            $combo = Combo::findOrFail($id);
            $oldData = $combo->getOriginal();

            ComboPackageItem::where('combo_id', $combo->getKey())->delete();
            $combo->delete();

            // Tạo thông báo khi xóa combo
            Notification::create([
                'user_id' => Auth::id(),
                'entity_type' => Combo::class,
                'entity_id' => $combo->id,
                'title' => 'Xóa combo',
                'message' => 'Combo "' . $combo->name . '" đã bị xóa.',
                'type' => NotificationType::System,
                'priority' => 'low',
                'old_status' => json_encode($oldData),
                'new_status' => null,
                'event_details' => json_encode(['action' => 'delete', 'combo_id' => $combo->id]),
            ]);

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
        $combos = Combo::where('combo_product_variant_id', $comboProductVariantId)->get();
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