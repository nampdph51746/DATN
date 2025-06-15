<?php

use App\Enums\PromotionDiscountType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id()->comment('ID khuyến mãi');
            $table->string('name', 255)->comment('Tên khuyến mãi');
            $table->string('code', 50)->unique()->nullable()->comment('Mã khuyến mãi (duy nhất)');
            $table->text('description')->nullable()->comment('Mô tả khuyến mãi');
            $table->enum('discount_type', array_column(PromotionDiscountType::cases(), 'value'))->comment('Loại giảm giá');
            $table->decimal('discount_value', 10, 2)->comment('Giá trị giảm giá');
            $table->decimal('max_discount_amount', 10, 2)->nullable()->comment('Giá trị giảm tối đa');
            $table->decimal('min_booking_value', 10, 2)->nullable()->comment('Giá trị đơn hàng tối thiểu');
            $table->timestamp('start_date')->comment('Ngày bắt đầu');
            $table->timestamp('end_date')->comment('Ngày kết thúc');
            $table->integer('quantity')->nullable()->comment('Số lượng mã có thể sử dụng');
            $table->integer('usage_limit_per_user')->nullable()->comment('Giới hạn sử dụng mỗi người dùng');
            $table->string('applies_to', 50)->nullable()->comment('Áp dụng cho (movies, tickets, products)');
            $table->string('status', 20)->nullable()->comment('Trạng thái khuyến mãi (active, inactive)');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};