<?php

use App\Enums\MovieStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id()->comment('ID phim');
            $table->string('name', 255)->comment('Tên phim');
            $table->string('director', 255)->nullable()->comment('Đạo diễn');
            $table->text('actors')->nullable()->comment('Danh sách diễn viên');
            $table->integer('duration_minutes')->nullable()->comment('Thời lượng phim (phút)');
            $table->date('release_date')->nullable()->comment('Ngày phát hành');
            $table->date('end_date')->nullable()->comment('Ngày kết thúc chiếu');
            $table->text('description')->nullable()->comment('Mô tả phim');
            $table->string('poster_url', 255)->nullable()->comment('URL ảnh poster');
            $table->string('trailer_url', 255)->nullable()->comment('URL trailer');
            $table->string('language', 50)->nullable()->comment('Ngôn ngữ phim');
            $table->foreignId('country_id')->nullable()->constrained('countries')->comment('ID quốc gia sản xuất');
            $table->foreignId('age_limit_id')->nullable()->constrained('age_limits')->comment('ID giới hạn độ tuổi');
            $table->enum('status', array_column(MovieStatus::cases(), 'value'))->nullable()->comment('Trạng thái phim');
            $table->decimal('average_rating', 3, 1)->nullable()->comment('Điểm đánh giá trung bình (0-10)');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};