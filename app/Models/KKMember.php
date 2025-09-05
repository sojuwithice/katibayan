<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KKMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'position',
        'appointment_date',
        'barangay_indigency_path', // Changed from appointment_letter_path
    
    ];

    

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}