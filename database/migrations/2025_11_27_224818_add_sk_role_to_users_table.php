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
    Schema::table('users', function (Blueprint $table) {
        // Ito yung code na mag-a-add ng column sa database ng teammates mo
        // Gumamit tayo ng nullable() para okay lang kahit walang laman
        $table->string('sk_role')->nullable()->after('role');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        // Ito naman pang-undo kung sakaling i-rollback nila
        $table->dropColumn('sk_role');
    });
}
};
