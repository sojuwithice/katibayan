<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'barangay_id',
        'title',
        'description',
        'image',
        'services_offered',
        'location',
        'how_to_avail',
        'contact_info',
        'assistance_description',
        'assistance_fb_link',
        'assistance_msgr_link',
        'is_active'
        
    ];

    protected $casts = [
        'services_offered' => 'array',
        'is_active' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }

    public function scopeForBarangay($query, $barangayId)
    {
        return $query->where('barangay_id', $barangayId)->where('is_active', true);
    }
}