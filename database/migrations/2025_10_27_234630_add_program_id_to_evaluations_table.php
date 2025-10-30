<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->unsignedBigInteger('program_id')->nullable()->after('event_id');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            
            // Make event_id nullable since now we can have program evaluations too
            $table->unsignedBigInteger('event_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->dropColumn('program_id');
            $table->unsignedBigInteger('event_id')->nullable(false)->change();
        });
    }
};