<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluation_questions', function (Blueprint $table) {
            // First add the column
            $table->boolean('is_default')->default(false)->after('is_active');
        });

        // Now update the values AFTER the column is added
        DB::table('evaluation_questions')->update(['is_default' => false]);
    }

    public function down(): void
    {
        Schema::table('evaluation_questions', function (Blueprint $table) {
            $table->dropColumn('is_default');
        });
    }
};