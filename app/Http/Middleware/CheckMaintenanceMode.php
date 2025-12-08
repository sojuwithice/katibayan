<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\MaintenanceController;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if maintenance mode is enabled
        if (File::exists(storage_path('framework/down'))) {
            $data = json_decode(File::get(storage_path('framework/down')), true);
            
            // Get client IP
            $clientIp = $request->ip();
            
            // Check if IP is allowed
            $allowedIps = $data['allowed'] ?? [];
            $allowedIps[] = '127.0.0.1'; // Always allow localhost
            $allowedIps[] = '::1'; // IPv6 localhost
            
            // Check if current route is for admins (allow access)
            $isAdminRoute = $request->is('admin/*') || 
                           $request->is('login') || 
                           $request->routeIs('login.*');
            
            // Allow access if IP is in allowed list or is admin route
            if (in_array($clientIp, $allowedIps) || $isAdminRoute) {
                return $next($request);
            }
            
            // If it's an API request, return JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $data['message'] ?? 'Service Unavailable',
                    'retry' => $data['retry'] ?? null
                ], 503);
            }
            
            // For web requests, show maintenance page
            return response()->view('maintenance', $data, 503);
        }
        
        return $next($request);
    }
}