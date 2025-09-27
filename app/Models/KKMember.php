<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KKMember extends Model
{
    use HasFactory;

    protected $table = 'kk_members'; 

    protected $fillable = [
        'user_id',
        'position',
        'appointment_date',
        'barangay_indigency_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
