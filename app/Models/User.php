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
        'address',
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
}