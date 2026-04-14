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
            $table->integer('max_guests');
            $table->integer('bedrooms');
            $table->integer('beds');
            $table->integer('bathrooms');
            $table->string('address');
            $table->string('city')->index();
            $table->string('state')->nullable();
            $table->string('country');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->index();
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
