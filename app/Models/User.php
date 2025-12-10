<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'role',
        'last_name',
        'given_name',
        'middle_name',
        'suffix',
        'region_id',
        'province_id',
        'city_id',
        'barangay_id',
        'purok_zone',
        'zip_code',
        'date_of_birth',
        'sex',
        'email',
        'contact_no',
        'civil_status',
        'education',
        'work_status',
        'youth_classification',
        'sk_voter',
        'account_status',
        'password',
        'default_password', // This will now store ENCRYPTED password
        'account_number',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'default_password', 
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
    ];

    /**
     * AUTO-ENCRYPT when setting default_password
     */
    public function setDefaultPasswordAttribute($value)
    {
        if (!empty($value)) {
            // Encrypt the password
            $this->attributes['default_password'] = Crypt::encryptString($value);
        } else {
            $this->attributes['default_password'] = null;
        }
    }

    /**
     * AUTO-DECRYPT when getting default_password
     */
    public function getDefaultPasswordAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        
        try {
            // Try to decrypt
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            // If decryption fails, return as-is (for old records)
            return $value;
        }
    }

    public function skOfficial()
    {
        return $this->hasOne(SkOfficial::class);
    }

    public function kkMember()
    {
        return $this->hasOne(KKMember::class);
    }
    
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }
    
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        return asset('images/default-avatar.png');
    }
    
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function certificateRequests()
    {
        return $this->hasMany(CertificateRequest::class);
    }

    // Computed full name
    public function getNameAttribute()
    {
        $name = $this->given_name;

        if ($this->middle_name) {
            $name .= ' ' . strtoupper(substr($this->middle_name, 0, 1)) . '.';
        }

        if ($this->last_name) {
            $name .= ' ' . $this->last_name;
        }

        if ($this->suffix) {
            $name .= ' ' . $this->suffix;
        }

        return trim($name);
    }

    // Computed age (from date_of_birth)
    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    // Computed purok
    public function getPurokAttribute()
    {
        return $this->purok_zone ?? 'N/A';
    }
}