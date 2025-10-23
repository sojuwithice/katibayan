<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'barangay_id',
        'question',
        'options',
        'end_date',
        'committee',
        'is_active'
    ];

    protected $casts = [
        'options' => 'array',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }

    public function votes()
    {
        return $this->hasMany(PollVote::class);
    }

    // Get vote counts for each option
    public function getVoteCounts()
    {
        $counts = [];
        foreach ($this->options as $index => $option) {
            $counts[$index] = $this->votes()->where('option_index', $index)->count();
        }
        return $counts;
    }

    // Get total votes
    public function getTotalVotes()
    {
        return $this->votes()->count();
    }

    // Check if user has voted
    public function userHasVoted($userId)
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }

    // Get user's vote
    public function getUserVote($userId)
    {
        $vote = $this->votes()->where('user_id', $userId)->first();
        return $vote ? $vote->option_index : null;
    }
}