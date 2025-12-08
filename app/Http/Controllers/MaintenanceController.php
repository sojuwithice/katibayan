<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class MaintenanceController extends Controller
{
    /**
     * Enable maintenance mode
     */
    public function enable(Request $request)
    {
        try {
            $message = $request->input('message', 'The site is currently under maintenance. Please check back later.');
            $allowedIps = $request->input('allowed_ips', []);
            $retryAfter = $request->input('retry_after', 60); // in seconds
            
            // Store maintenance settings in cache
            Cache::put('maintenance_mode', true, now()->addDays(1));
            Cache::put('maintenance_message', $message, now()->addDays(1));
            Cache::put('maintenance_allowed_ips', $allowedIps, now()->addDays(1));
            Cache::put('maintenance_retry_after', $retryAfter, now()->addDays(1));
            
            // Also store in session for immediate effect
            session(['maintenance_mode' => true]);
            
            // Create a maintenance file (traditional Laravel way)
            File::put(storage_path('framework/down'), json_encode([
                'time' => time(),
                'message' => $message,
                'retry' => $retryAfter,
                'allowed' => $allowedIps,
            ]));
            
            // Log the action
            \Log::info('Maintenance mode enabled by admin');
            
            return response()->json([
                'success' => true,
                'message' => 'Maintenance mode enabled successfully',
                'data' => [
                    'message' => $message,
                    'allowed_ips' => $allowedIps,
                    'retry_after' => $retryAfter
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to enable maintenance mode: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to enable maintenance mode: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Disable maintenance mode
     */
    public function disable()
    {
        try {
            // Remove maintenance settings from cache
            Cache::forget('maintenance_mode');
            Cache::forget('maintenance_message');
            Cache::forget('maintenance_allowed_ips');
            Cache::forget('maintenance_retry_after');
            
            // Remove from session
            session()->forget('maintenance_mode');
            
            // Remove maintenance file
            if (File::exists(storage_path('framework/down'))) {
                File::delete(storage_path('framework/down'));
            }
            
            \Log::info('Maintenance mode disabled by admin');
            
            return response()->json([
                'success' => true,
                'message' => 'Maintenance mode disabled successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to disable maintenance mode: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to disable maintenance mode: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get maintenance mode status
     */
    public function status()
    {
        $isEnabled = File::exists(storage_path('framework/down'));
        
        if ($isEnabled) {
            $data = json_decode(File::get(storage_path('framework/down')), true);
        }
        
        return response()->json([
            'success' => true,
            'enabled' => $isEnabled,
            'data' => $isEnabled ? $data : null
        ]);
    }
    
    /**
     * Update maintenance settings
     */
    public function update(Request $request)
    {
        try {
            if (!File::exists(storage_path('framework/down'))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maintenance mode is not enabled'
                ], 400);
            }
            
            $message = $request->input('message', 'The site is currently under maintenance. Please check back later.');
            $allowedIps = $request->input('allowed_ips', []);
            $retryAfter = $request->input('retry_after', 60);
            
            // Update the maintenance file
            File::put(storage_path('framework/down'), json_encode([
                'time' => time(),
                'message' => $message,
                'retry' => $retryAfter,
                'allowed' => $allowedIps,
            ]));
            
            // Update cache
            Cache::put('maintenance_message', $message, now()->addDays(1));
            Cache::put('maintenance_allowed_ips', $allowedIps, now()->addDays(1));
            Cache::put('maintenance_retry_after', $retryAfter, now()->addDays(1));
            
            \Log::info('Maintenance settings updated by admin');
            
            return response()->json([
                'success' => true,
                'message' => 'Maintenance settings updated successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to update maintenance settings: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update maintenance settings: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get maintenance page (for visitors)
     */
    public function showMaintenancePage()
    {
        $data = [];
        
        if (File::exists(storage_path('framework/down'))) {
            $data = json_decode(File::get(storage_path('framework/down')), true);
        }
        
        return response()->view('maintenance', $data, 503);
    }
    
    /**
     * Check if current IP is allowed during maintenance
     */
    public function isIpAllowed($ip)
    {
        if (!File::exists(storage_path('framework/down'))) {
            return true;
        }
        
        $data = json_decode(File::get(storage_path('framework/down')), true);
        $allowedIps = $data['allowed'] ?? [];
        
        // Always allow localhost
        $allowedIps[] = '127.0.0.1';
        $allowedIps[] = '::1';
        
        return in_array($ip, $allowedIps);
    }
}