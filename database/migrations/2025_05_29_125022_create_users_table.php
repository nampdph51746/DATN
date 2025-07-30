<?php

use App\Enums\UserStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->comment('ID người dùng');
            $table->foreignId('role_id')->constrained()->comment('ID vai trò của người dùng');
            $table->string('name', 255)->comment('Họ và tên');
            $table->string('email', 255)->unique()->comment('Email người dùng (duy nhất)');
            $table->string('password', 255)->comment('Mật khẩu đã mã hóa');
            $table->string('phone_number', 20)->unique()->nullable()->comment('Số điện thoại (duy nhất)');
            $table->text('address')->nullable()->comment('Địa chỉ');
            $table->string('avatar_url', 255)->nullable()->comment('URL ảnh đại diện');
            $table->date('date_of_birth')->nullable()->comment('Ngày sinh');
            $table->enum('status', array_column(UserStatus::cases(), 'value'))->default(UserStatus::Active->value)->comment('Trạng thái tài khoản');
            $table->timestamp('email_verified_at')->nullable()->comment('Thời gian xác thực email');
            $table->timestamp('last_login_at')->nullable()->comment('Thời gian đăng nhập cuối');
            $table->foreignId('customer_rank_id')->nullable()->constrained('customer_ranks')->comment('ID xếp hạng khách hàng');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};