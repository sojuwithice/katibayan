<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomFieldsToProgramsAndRegistrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add columns to programs table
        Schema::table('programs', function (Blueprint $table) {
            $table->string('registration_title')->nullable()->after('registration_type');
            $table->json('custom_fields')->nullable()->after('registration_close_time');
        });

        // Add columns to program_registrations table
        Schema::table('program_registrations', function (Blueprint $table) {
            $table->json('registration_data')->nullable()->after('special_requirements');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove columns from programs table
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn(['registration_title', 'custom_fields']);
        });

        // Remove columns from program_registrations table
        Schema::table('program_registrations', function (Blueprint $table) {
            $table->dropColumn(['registration_data']);
        });
    }
}