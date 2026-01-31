<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'program_id',
        'ratings',
        'comments',
        'submitted_at',
    ];

    protected $casts = [
        'ratings' => 'array',
        'submitted_at' => 'datetime',
    ];

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with Event
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
      public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    private function locked()
{
    return Evaluation::whereNotNull('submitted_at')->exists();
}
}