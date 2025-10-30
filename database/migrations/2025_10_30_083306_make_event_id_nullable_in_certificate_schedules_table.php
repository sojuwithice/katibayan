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
    Schema::table('certificate_schedules', function (Blueprint $table) {
        // Sinasabi natin na 'yung event_id column ay pwede nang maging NULL
        $table->unsignedBigInteger('event_id')->nullable()->change();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificate_schedules', function (Blueprint $table) {
            //
        });
    }
};
