<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkContactDetail extends Model
{
    use HasFactory;

    protected $table = 'sk_contact_details'; // Itugma sa table name

    protected $fillable = [
        'barangay_id',
        'assistance_description',
        'assistance_fb_link',
        'assistance_msgr_link',
    ];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }
}