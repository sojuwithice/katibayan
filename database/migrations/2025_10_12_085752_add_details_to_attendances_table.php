<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('status')->default('Present')->after('passcode_used');
            $table->string('account_number')->nullable()->after('status');
            $table->string('fullname')->nullable()->after('account_number');
            $table->integer('age')->nullable()->after('fullname');
            $table->string('purok')->nullable()->after('age');
            $table->string('role')->nullable()->after('purok');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['status', 'account_number', 'fullname', 'age', 'purok', 'role']);
        });
    }
};
