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
        Schema::table('messages', function (Blueprint $table) {
            //
            $table->string('guard_name')->nullable(); // Allows null values if no image is uploaded

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            //
            Schema::table('visitor_gate_monitors', function (Blueprint $table) {
                //
                $table->dropColumn('guard_name');
            });
        });
    }
};