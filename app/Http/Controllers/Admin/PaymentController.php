<?php
namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Hiển thị danh sách payment kèm tìm kiếm và lọc
   public function index(Request $request)
{
    $query = Payment::with(['booking.user', 'paymentMethod']); // eager load quan hệ
    // var_dump($query);
    // die;

    // Tìm kiếm theo ID thanh toán hoặc booking code hoặc user_id (qua quan hệ)
    if ($search = $request->input('search')) {
        $query->where('id', $search)
              ->orWhereHas('booking', function ($q) use ($search) {
                  $q->where('booking_code', 'like', '%' . $search . '%')
                    ->orWhere('user_id', $search);
              });
            //   var_dump($request->input('search'));
            //   die;
    }

    // Lọc theo phương thức thanh toán
    if ($method = $request->input('method')) {
        $query->where('payment_method_id', $method);
    }

    // Lọc theo trạng thái (enum) => cần so sánh với giá trị `value`
    if ($status = $request->input('status')) {
        $query->where('status', $status); // Nếu $status là string như 'paid', 'unpaid'
    }

    $payments = $query->latest()->paginate(10);

    $paymentStats = [
        'all' => Payment::count(),
    'pending' => Payment::where('status', PaymentStatus::Pending)->count(),
    'completed' => Payment::where('status', PaymentStatus::Completed)->count(),
    'failed' => Payment::where('status', PaymentStatus::Failed)->count(),
];

    return view('admin.payments.index', compact('payments', 'paymentStats'));
}

    // Xem chi tiết 1 payment
public function show(Payment $payment)
{
    // Nạp sẵn các quan hệ để tránh lỗi null
    $payment->load(['booking.user', 'paymentMethod']);

    return view('admin.payments.show', compact('payment'));
}

public function editStatus(Payment $payment)
{
    $status = [
        'pending' => 'Chờ xử lý',
        'completed' => 'Đã hoàn thành',
        'failed' => 'Thanh toán thất bại',
    ];

    return view('admin.payments.edit-status', compact('payment', 'status'));
}


    // Cập nhật trạng thái
    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|string|in:pending,paid,failed,refunded',
        ]);

        $payment->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Trạng thái thanh toán đã được cập nhật.');
    }

}
