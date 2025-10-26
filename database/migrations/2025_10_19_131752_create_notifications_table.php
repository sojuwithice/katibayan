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
    Schema::create('certificate_requests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('event_id')->constrained()->onDelete('cascade');
    $table->string('status')->default('requesting');
    $table->timestamps();

    // ✅ Prevent duplicate requests
    $table->unique(['user_id', 'event_id']);
});
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('certificate_requests');
}

};

