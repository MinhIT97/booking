<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('property_id')->index();
            $table->uuid('user_id')->index();
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->unsignedInteger('guests');
            $table->decimal('total_price', 10, 2);
            // 1: pending, 2: confirmed, 3: cancelled, 4: completed
            $table->tinyInteger('status')->default(1)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Helpful index for double booking checks
            $table->index(['property_id', 'status', 'check_in_date', 'check_out_date'], 'booking_availability_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
