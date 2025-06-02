<?php

use App\Enums\BookingStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id()->comment('ID đơn đặt vé');
            $table->foreignId('user_id')->constrained()->comment('ID người dùng');
            $table->string('booking_code', 20)->unique()->comment('Mã đặt vé (duy nhất)');
            $table->decimal('total_amount_before_discount', 12, 2)->comment('Tổng tiền trước giảm giá');
            $table->decimal('discount_amount', 12, 2)->nullable()->comment('Số tiền giảm giá');
            $table->decimal('final_amount', 12, 2)->comment('Tổng tiền cuối cùng');
            $table->foreignId('promotion_id')->nullable()->constrained('promotions')->comment('ID khuyến mãi áp dụng');
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->comment('ID phương thức thanh toán');
            $table->enum('status', array_column(BookingStatus::cases(), 'value'))->comment('Trạng thái đơn đặt vé');
            $table->text('notes')->nullable()->comment('Ghi chú đơn hàng');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};