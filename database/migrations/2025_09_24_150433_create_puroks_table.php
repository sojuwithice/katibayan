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
       Schema::create('puroks', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // e.g., Purok 1, Zone 2
        $table->foreignId('barangay_id')->constrained()->onDelete('cascade');
        $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puroks');
    }
};
