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
        Schema::table('notifications', function (Blueprint $table) {
            // DAPAT GAWIN MONG NULLABLE ANG 'data' COLUMN
            $table->text('data')->nullable()->change(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Kung gusto mo ring i-reverse ang pagbabago, dapat mo ring i-undo.
            // Ngunit kung ang column ay in-add sa ibang migration, baka hindi mo na ito kailangan.
        });
    }
};