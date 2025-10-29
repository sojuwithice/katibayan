<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('barangay_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('image')->nullable();
            $table->text('services_offered')->nullable(); // JSON array
            $table->string('location')->nullable();
            $table->text('how_to_avail')->nullable();
            $table->text('contact_info')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['barangay_id', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
};