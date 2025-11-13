<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemFeedback extends Model
{
    use HasFactory;

    protected $table = 'system_feedbacks';

    protected $fillable = [
        'user_id',
        'type',
        'message',
        'rating',
        'status'
    ];

    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that submitted the feedback.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include pending feedback.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include feedback of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include feedback with ratings.
     */
    public function scopeWithRating($query)
    {
        return $query->whereNotNull('rating');
    }

    /**
     * Scope a query to order by latest first.
     */
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get the feedback type with proper formatting.
     */
    public function getTypeFormattedAttribute()
    {
        return ucfirst($this->type);
    }

    /**
     * Get the star rating as HTML stars.
     */
    public function getStarRatingAttribute()
    {
        if (!$this->rating) return 'No rating';

        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '★';
            } else {
                $stars .= '☆';
            }
        }
        return $stars;
    }

    /**
     * Get HTML star rating with Font Awesome icons.
     */
    public function getHtmlStarRatingAttribute()
    {
        if (!$this->rating) return '<span class="no-rating">No rating</span>';

        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<i class="fa-solid fa-star"></i>';
            } else {
                $stars .= '<i class="fa-regular fa-star"></i>';
            }
        }
        return $stars;
    }

    /**
     * Get formatted created date.
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('m/d/Y');
    }

    /**
     * Get formatted created time.
     */
    public function getFormattedTimeAttribute()
    {
        return $this->created_at->format('g:i A');
    }

    /**
     * Get user account number.
     */
    public function getUserAccountNumberAttribute()
    {
        return $this->user ? $this->user->account_number : 'N/A';
    }

    /**
     * Get user full name.
     */
    public function getUserFullNameAttribute()
    {
        if (!$this->user) return 'Unknown User';
        
        $name = $this->user->given_name . ' ' . $this->user->last_name;
        if ($this->user->middle_name) {
            $name .= ' ' . $this->user->middle_name;
        }
        if ($this->user->suffix) {
            $name .= ' ' . $this->user->suffix;
        }
        return $name;
    }

    /**
     * Get truncated message for preview.
     */
    public function getTruncatedMessageAttribute()
    {
        $length = 100;
        if (strlen($this->message) <= $length) {
            return $this->message;
        }
        return substr($this->message, 0, $length) . '...';
    }

    /**
     * Get CSS class for feedback type.
     */
    public function getTypeClassAttribute()
    {
        $classes = [
            'suggestion' => 'suggestion',
            'bug' => 'bug',
            'appreciation' => 'appreciation',
            'others' => 'others'
        ];
        
        return $classes[$this->type] ?? 'others';
    }

    /**
     * Check if feedback has rating.
     */
    public function getHasRatingAttribute()
    {
        return !is_null($this->rating);
    }

    /**
     * Get rating percentage for charts.
     */
    public function getRatingPercentageAttribute()
    {
        if (!$this->rating) return 0;
        return ($this->rating / 5) * 100;
    }
}