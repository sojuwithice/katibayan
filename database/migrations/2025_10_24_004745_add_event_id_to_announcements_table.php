<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('announcements', function (Blueprint $table) {
        // Idagdag ito. Nilagay ko 'yung ->after('id') para madaling makita, pero pwedeng wala 'yun.
        // Ang 'nullable()' ay importante para hindi mag-error 'yung mga luma mong data.
        $table->foreignId('event_id')
              ->nullable()
              ->after('id') // Pwede mong ilagay after 'barangay_id' kung mas gusto mo
              ->constrained('events')
              ->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('announcements', function (Blueprint $table) {
        $table->dropForeign(['event_id']);
        $table->dropColumn('event_id');
    });
}
};
