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
        'registration_data',
        'attended', // ADD THIS
        'attended_at', // ADD THIS
        'attendance_days', // ADD THIS
        'marked_by_user_id' // ADD THIS
    ];

    protected $casts = [
        'registration_data' => 'array',
        'attendance_days' => 'array', // ADD THIS CAST
        'attended' => 'boolean', // ADD THIS CAST
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

    /**
     * Calculate present days from attendance_days
     */
    public function getPresentDaysAttribute()
    {
        if (!$this->attendance_days) {
            return 0;
        }
        
        return count(array_filter($this->attendance_days, function($attended) {
            return $attended === true || $attended === 'true';
        }));
    }

    /**
     * Update attendance based on daily attendance
     */
    public function updateAttendanceFromDaily()
    {
        $presentDays = $this->present_days;
        $totalDays = $this->program ? $this->program->number_of_days : 1;
        
        $this->update([
            'attended' => $presentDays > 0,
            'attended_at' => $presentDays > 0 ? now() : null
        ]);
    }
}