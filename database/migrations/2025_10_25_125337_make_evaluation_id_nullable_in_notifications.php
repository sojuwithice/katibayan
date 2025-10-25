<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeEvaluationIdNullableInNotifications extends Migration
{
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            // First drop the foreign key constraint
            $table->dropForeign(['evaluation_id']);
            
            // Make the column nullable
            $table->unsignedBigInteger('evaluation_id')->nullable()->change();
            
            // Re-add the foreign key constraint but allow null
            $table->foreign('evaluation_id')->references('id')->on('evaluations')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['evaluation_id']);
            $table->unsignedBigInteger('evaluation_id')->nullable(false)->change();
            $table->foreign('evaluation_id')->references('id')->on('evaluations')->onDelete('cascade');
        });
    }
}