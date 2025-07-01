<?php

namespace App\Http\Controllers\Client;

use App\Models\Product;
use App\Models\BookingItem;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ClientProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = ProductCategory::whereHas('products', function ($query) {
            $query->where('is_active', 1);
        })->get();

        // Lấy sản phẩm phân trang theo danh mục
        foreach ($categories as $category) {
            $category->products = Product::where('category_id', $category->id)
                ->where('is_active', 1)
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'category_' . $category->id . '_page');
        }

        return view('client.ticket_booking', compact('categories'));
    }

    public function show($id)
    {
        $product = Product::with(['productVariants.productVariantOptions.attributeValue.attribute'])
            ->where('is_active', 1)
            ->findOrFail($id);

        return response()->json($product);
    }

    public function addToBooking(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'booking_id' => 'required|integer|exists:bookings,id',
                'product_variant_id' => 'required|integer|exists:product_variants,id',
                'quantity' => 'required|integer|min:1'
            ]);

            // Lấy thông tin biến thể sản phẩm
            $variant = ProductVariant::where('is_active', 1)
                ->where('id', $request->product_variant_id)
                ->firstOrFail();

            // Kiểm tra số lượng tồn kho
            if ($variant->stock_quantity < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng tồn kho không đủ. Còn lại: ' . $variant->stock_quantity
                ], 400);
            }

            // Sử dụng transaction để đảm bảo tính toàn vẹn dữ liệu
            return DB::transaction(function () use ($request, $variant) {
                // Tạo bản ghi trong booking_items
                $bookingItem = BookingItem::create([
                    'booking_id' => $request->booking_id,
                    'product_variant_id' => $request->product_variant_id,
                    'quantity' => $request->quantity,
                    'price_at_purchase' => $variant->price
                ]);

                // Cập nhật số lượng tồn kho
                $variant->stock_quantity -= $request->quantity;
                $variant->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Thêm sản phẩm vào đơn đặt vé thành công',
                    'booking_item' => [
                        'id' => $bookingItem->id,
                        'booking_id' => $bookingItem->booking_id,
                        'product_variant_id' => $bookingItem->product_variant_id,
                        'quantity' => $bookingItem->quantity,
                        'price_at_purchase' => $bookingItem->price_at_purchase
                    ]
                ], 201);
            });

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi thêm sản phẩm vào đơn đặt vé',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
