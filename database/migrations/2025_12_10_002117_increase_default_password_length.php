<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Change VARCHAR(255) to TEXT to store encrypted passwords
            $table->text('default_password')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Change back to VARCHAR(255) if rolling back
            $table->string('default_password', 255)->nullable()->change();
        });
    }
};