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
        Schema::table('program_registrations', function (Blueprint $table) {
            $table->boolean('attended')->default(false)->after('reference_id');
            $table->timestamp('attended_at')->nullable()->after('attended');
            $table->foreignId('marked_by_user_id')->nullable()->after('attended_at')->constrained('users')->onDelete('set null');
            
            // Optional: Add index for better performance when querying attendance
            $table->index(['attended']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_registrations', function (Blueprint $table) {
            $table->dropForeign(['marked_by_user_id']);
            $table->dropIndex(['attended']);
            $table->dropColumn(['attended', 'attended_at', 'marked_by_user_id']);
        });
    }
};