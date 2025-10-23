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
        'registration_title',
        'registration_description',
        'registration_open_date',
        'registration_open_time',
        'registration_close_date',
        'registration_close_time',
        'custom_fields',
        'barangay_id',
        'user_id'
    ];

    protected $casts = [
        'event_date' => 'date',
        'registration_open_date' => 'date',
        'registration_close_date' => 'date',
        'custom_fields' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }

    public function registrations()
    {
        return $this->hasMany(ProgramRegistration::class);
    }

    /**
     * Check if registration is currently open
     */
    public function isRegistrationOpen()
    {
        $now = now();
        
        // If no registration period is set, consider it always open
        if (!$this->registration_open_date && !$this->registration_close_date) {
            return true;
        }

        $openDateTime = null;
        $closeDateTime = null;

        // Create opening datetime
        if ($this->registration_open_date) {
            $openDateTime = \Carbon\Carbon::parse($this->registration_open_date);
            if ($this->registration_open_time) {
                $openDateTime->setTimeFromTimeString($this->registration_open_time);
            }
        }

        // Create closing datetime
        if ($this->registration_close_date) {
            $closeDateTime = \Carbon\Carbon::parse($this->registration_close_date);
            if ($this->registration_close_time) {
                $closeDateTime->setTimeFromTimeString($this->registration_close_time);
            }
        }

        // Check if current time is within registration period
        $isAfterOpen = !$openDateTime || $now->gte($openDateTime);
        $isBeforeClose = !$closeDateTime || $now->lte($closeDateTime);

        return $isAfterOpen && $isBeforeClose;
    }

    /**
     * Get custom registration fields
     */
    public function getCustomRegistrationFields()
    {
        return $this->custom_fields ?? [];
    }

    /**
     * Check if program has custom registration form
     */
    public function hasCustomRegistration()
    {
        return $this->registration_type === 'create' && !empty($this->custom_fields);
    }

    /**
     * Scope for programs with create registration type
     */
    public function scopeWithCreateRegistration($query)
    {
        return $query->where('registration_type', 'create');
    }

    /**
     * Scope for programs with link registration type
     */
    public function scopeWithLinkRegistration($query)
    {
        return $query->where('registration_type', 'link');
    }

    /**
     * Scope for upcoming programs
     */
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->toDateString());
    }

    /**
     * Scope for programs in a specific barangay
     */
    public function scopeInBarangay($query, $barangayId)
    {
        return $query->where('barangay_id', $barangayId);
    }
}