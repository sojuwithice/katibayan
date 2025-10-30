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
            // Idagdag ang 'program_id' column pagkatapos ng 'event_id'
            // Ginawa nating 'nullable' para 'yung mga lumang data na 'event_id' lang
            // ang may laman ay hindi magka-error.
            $table->unsignedBigInteger('program_id')->nullable()->after('event_id');

            // (Optional, pero recommended) Idagdag ang foreign key constraint
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificate_requests', function (Blueprint $table) {
            // Tatanggalin 'yung foreign key bago i-drop 'yung column
            $table->dropForeign(['program_id']);
            $table->dropColumn('program_id');
        });
    }
};