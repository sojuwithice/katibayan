<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'default_password',
        'account_number',
        'avatar',

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
    ];

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
}