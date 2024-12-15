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
        Schema::table('household_gate_monitors', function (Blueprint $table) {
            //
            $table->string('in_img')->nullable(); // Allows null values if no image is uploaded
            $table->string('out_img')->nullable(); // Allows null values if no image is uploaded
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('household_gate_monitors', function (Blueprint $table) {
            $table->dropColumn('in_img');
            $table->dropColumn('out_img');
            //
        });
    }
};