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
    Schema::table('certificate_requests', function (Blueprint $table) {
        // Sinasabi natin dito na 'yung event_id ay pwedeng maging NULL
        $table->unsignedBigInteger('event_id')->nullable()->change();

        // Pati 'yung program_id, dapat pwedeng maging NULL
        $table->unsignedBigInteger('program_id')->nullable()->change();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificate_requests', function (Blueprint $table) {
            //
        });
    }
};
