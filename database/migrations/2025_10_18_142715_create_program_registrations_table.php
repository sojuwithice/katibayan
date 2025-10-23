<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('program_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('motivation')->nullable();
            $table->text('expectations')->nullable();
            $table->text('special_requirements')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('reference_id')->unique();
            $table->timestamps();
            
            // Ensure one registration per user per program
            $table->unique(['program_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('program_registrations');
    }
};