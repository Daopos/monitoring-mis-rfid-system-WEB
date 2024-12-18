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
            $table->string('or_number')->nullable(); // Allows null values if no type ID is provided
            $table->string('cr_number')->nullable(); // Allows null values if no valid ID is provided
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            //
            $table->dropColumn(['or_number', 'cr_number']); // Drops the added columns
        });
    }
};