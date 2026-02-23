<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('scooters', function (Blueprint $table) {
            // Add price per minute
            $table->decimal('price_minute', 8, 2)->default(0.5)->after('price_hour');
            // Make daily price nullable (will be deprecated)
            $table->decimal('price_day', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scooters', function (Blueprint $table) {
            $table->dropColumn('price_minute');
        });
    }
};
