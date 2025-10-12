<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * App\Models\Event
 *
 * @property int $id
 * @property int $user_id
 * @property int $barangay_id
 * @property string $title
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $event_date
 * @property string $event_time
 * @property string $location
 * @property string $category
 * @property string|null $image
 * @property string $published_by
 * @property string $status
 * @property bool $is_launched
 * @property string|null $passcode
 *
 * @property-read string|null $formatted_time
 * @property-read string|null $event_date_time
 */
class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'event_time',
        'location',
        'category',
        'image',
        'published_by',
        'status',
        'is_launched',
        'passcode',
        'user_id',
        'barangay_id' // ADDED THIS LINE
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_launched' => 'boolean',
    ];

    /**
     * Accessor to format time for display.
     *
     * @return string|null
     */
    public function getFormattedTimeAttribute(): ?string
    {
        try {
            if (!$this->event_time) {
                return null;
            }

            // Already formatted with AM/PM
            if (stripos($this->event_time, 'AM') !== false || stripos($this->event_time, 'PM') !== false) {
                return $this->event_time;
            }

            // HH:MM:SS → 12-hour
            if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $this->event_time)) {
                return Carbon::createFromFormat('H:i:s', $this->event_time)->format('h:i A');
            }

            // HH:MM → 12-hour
            if (preg_match('/^\d{2}:\d{2}$/', $this->event_time)) {
                return Carbon::createFromFormat('H:i', $this->event_time)->format('h:i A');
            }

            return $this->event_time; // fallback
        } catch (\Exception $e) {
            return $this->event_time; // fallback if invalid
        }
    }

    /**
     * Accessor to get full datetime.
     *
     * @return string|null
     */
    public function getEventDateTimeAttribute(): ?string
    {
        return $this->event_date
            ? $this->event_date->format('F j, Y') . ' | ' . $this->formatted_time
            : null;
    }

    /**
     * Update status based on date and launch state.
     *
     * @return void
     */
    public function updateStatus(): void
    {
        if (!$this->event_date) {
            return;
        }

        if ($this->event_date->isPast() && !$this->is_launched) {
            $this->status = 'completed';
        } elseif ($this->is_launched) {
            $this->status = 'ongoing';
        } else {
            $this->status = 'upcoming';
        }

        $this->save();
    }
   
    /**
     * Get the evaluations for this event
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'event_id');
    }

    /**
     * Get the attendances for this event
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'event_id');
    }

    /**
     * Get the user who created this event
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the barangay of the user who created this event
     */
    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }
}