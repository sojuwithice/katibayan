<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('region')->after('password')->nullable();
            $table->string('province')->after('region')->nullable();
            $table->string('city_municipality')->after('province')->nullable();
            $table->string('zipcode')->after('city_municipality')->nullable();
            $table->string('purok_zone')->after('zipcode')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['region', 'province', 'city_municipality', 'zipcode', 'purok_zone']);
        });
    }
};
