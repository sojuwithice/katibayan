<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Table para sa Folders
        Schema::create('report_folders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->default('#4a6cf7'); // Default blue
            $table->foreignId('user_id')->constrained('users'); // Kung sino ang nag-create
            $table->timestamps();
        });

        // Table para sa Files
        Schema::create('report_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folder_id')->constrained('report_folders')->onDelete('cascade');
            $table->string('name'); // Original filename
            $table->string('path'); // Path sa storage/app/public
            $table->string('type'); // PDF, Image, etc.
            $table->string('size'); // Formatted size (e.g., "2 MB")
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('report_files');
        Schema::dropIfExists('report_folders');
    }
};
