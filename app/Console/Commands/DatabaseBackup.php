<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DatabaseBackup extends Command
{
    protected $signature = 'db:backup {--manual : Create manual backup}';
    protected $description = 'Create a backup of the database';

    public function handle()
    {
        $this->info('Starting database backup...');

        // Database configuration
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        
        // Backup directory
        $backupPath = storage_path('app/backups');
        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }
        
        // File name with timestamp
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $filename = "katibayan_backup_{$timestamp}.sql";
        $filepath = $backupPath . '/' . $filename;
        
        // mysqldump command
        $command = sprintf(
            'mysqldump --host=%s --user=%s --password=%s %s > %s',
            $host,
            $username,
            $password,
            $database,
            $filepath
        );
        
        // Execute backup
        $returnVar = NULL;
        $output = NULL;
        exec($command, $output, $returnVar);
        
        if ($returnVar === 0) {
            // Compress the backup
            $compressedFilename = "katibayan_backup_{$timestamp}.sql.gz";
            $compressedFilepath = $backupPath . '/' . $compressedFilename;
            
            exec("gzip -c {$filepath} > {$compressedFilepath}");
            
            // Delete uncompressed file
            unlink($filepath);
            
            // Clean old backups (keep last 30 days)
            $this->cleanOldBackups();
            
            $this->info("Backup created successfully: {$compressedFilename}");
            Log::info("Database backup created: {$compressedFilename}");
            
            return 0;
        } else {
            $this->error('Backup failed!');
            Log::error('Database backup failed');
            return 1;
        }
    }
    
    private function cleanOldBackups()
    {
        $backupPath = storage_path('app/backups');
        $files = glob($backupPath . '/*.sql.gz');
        $daysToKeep = 30; // Keep backups for 30 days
        
        foreach ($files as $file) {
            if (filemtime($file) < strtotime("-{$daysToKeep} days")) {
                unlink($file);
            }
        }
    }
}