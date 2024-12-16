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
        Schema::create('visitor_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId( 'visitor_id')->constrained('visitors')->onDelete('cascade');
            $table->string('name')->nullable(); // Allows null values if no image is uploaded
            $table->string('type_id')->nullable(); // Allows null values if no image is uploaded
            $table->string('valid_id')->nullable(); // Allows null values if no image is uploaded
            $table->string('profile_img')->nullable(); // Allows null values if no image is uploaded
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_groups');
    }
};