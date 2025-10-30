<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('organizational_charts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barangay_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->string('original_name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['barangay_id', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('organizational_charts');
    }
};