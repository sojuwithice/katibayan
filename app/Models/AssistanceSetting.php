<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssistanceSetting extends Model
{
    protected $fillable = [
        'barangay_id',
        'description',
        'fb_link',
        'msgr_link'
    ];
}
