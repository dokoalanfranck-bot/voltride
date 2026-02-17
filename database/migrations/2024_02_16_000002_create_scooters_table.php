<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scooters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price_hour', 8, 2);
            $table->decimal('price_day', 8, 2);
            $table->integer('battery_level')->default(100);
            $table->enum('status', ['available', 'rented', 'maintenance'])->default('available');
            $table->boolean('is_active')->default(true);
            $table->decimal('max_speed', 5, 2)->nullable();
            $table->string('qr_code')->unique()->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scooters');
    }
};
