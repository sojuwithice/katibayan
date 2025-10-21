<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'user_id',
        'reference_id',
        'registration_data' // This will store all the dynamic form data
    ];

    protected $casts = [
        'registration_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get auto-filled registration data
     */
    public function getAutoFilledData()
    {
        return $this->registration_data['auto_filled'] ?? [];
    }

    /**
     * Get custom fields registration data
     */
    public function getCustomFieldsData()
    {
        return $this->registration_data['custom_fields'] ?? [];
    }

    /**
     * Get all registration data combined
     */
    public function getAllRegistrationData()
    {
        $autoFilled = $this->getAutoFilledData();
        $customFields = $this->getCustomFieldsData();
        
        return array_merge($autoFilled, $customFields);
    }
     /**
     * Get the user who marked the attendance
     */
    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by_user_id');
    }

    /**
     * Scope to get attended registrations
     */
    public function scopeAttended($query)
    {
        return $query->where('attended', true);
    }

    /**
     * Scope to get absent registrations
     */
    public function scopeAbsent($query)
    {
        return $query->where('attended', false);
    }
}