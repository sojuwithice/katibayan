<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_events_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('event_date');
            $table->time('event_time');
            $table->string('location');
            $table->string('category');
            $table->string('image')->nullable();
            $table->string('published_by');
            $table->string('status')->default('upcoming'); // upcoming, ongoing, completed, rescheduled
            $table->boolean('is_launched')->default(false);
            $table->string('passcode')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
};