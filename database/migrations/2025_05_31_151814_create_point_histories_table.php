<?php

   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;

   return new class extends Migration
   {
       public function up(): void
       {
           Schema::create('point_histories', function (Blueprint $table) {
               $table->id();
               $table->unsignedBigInteger('user_id');
               $table->unsignedBigInteger('booking_id')->nullable();
               $table->integer('points_change');
               $table->enum('reason_type', ['earned', 'spent', 'expired', 'adjusted']);
               $table->string('description')->nullable();
               $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
               $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');

               $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
               $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('set null');
           });
       }

       public function down(): void
       {
           Schema::dropIfExists('point_histories');
       }
   };