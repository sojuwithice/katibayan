<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->unsignedBigInteger('certificate_request_id')->nullable()->after('barangay_id');

            $table->foreign('certificate_request_id')
                ->references('id')->on('certificate_requests')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropForeign(['certificate_request_id']);
            $table->dropColumn('certificate_request_id');
        });
    }
};
