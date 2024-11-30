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
        Schema::create('home_owners', function (Blueprint $table) {
            $table->id();
            $table->string('fname');
            $table->string('lname');
            $table->string('phone');
            $table->string('email');
            $table->string('plate')->nullable();
            $table->string('extension')->nullable();
            $table->string('mname')->nullable();
            $table->date('birthdate');
            $table->string('gender');
            $table->string('rfid')->nullable();;
            $table->string('image')->nullable();
            $table->string('position')->nullable();
            $table->string('password');
            $table->string('status');
            $table->string('phase');
            $table->string('block');
            $table->string('lot');
            $table->string('document_image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_owners');
    }
};