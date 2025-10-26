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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            // Ito yung susi para ma-filter per barangay
            $table->unsignedBigInteger('barangay_id')->nullable()->index(); 
            $table->string('title');
            $table->text('message');
            // Para malaman anong uri ng announcement (certificate, general, etc.)
            $table->string('type')->nullable(); 
            // Para kusang mawala ang announcement paglipas ng petsa
            $table->timestamp('expires_at')->nullable(); 
            $table->timestamps(); // created_at at updated_at

            // Optional: Kung may 'barangays' table ka, pwede mong i-link
            // $table->foreign('barangay_id')->references('id')->on('barangays')->onDelete('cascade'); // o 'set null'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};