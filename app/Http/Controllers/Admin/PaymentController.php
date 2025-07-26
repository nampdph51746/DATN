<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['booking.user', 'paymentMethod']);

        if ($search = $request->input('search')) {
            $query->where('id', $search)
                ->orWhereHas('booking', function ($q) use ($search) {
                    $q->where('booking_code', 'like', '%' . $search . '%')
                        ->orWhere('user_id', $search);
                });
        }

        if ($method = $request->input('method')) {
            $query->where('payment_method_id', $method);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $payments = $query->latest()->paginate(10);

        // Đếm số lượng giao dịch theo trạng thái
        $countCompleted = Payment::where('status', 'completed')->count();
        $countPending = Payment::where('status', 'pending')->count();
        $countFailed = Payment::where('status', 'failed')->count();

        // Tổng số tiền tất cả giao dịch
        $totalAmount = Payment::sum('amount');

        return view('admin.payments.index', compact('payments', 'countCompleted', 'countPending', 'countFailed', 'totalAmount'));
    }

    public function show($id)
    {
        $payment = Payment::with(['booking.user', 'paymentMethod'])->findOrFail($id);
        return view('admin.payments.show', compact('payment'));
    }
}