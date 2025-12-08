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
    Schema::create('sk_contact_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('barangay_id')
              ->unique()
              ->constrained('barangays')
              ->onDelete('cascade');
              
        $table->text('assistance_description')->nullable();
        $table->string('assistance_fb_link', 500)->nullable();
        $table->string('assistance_msgr_link', 500)->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('sk_contact_details');
}
};
