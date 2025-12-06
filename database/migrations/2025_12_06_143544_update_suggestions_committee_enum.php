<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateSuggestionsCommitteeEnum extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, temporarily change the column to VARCHAR to avoid ENUM constraints
        DB::statement("ALTER TABLE suggestions MODIFY COLUMN committee VARCHAR(50) NOT NULL");
        
        // Update all existing records to 'others'
        DB::table('suggestions')->update(['committee' => 'others']);
        
        // Now change it back to ENUM with new values
        DB::statement("ALTER TABLE suggestions MODIFY COLUMN committee ENUM('event', 'program', 'others') NOT NULL DEFAULT 'others'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Change to VARCHAR first
        DB::statement("ALTER TABLE suggestions MODIFY COLUMN committee VARCHAR(50) NOT NULL");
        
        // Update records to a value that will exist in the old ENUM
        DB::table('suggestions')->update(['committee' => 'education']);
        
        // Change back to original ENUM
        DB::statement("ALTER TABLE suggestions MODIFY COLUMN committee ENUM('active citizenship', 'economic', 'education') NOT NULL DEFAULT 'education'");
    }
}