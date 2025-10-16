<?php
// app/Models/Program.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'event_date',
        'event_time',
        'category',
        'location',
        'description',
        'display_image',
        'published_by',
        'registration_type',
        'link_source',
        'registration_description',
        'registration_open_date',
        'registration_open_time',
        'registration_close_date',
        'registration_close_time',
        'barangay_id',
        'user_id'
    ];

    protected $casts = [
        'event_date' => 'date',
        'registration_open_date' => 'date',
        'registration_close_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }
}