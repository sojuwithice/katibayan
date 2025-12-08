<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class LogController extends Controller
{
    /**
     * Get log data including statistics and file list
     */
    public function getLogData()
    {
        try {
            $logPath = storage_path('logs');
            $logFiles = [];
            
            if (!file_exists($logPath)) {
                mkdir($logPath, 0755, true);
            }
            
            // Get all log files
            $files = glob($logPath . '/*.log');
            $currentLog = 'laravel.log';
            
            foreach ($files as $file) {
                $filename = basename($file);
                $isCurrent = ($filename === $currentLog);
                
                // Count error and info logs in current file
                $errorCount = 0;
                $infoCount = 0;
                
                if ($isCurrent && file_exists($file)) {
                    $content = file_get_contents($file);
                    $errorCount = substr_count($content, '[error]');
                    $infoCount = substr_count($content, '[info]');
                }
                
                $logFiles[] = [
                    'name' => $filename,
                    'size' => $this->formatBytes(filesize($file)),
                    'modified' => date('Y-m-d H:i:s', filemtime($file)),
                    'is_current' => $isCurrent,
                    'error_count' => $errorCount,
                    'info_count' => $infoCount
                ];
            }
            
            // Sort by modification time (newest first)
            usort($logFiles, function($a, $b) {
                return strtotime($b['modified']) - strtotime($a['modified']);
            });
            
            // Get last cleanup time from settings or default
            $lastCleanup = null; // You can store this in database or cache
            
            return response()->json([
                'success' => true,
                'log_files' => $logFiles,
                'error_count' => array_sum(array_column($logFiles, 'error_count')),
                'info_count' => array_sum(array_column($logFiles, 'info_count')),
                'last_cleanup' => $lastCleanup,
                'log_path' => 'storage/logs/'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting log data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load log data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * View specific log file
     */
    public function viewLogFile($filename)
    {
        try {
            $filepath = storage_path('logs/' . $filename);
            
            if (!file_exists($filepath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Log file not found'
                ], 404);
            }
            
            $content = file_get_contents($filepath);
            $size = filesize($filepath);
            
            return response()->json([
                'success' => true,
                'content' => $content,
                'size' => $this->formatBytes($size),
                'filename' => $filename
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error viewing log file: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to view log file',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * View logs by type
     */
    public function viewLogs($type)
    {
        try {
            $filepath = storage_path('logs/laravel.log');
            
            if (!file_exists($filepath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Log file not found'
                ], 404);
            }
            
            $content = file_get_contents($filepath);
            $lines = explode("\n", $content);
            
            $filteredLogs = [];
            foreach ($lines as $line) {
                if (empty(trim($line))) continue;
                
                if ($type === 'all') {
                    $filteredLogs[] = $line;
                } elseif ($type === 'error' && strpos($line, '[error]') !== false) {
                    $filteredLogs[] = $line;
                } elseif ($type === 'warning' && strpos($line, '[warning]') !== false) {
                    $filteredLogs[] = $line;
                } elseif ($type === 'info' && strpos($line, '[info]') !== false) {
                    $filteredLogs[] = $line;
                } elseif ($type === 'debug' && strpos($line, '[debug]') !== false) {
                    $filteredLogs[] = $line;
                }
            }
            
            // Return last 1000 lines for performance
            $filteredLogs = array_slice($filteredLogs, -1000);
            
            return response()->json([
                'success' => true,
                'logs' => $filteredLogs,
                'count' => count($filteredLogs)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error viewing logs: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to view logs',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Filter logs
     */
    public function filterLogs(Request $request)
    {
        try {
            $level = $request->input('level', 'all');
            $dateFrom = $request->input('date_from');
            $dateTo = $request->input('date_to');
            
            $filepath = storage_path('logs/laravel.log');
            
            if (!file_exists($filepath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Log file not found'
                ], 404);
            }
            
            $content = file_get_contents($filepath);
            $lines = explode("\n", $content);
            
            $filteredLogs = [];
            foreach ($lines as $line) {
                if (empty(trim($line))) continue;
                
                // Filter by level
                $include = false;
                if ($level === 'all') {
                    $include = true;
                } elseif ($level === 'error' && strpos($line, '[error]') !== false) {
                    $include = true;
                } elseif ($level === 'warning' && strpos($line, '[warning]') !== false) {
                    $include = true;
                } elseif ($level === 'info' && strpos($line, '[info]') !== false) {
                    $include = true;
                } elseif ($level === 'debug' && strpos($line, '[debug]') !== false) {
                    $include = true;
                }
                
                // Filter by date if dates provided
                if ($include && ($dateFrom || $dateTo)) {
                    preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $line, $matches);
                    
                    if (isset($matches[1])) {
                        $logDate = strtotime($matches[1]);
                        
                        if ($dateFrom && $logDate < strtotime($dateFrom)) {
                            $include = false;
                        }
                        
                        if ($dateTo && $logDate > strtotime($dateTo . ' 23:59:59')) {
                            $include = false;
                        }
                    }
                }
                
                if ($include) {
                    $filteredLogs[] = $line;
                }
            }
            
            // Return last 1000 lines for performance
            $filteredLogs = array_slice($filteredLogs, -1000);
            
            return response()->json([
                'success' => true,
                'logs' => $filteredLogs,
                'count' => count($filteredLogs)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error filtering logs: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to filter logs',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Download log file
     */
    public function downloadLogFile($filename)
    {
        $filepath = storage_path('logs/' . $filename);
        
        if (!file_exists($filepath)) {
            abort(404, 'Log file not found');
        }
        
        return response()->download($filepath);
    }
    
    /**
     * Clear specific log file
     */
    public function clearLogFile($filename)
    {
        try {
            $filepath = storage_path('logs/' . $filename);
            
            if (!file_exists($filepath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Log file not found'
                ], 404);
            }
            
            // Clear the file
            file_put_contents($filepath, '');
            
            Log::info("Log file cleared: {$filename}");
            
            return response()->json([
                'success' => true,
                'message' => 'Log file cleared successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error clearing log file: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear log file',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete specific log file
     */
    public function deleteLogFile($filename)
    {
        try {
            $filepath = storage_path('logs/' . $filename);
            
            if (!file_exists($filepath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Log file not found'
                ], 404);
            }
            
            // Don't delete current log file
            if ($filename === 'laravel.log') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete current log file. Clear it instead.'
                ], 400);
            }
            
            unlink($filepath);
            
            Log::info("Log file deleted: {$filename}");
            
            return response()->json([
                'success' => true,
                'message' => 'Log file deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error deleting log file: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete log file',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Clear old log files
     */
    public function clearOldLogs(Request $request)
    {
        try {
            $days = $request->input('days', 30);
            $logPath = storage_path('logs');
            $files = glob($logPath . '/*.log');
            $cleaned = 0;
            
            foreach ($files as $file) {
                $filename = basename($file);
                
                // Don't touch current log file
                if ($filename === 'laravel.log') {
                    continue;
                }
                
                if (filemtime($file) < strtotime("-{$days} days")) {
                    unlink($file);
                    $cleaned++;
                }
            }
            
            Log::info("Cleared {$cleaned} old log files (older than {$days} days)");
            
            return response()->json([
                'success' => true,
                'message' => "Cleared {$cleaned} old log files",
                'cleaned' => $cleaned,
                'days' => $days
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error clearing old logs: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear old logs',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Clear all log files
     */
    public function clearAllLogs()
    {
        try {
            $logPath = storage_path('logs');
            $files = glob($logPath . '/*.log');
            $cleaned = 0;
            
            foreach ($files as $file) {
                $filename = basename($file);
                
                // Don't delete current log file, just clear it
                if ($filename === 'laravel.log') {
                    file_put_contents($file, '');
                } else {
                    unlink($file);
                }
                $cleaned++;
            }
            
            Log::info("Cleared all log files: {$cleaned} files affected");
            
            return response()->json([
                'success' => true,
                'message' => 'All log files cleared successfully',
                'cleaned' => $cleaned
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error clearing all logs: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear all logs',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}