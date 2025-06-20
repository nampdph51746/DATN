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
    public function index(Request $request)
    {
        $products = Product::with('productVariants')->get();
        $query = ProductVariant::whereHas('comboPackageItems');

        if ($request->filled('search')) {
            $query->where('sku', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('min_quantity')) {
            $query->whereHas('comboPackageItems', function ($q) use ($request) {
                $q->havingRaw('SUM(quantity) >= ?', [$request->min_quantity]);
            });
        }

        $combos = $query->with(['product', 'comboPackageItems.itemProductVariant.product'])->paginate(10);

        return view('admin.combos.index', compact('products', 'combos'));
    }

    public function create()
    {
        $products = Product::with('productVariants')->where('is_active', true)->get();
        return view('admin.combos.create', compact('products'));
    }

    public function store(Request $request)
    {
        Log::info('Request data in store:', $request->all());

        $request->validate([
            'combo_product_variant_id' => 'required|exists:product_variants,id',
            'items' => 'required|array|min:1',
            'items.*.item_product_variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
        ], [
            'combo_product_variant_id.required' => 'Vui lòng chọn biến thể đại diện cho combo.',
            'items.required' => 'Vui lòng thêm ít nhất một mục vào combo.',
            'items.min' => 'Vui lòng thêm ít nhất một mục vào combo.',
            'items.*.item_product_variant_id.required' => 'Vui lòng chọn biến thể cho mỗi mục.',
            'items.*.quantity.required' => 'Vui lòng nhập số lượng.',
            'items.*.quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 1.',
            'items.*.item_product_variant_id.exists' => 'Biến thể không tồn tại.',
        ]);

        try {
            DB::beginTransaction();

            $comboVariant = ProductVariant::findOrFail($request->combo_product_variant_id);
            Log::info('Combo variant found:', ['id' => $comboVariant->id, 'sku' => $comboVariant->sku]);

            if ($comboVariant->comboPackageItems()->exists()) {
                return back()->withErrors(['combo_product_variant_id' => 'Biến thể này đã được sử dụng cho combo khác.'])->withInput();
            }

            if (!$comboVariant->is_active) {
                return back()->withErrors(['combo_product_variant_id' => 'Biến thể không ở trạng thái hoạt động.'])->withInput();
            }

            foreach ($request->items as $index => $item) {
                Log::info('Processing item:', ['index' => $index, 'data' => $item]);
                $itemVariant = ProductVariant::findOrFail($item['item_product_variant_id']);
                if (!$itemVariant->is_active) {
                    return back()->withErrors(['items.' . $index . '.item_product_variant_id' => 'Biến thể mục không ở trạng thái hoạt động.'])->withInput();
                }

                $comboItem = ComboPackageItem::create([
                    'combo_product_variant_id' => $comboVariant->id,
                    'item_product_variant_id' => $item['item_product_variant_id'],
                    'quantity' => $item['quantity'],
                ]);
                Log::info('Item created:', ['combo_id' => $comboVariant->id, 'item_id' => $itemVariant->id, 'quantity' => $item['quantity'], 'item_record' => $comboItem->toArray()]);
            }

            DB::commit();
            Log::info('Combo created successfully for variant ID:', ['id' => $comboVariant->id]); // Sửa lại thành mảng
            return redirect()->route('admin.combos.index')->with('success', 'Combo đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Exception in store:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return back()->withErrors(['error' => 'Đã xảy ra lỗi khi tạo combo: ' . $e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $combo = ProductVariant::with([
            'product',
            'comboPackageItems.itemProductVariant.product'
        ])->findOrFail($id);

        if (!$combo->comboPackageItems()->exists()) {
            abort(404, 'Combo không tồn tại.');
        }

        return view('admin.combos.show', compact('combo'));
    }

    public function edit($id)
    {
        $combo = ProductVariant::with([
            'product',
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
            'combo_product_variant_id' => 'required|exists:product_variants,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.item_product_variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $comboVariant = ProductVariant::where('is_active', true)->findOrFail($id);

            ComboPackageItem::where('combo_product_variant_id', $comboVariant->id)->delete();

            foreach ($request->items as $item) {
                ComboPackageItem::create([
                    'combo_product_variant_id' => $comboVariant->id,
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

            $comboVariant = ProductVariant::findOrFail($id);

            ComboPackageItem::where('combo_product_variant_id', $comboVariant->id)->delete();

            DB::commit();
            return redirect()->route('admin.combos.index')->with('success', 'Combo đã được xóa thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Đã xảy ra lỗi khi xóa combo: ' . $e->getMessage()]);
        }
    }
}