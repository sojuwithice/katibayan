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
        // Check if the column exists first
        if (!Schema::hasColumn('notifications', 'data')) {
            Schema::table('notifications', function (Blueprint $table) {
                // ADD the 'data' column (not change it)
                $table->text('data')->nullable();
            });
        } else {
            // If column exists, then make it nullable
            Schema::table('notifications', function (Blueprint $table) {
                $table->text('data')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Remove the 'data' column if it was added in this migration
            $table->dropColumn('data');
        });
    }
};