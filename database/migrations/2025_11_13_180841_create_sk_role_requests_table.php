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
    Schema::create('sk_role_requests', function (Blueprint $table) {
        $table->id();
        
        // Sino 'yung nag-request?
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        // Ano 'yung status?
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sk_role_requests');
    }
};
