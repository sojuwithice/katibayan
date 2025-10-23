<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProgramRegistrationsTable extends Migration
{
    public function up()
    {
        Schema::table('program_registrations', function (Blueprint $table) {
            // Remove old fields that are no longer needed
            $table->dropColumn(['motivation', 'expectations', 'special_requirements', 'status']);
            
            // Make sure registration_data exists and is properly configured
            if (!Schema::hasColumn('program_registrations', 'registration_data')) {
                $table->json('registration_data')->nullable()->after('user_id');
            }
        });
    }

    public function down()
    {
        Schema::table('program_registrations', function (Blueprint $table) {
            // Add back the removed columns if rolling back
            $table->text('motivation')->nullable();
            $table->text('expectations')->nullable();
            $table->text('special_requirements')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        });
    }
}