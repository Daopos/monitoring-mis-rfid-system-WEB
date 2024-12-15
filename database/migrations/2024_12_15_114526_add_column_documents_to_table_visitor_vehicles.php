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
        Schema::table('vehicles', function (Blueprint $table) {
            //
            $table->string('vehicle_img')->nullable(); // Allows null values if no image is uploaded
            $table->string('or_img')->nullable(); // Allows null values if no image is uploaded
            $table->string('cr_img')->nullable(); // Allows null values if no image is uploaded

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            //
            $table->dropColumn(['vehicle_mg', 'or_img', 'cr_img']);
        });
    }
};
