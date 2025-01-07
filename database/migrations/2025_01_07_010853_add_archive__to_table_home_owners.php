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
        Schema::table('home_owners', function (Blueprint $table) {
            //

            $table->boolean('archived')->default(false); // Add the archived column

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_owners', function (Blueprint $table) {
            //
            $table->dropColumn(['archived']); // Drops the added columns

        });
    }
};
