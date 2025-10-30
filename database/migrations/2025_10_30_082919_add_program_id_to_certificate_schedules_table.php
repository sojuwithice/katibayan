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
    Schema::table('certificate_schedules', function (Blueprint $table) {
        $table->foreignId('program_id')
              ->nullable() // Pwedeng walang laman (kung event 'yung schedule)
              ->constrained('programs') // I-link sa 'programs' table
              ->onDelete('set null')
              ->after('event_id'); // Ilagay pagkatapos ng 'event_id'
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificate_schedules', function (Blueprint $table) {
            //
        });
    }
};
