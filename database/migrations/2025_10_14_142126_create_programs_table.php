<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_programs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('event_date');
            $table->time('event_time');
            $table->string('category');
            $table->text('location');
            $table->text('description')->nullable();
            $table->string('display_image')->nullable();
            $table->string('published_by');
            $table->enum('registration_type', ['create', 'link']);
            $table->string('link_source')->nullable();
            $table->text('registration_description')->nullable();
            $table->date('registration_open_date')->nullable();
            $table->time('registration_open_time')->nullable();
            $table->date('registration_close_date')->nullable();
            $table->time('registration_close_time')->nullable();
            $table->foreignId('barangay_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('programs');
    }
};