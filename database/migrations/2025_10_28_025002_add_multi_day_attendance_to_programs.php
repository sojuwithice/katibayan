<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultiDayAttendanceToPrograms extends Migration
{
    public function up()
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->integer('number_of_days')->default(1)->after('event_time');
            $table->date('event_end_date')->nullable()->after('event_date');
        });

        Schema::table('program_registrations', function (Blueprint $table) {
            $table->json('attendance_days')->nullable()->after('attended_at');
        });
    }

    public function down()
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn(['number_of_days', 'event_end_date']);
        });

        Schema::table('program_registrations', function (Blueprint $table) {
            $table->dropColumn('attendance_days');
        });
    }
}