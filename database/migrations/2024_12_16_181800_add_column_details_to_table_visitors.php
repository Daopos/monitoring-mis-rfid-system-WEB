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
        Schema::table('visitors', function (Blueprint $table) {
            $table->string('reason')->nullable(); // Allows null values if no reason is provided
            $table->string('type_id')->nullable(); // Allows null values if no type ID is provided
            $table->string('valid_id')->nullable(); // Allows null values if no valid ID is provided
            $table->string('profile_img')->nullable(); // Allows null values if no profile image is uploaded
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn(['reason', 'type_id', 'valid_id', 'profile_img']); // Drops the added columns
        });
    }
};