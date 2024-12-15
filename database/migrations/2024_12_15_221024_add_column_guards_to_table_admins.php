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
        Schema::table('admins', function (Blueprint $table) {
            //
            $table->string('email')->nullable(); // Allows null values if no image is uploaded
            $table->string('fname')->nullable(); // Allows null values if no image is uploaded
            $table->string('mname')->nullable(); // Allows null values if no image is uploaded
            $table->string('lname')->nullable(); // Allows null values if no image is uploaded
            $table->string('phone')->nullable(); // Allows null values if no image is uploaded
            $table->date('hired')->nullable(); // Allows null values if no image is uploaded
            $table->string('active')->default(false); // Allows null values if no image is uploaded
            $table->string('token')->nullable(); // Allows null values if no image is uploaded
            $table->boolean('is_archived')->default(false); // Manage archived guards
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'fname',
                'mname',
                'lname',
                'phone',
                'hired',
                'active',
                'token',
                'is_archived',
            ]);
        });
    }
};