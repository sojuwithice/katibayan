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
    Schema::table('certificate_requests', function (Blueprint $table) {
        // Magdaragdag tayo ng column para sa request count
        // Default sa '1' sa unang request
        $table->integer('request_count')->default(1)->after('status');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('certificate_requests', function (Blueprint $table) {
        $table->dropColumn('request_count');
    });
}
};
