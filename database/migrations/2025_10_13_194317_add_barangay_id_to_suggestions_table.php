<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBarangayIdToSuggestionsTable extends Migration
{
    public function up()
    {
        Schema::table('suggestions', function (Blueprint $table) {
            $table->unsignedBigInteger('barangay_id')->after('user_id');
            $table->foreign('barangay_id')->references('id')->on('barangays')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('suggestions', function (Blueprint $table) {
            $table->dropForeign(['barangay_id']);
            $table->dropColumn('barangay_id');
        });
    }
}