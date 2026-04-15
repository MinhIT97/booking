<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('host_id')->index();
            $table->string('title');
            $table->text('description');
            $table->decimal('price_per_night', 10, 2);
            $table->unsignedInteger('reviews_count')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0.00);
            $table->unsignedInteger('max_guests');
            $table->unsignedInteger('bedrooms');
            $table->unsignedInteger('beds');
            $table->unsignedInteger('bathrooms');
            $table->string('address');
            $table->string('city')->index();
            $table->string('state')->nullable();
            $table->string('country');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            // 1: draft, 2: active, 3: rejected
            $table->tinyInteger('status')->default(1)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('host_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
