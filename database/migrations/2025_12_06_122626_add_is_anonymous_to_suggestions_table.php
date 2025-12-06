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
        Schema::table('suggestions', function (Blueprint $table) {
            // Add is_anonymous column after suggestions column
            $table->boolean('is_anonymous')
                  ->default(false)
                  ->after('suggestions')
                  ->comment('Whether the suggestion was posted anonymously');
            
            // Make user_id nullable for anonymous suggestions
            $table->unsignedBigInteger('user_id')
                  ->nullable()
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suggestions', function (Blueprint $table) {
            // Remove is_anonymous column
            $table->dropColumn('is_anonymous');
            
            // Revert user_id to not nullable
            $table->unsignedBigInteger('user_id')
                  ->nullable(false)
                  ->change();
        });
    }
};