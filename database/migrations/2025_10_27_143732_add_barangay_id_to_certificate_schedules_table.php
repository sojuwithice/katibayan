<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('certificate_schedules', function (Blueprint $table) {
            $table->foreignId('barangay_id')->nullable()->constrained('barangays')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('certificate_schedules', function (Blueprint $table) {
            $table->dropForeign(['barangay_id']);
            $table->dropColumn('barangay_id');
        });
    }
};