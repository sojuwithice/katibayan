<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPostponedToEventsTable extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('postponed')->default(false)->after('is_launched');
            $table->dateTime('postponed_to')->nullable()->after('postponed');
            $table->text('postponed_reason')->nullable()->after('postponed_to');
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['postponed', 'postponed_to', 'postponed_reason']);
        });
    }
}