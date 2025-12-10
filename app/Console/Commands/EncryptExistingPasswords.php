<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class EncryptExistingPasswords extends Command
{
    protected $signature = 'passwords:encrypt-existing';
    protected $description = 'Encrypt existing plain text default passwords';

    public function handle()
    {
        // First, check if column needs to be resized
        $columnInfo = DB::select("
            SELECT CHARACTER_MAXIMUM_LENGTH 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_NAME = 'users' 
            AND COLUMN_NAME = 'default_password'
        ")[0] ?? null;
        
        if ($columnInfo && $columnInfo->CHARACTER_MAXIMUM_LENGTH < 500) {
            $this->error('Column is too small! Run migration first:');
            $this->line('php artisan make:migration increase_default_password_length --table=users');
            $this->line('Then update default_password column to TEXT type.');
            return;
        }

        $users = User::whereNotNull('default_password')->get();
        
        $this->info("Encrypting {$users->count()} existing passwords...");
        
        $encryptedCount = 0;
        $skippedCount = 0;
        
        foreach ($users as $user) {
            $currentPassword = $user->getRawOriginal('default_password') ?? $user->default_password;
            
            if (empty($currentPassword)) {
                $this->line("User {$user->id}: Empty password, skipping");
                $skippedCount++;
                continue;
            }
            
            // Check if already encrypted
            try {
                Crypt::decryptString($currentPassword);
                $this->line("User {$user->id}: Already encrypted, skipping");
                $skippedCount++;
                continue;
            } catch (\Exception $e) {
                // Not encrypted, proceed
            }
            
            try {
                // Encrypt the password
                $encryptedPassword = Crypt::encryptString($currentPassword);
                
                // Update directly using DB query to avoid model mutator
                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'default_password' => $encryptedPassword,
                        'updated_at' => now()
                    ]);
                
                $encryptedCount++;
                $this->info("✓ User {$user->id}: Password encrypted successfully");
                
                // Verify it worked
                $encryptedLength = strlen($encryptedPassword);
                $this->line("  Encrypted length: {$encryptedLength} characters");
                
            } catch (\Exception $e) {
                $this->error("✗ User {$user->id}: Failed - " . $e->getMessage());
            }
        }
        
        $this->newLine();
        $this->info("Encryption Summary:");
        $this->info("Total processed: " . ($encryptedCount + $skippedCount));
        $this->info("Successfully encrypted: {$encryptedCount}");
        $this->info("Skipped (already encrypted/empty): {$skippedCount}");
        
        if ($encryptedCount > 0) {
            $this->newLine();
            $this->warn("⚠️ IMPORTANT: Verify encryption worked:");
            $this->line("1. Check a user's encrypted password:");
            $this->line("   SELECT id, account_number, LENGTH(default_password) as len FROM users LIMIT 1;");
            $this->line("2. Test login with one account");
            $this->line("3. Check email sending still works");
        }
    }
}