<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('certificate_schedules', function (Blueprint $table) {
        $table->id();
        // Ito ang link papunta sa event
        $table->foreignId('event_id')->constrained('events')->onDelete('cascade'); 
        $table->date('release_date');
        $table->string('release_time');
        $table->string('location');
        $table->timestamps(); // created_at, updated_at

        // Siguraduhin na iisa lang ang schedule per event (optional pero recommended)
        $table->unique('event_id'); 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_schedules');
    }
};
