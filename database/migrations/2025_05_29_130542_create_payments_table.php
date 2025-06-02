<?php

use App\Enums\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id()->comment('ID thanh toán');
            $table->foreignId('booking_id')->constrained()->comment('ID đơn đặt vé');
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->comment('ID phương thức thanh toán');
            $table->decimal('amount', 12, 2)->comment('Số tiền thanh toán');
            $table->string('transaction_id_gateway', 255)->unique()->nullable()->comment('ID giao dịch từ cổng thanh toán');
            $table->enum('status', array_column(PaymentStatus::cases(), 'value'))->comment('Trạng thái thanh toán');
            $table->text('payment_details')->nullable()->comment('Chi tiết thanh toán (JSON)');
            $table->timestamp('paid_at')->nullable()->comment('Thời gian thanh toán');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};