<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('assistance_settings', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('barangay_id');
        $table->text('description')->nullable();
        $table->string('fb_link')->nullable();
        $table->string('msgr_link')->nullable();
        $table->timestamps();

        $table->foreign('barangay_id')->references('id')->on('barangays')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assistance_settings');
    }
};
