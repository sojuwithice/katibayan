<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class BackupController extends Controller
{
    public function getBackupData()
    {
        $backupPath = storage_path('app/backups');
        $backups = [];
        
        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }
        
        // Look for all backup files
        $files = glob($backupPath . '/*.sql');
        $zipFiles = glob($backupPath . '/*.zip');
        $allFiles = array_merge($files, $zipFiles);
        
        foreach ($allFiles as $file) {
            $backups[] = [
                'name' => basename($file),
                'size' => $this->formatBytes(filesize($file)),
                'date' => date('Y-m-d H:i:s', filemtime($file)),
            ];
        }
        
        // Sort by date (newest first)
        usort($backups, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        // Get database size
        $databaseSize = $this->getDatabaseSize();
        
        return response()->json([
            'success' => true,
            'databaseSize' => $databaseSize,
            'totalBackups' => count($backups),
            'lastBackup' => count($backups) > 0 ? $backups[0]['date'] : null,
            'backupPath' => 'storage/app/backups/',
            'backups' => $backups
        ]);
    }
    
    public function createBackup(Request $request)
    {
        try {
            // Get database configuration from .env
            $database = env('DB_DATABASE', 'katibayandb');
            $username = env('DB_USERNAME', 'root');
            $password = env('DB_PASSWORD', '');
            $host = env('DB_HOST', '127.0.0.1');
            $port = env('DB_PORT', '3306');
            
            $backupPath = storage_path('app/backups');
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }
            
            $timestamp = date('Y-m-d_H-i-s');
            $filename = "katibayan_backup_{$timestamp}.sql";
            $filepath = $backupPath . '/' . $filename;
            $zipFilename = "katibayan_backup_{$timestamp}.zip";
            $zipFilepath = $backupPath . '/' . $zipFilename;
            
            // Build mysqldump command for Windows
            $command = "mysqldump --host={$host} --port={$port} --user={$username}";
            
            // Add password only if set
            if (!empty($password)) {
                $command .= " --password={$password}";
            }
            
            $command .= " {$database} --result-file=\"{$filepath}\"";
            
            // Execute command
            $output = [];
            $returnVar = 0;
            exec($command, $output, $returnVar);
            
            if ($returnVar === 0 && file_exists($filepath)) {
                // Create ZIP file for easier download
                if (class_exists('ZipArchive')) {
                    $zip = new ZipArchive();
                    if ($zip->open($zipFilepath, ZipArchive::CREATE) === TRUE) {
                        $zip->addFile($filepath, $filename);
                        $zip->close();
                        
                        // Delete the SQL file after zipping
                        unlink($filepath);
                        
                        return response()->json([
                            'success' => true,
                            'message' => 'Backup created successfully!',
                            'filename' => $zipFilename
                        ]);
                    }
                }
                
                // If ZIP fails, return SQL file
                return response()->json([
                    'success' => true,
                    'message' => 'Backup created (SQL file)',
                    'filename' => $filename
                ]);
            } else {
                // If mysqldump fails, try PHP backup as fallback
                return $this->phpBackup();
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // PHP-based backup (fallback method)
    private function phpBackup()
    {
        try {
            $backupPath = storage_path('app/backups');
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }
            
            $timestamp = date('Y-m-d_H-i-s');
            $filename = "katibayan_php_backup_{$timestamp}.sql";
            $filepath = $backupPath . '/' . $filename;
            
            // Get all tables
            $tables = DB::select('SHOW TABLES');
            $databaseName = env('DB_DATABASE', 'katibayandb');
            
            $sqlScript = "-- KatiBayan Database Backup\n";
            $sqlScript .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
            $sqlScript .= "-- Database: " . $databaseName . "\n";
            $sqlScript .= "-- Method: PHP Backup\n\n";
            
            foreach ($tables as $table) {
                $tableName = $table->{'Tables_in_' . $databaseName};
                
                // Get create table statement
                $createTable = DB::select("SHOW CREATE TABLE `$tableName`");
                $createTableSQL = $createTable[0]->{'Create Table'};
                
                $sqlScript .= "DROP TABLE IF EXISTS `$tableName`;\n";
                $sqlScript .= $createTableSQL . ";\n\n";
                
                // Get table data
                $rows = DB::table($tableName)->get();
                
                if ($rows->count() > 0) {
                    $sqlScript .= "INSERT INTO `$tableName` VALUES \n";
                    
                    $rowValues = [];
                    foreach ($rows as $row) {
                        $values = [];
                        foreach ((array)$row as $value) {
                            if ($value === null) {
                                $values[] = 'NULL';
                            } else {
                                $values[] = "'" . addslashes($value) . "'";
                            }
                        }
                        $rowValues[] = "(" . implode(', ', $values) . ")";
                    }
                    
                    $sqlScript .= implode(",\n", $rowValues) . ";\n\n";
                }
            }
            
            // Write to file
            file_put_contents($filepath, $sqlScript);
            
            return response()->json([
                'success' => true,
                'message' => 'Backup created using PHP method',
                'filename' => $filename
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'PHP backup failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function downloadBackup($filename)
    {
        $filepath = storage_path('app/backups/' . $filename);
        
        if (!file_exists($filepath)) {
            abort(404, 'Backup file not found');
        }
        
        return response()->download($filepath);
    }
    
    public function deleteBackup($filename)
    {
        $filepath = storage_path('app/backups/' . $filename);
        
        if (file_exists($filepath)) {
            unlink($filepath);
            return response()->json([
                'success' => true,
                'message' => 'Backup deleted successfully'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Backup file not found'
        ], 404);
    }
    
    public function cleanupOldBackups(Request $request)
    {
        $days = $request->input('days', 30);
        $backupPath = storage_path('app/backups');
        $files = glob($backupPath . '/*');
        $deleted = 0;
        
        foreach ($files as $file) {
            if (filemtime($file) < strtotime("-{$days} days")) {
                unlink($file);
                $deleted++;
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "Cleaned up {$deleted} old backups",
            'deleted' => $deleted
        ]);
    }
    
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
    
    private function getDatabaseSize()
    {
        try {
            $database = env('DB_DATABASE', 'katibayandb');
            $result = DB::select("
                SELECT SUM(data_length + index_length) as size
                FROM information_schema.TABLES 
                WHERE table_schema = ?
                GROUP BY table_schema
            ", [$database]);
            
            if (!empty($result)) {
                return $this->formatBytes($result[0]->size);
            }
            
            return '0 B';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }
}