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
        Schema::create('outsiders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('vehicle_type')->nullable();
            $table->string('brand')->nullable();
            $table->string('color')->nullable();
            $table->string('model')->nullable();
            $table->string('plate_number')->nullable();
            $table->string('rfid')->nullable(); // Add RFID field
            $table->timestamp('in')->nullable(); // Add RFID field
            $table->timestamp('out')->nullable(); // Add RFID field
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outsiders');
    }
};
