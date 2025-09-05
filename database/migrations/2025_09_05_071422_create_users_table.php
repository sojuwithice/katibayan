<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->enum('role', ['sk', 'kk']);
            $table->string('last_name');
            $table->string('given_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix', 10)->nullable();
            $table->text('address');
            $table->date('date_of_birth');
            $table->enum('sex', ['male', 'female']);
            $table->string('email')->unique();
            $table->string('contact_no');
            $table->enum('civil_status', [
                'Single', 'Married', 'Widowed', 'Divorced', 
                'Separated', 'Anulled', 'Unknown', 'Live-in'
            ]);
            $table->enum('education', [
                'Elementary Level', 'Elementary Graduate', 'High School Level',
                'High School Graduate', 'Vocational Graduate', 'College Level',
                'College Graduate', 'Masters Level', 'Masters Graduate',
                'Doctorate Level', 'Doctorate Graduate'
            ]);
            $table->enum('work_status', [
                'Student', 'Employed', 'Unemployed', 'Self-Employed',
                'Currently looking for a Job', 'Not Interested Looking for a Job'
            ]);
            $table->enum('youth_classification', [
                'In-School Youth', 'Out-of-School Youth', 'Working Youth',
                'Youth with Specific Needs', 'Person with Disability (PWD)',
                'Children in Conflict with the Law (CICL)', 'Indigenous People (IP)'
            ]);
            $table->enum('sk_voter', ['Yes', 'No']);
            $table->enum('account_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};