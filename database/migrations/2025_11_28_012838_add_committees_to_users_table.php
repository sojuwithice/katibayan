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
        Schema::table('users', function (Blueprint $table) {
            // Nagdadagdag ng 'committees' column. 
            // Ginawa nating nullable kasi hindi naman lahat ng user may committee.
            // Nilagay natin after ng 'sk_role' para magkatabi sila.
            $table->text('committees')->nullable()->after('sk_role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Pang-undo kung sakaling kailangan burahin
            $table->dropColumn('committees');
        });
    }
};