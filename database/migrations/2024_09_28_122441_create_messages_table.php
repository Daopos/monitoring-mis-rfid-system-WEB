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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('message');
            $table->foreignId('home_owner_id')->references('id')->on('home_owners')->onDelete('cascade');
            $table->string('sender_role');
            $table->string('recipient_role' )->nullable();
            $table->boolean('is_seen')->default(false); // Tracks if the message has been seen
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};