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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId( 'home_owner_id')->constrained('home_owners')->onDelete('cascade');
            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('color')->nullable();
            $table->string('model')->nullable();
            $table->string('plate_number')->nullable();
            $table->string('rfid')->nullable(); // RFID assigned to visitor
            $table->string('relationship')->nullable();
            $table->date('date_visit')->nullable();
            $table->string('number_vistiors')->nullable();
            $table->enum('status', ['pending', 'approved', 'denied', 'requested'])->default('pending');
            $table->boolean('guard')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
