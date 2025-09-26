<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add location columns
            $table->unsignedBigInteger('region_id')->after('suffix');
            $table->unsignedBigInteger('province_id')->after('region_id');
            $table->unsignedBigInteger('city_id')->after('province_id');
            $table->unsignedBigInteger('barangay_id')->after('city_id');
            $table->string('purok_zone', 100)->after('barangay_id');
            $table->string('zip_code', 10)->after('purok_zone');
            
            // Add foreign key constraints
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('restrict');
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('restrict');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('restrict');
            $table->foreign('barangay_id')->references('id')->on('barangays')->onDelete('restrict');
            
            // Add indexes for better performance
            $table->index('region_id');
            $table->index('province_id');
            $table->index('city_id');
            $table->index('barangay_id');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['region_id']);
            $table->dropForeign(['province_id']);
            $table->dropForeign(['city_id']);
            $table->dropForeign(['barangay_id']);
            
            // Drop indexes
            $table->dropIndex(['region_id']);
            $table->dropIndex(['province_id']);
            $table->dropIndex(['city_id']);
            $table->dropIndex(['barangay_id']);
            
            // Drop columns
            $table->dropColumn([
                'region_id', 
                'province_id', 
                'city_id', 
                'barangay_id', 
                'purok_zone', 
                'zip_code'
            ]);
        });
    }
};