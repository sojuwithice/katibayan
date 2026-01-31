<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationQuestion extends Model
{
    protected $fillable = [
        'question_text',
        'order',
        'is_active',
        'is_default'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * Check if this question has any evaluations (by checking if any evaluation has a rating for this question)
     */
    public function hasEvaluations()
    {
        // Check if any evaluation has a rating for this question
        return Evaluation::whereNotNull('ratings')
            ->whereNotNull('submitted_at')
            ->get()
            ->filter(function($evaluation) {
                $ratings = $evaluation->ratings ?? [];
                return isset($ratings[$this->id]) || isset($ratings["q{$this->id}"]);
            })
            ->count() > 0;
    }

    /**
     * Get the evaluations count for this question
     */
    public function getEvaluationsCountAttribute()
    {
        $count = 0;
        
        Evaluation::whereNotNull('ratings')
            ->whereNotNull('submitted_at')
            ->each(function($evaluation) use (&$count) {
                $ratings = $evaluation->ratings ?? [];
                
                // Check both formats: by ID and by "q{id}"
                if (isset($ratings[$this->id]) || isset($ratings["q{$this->id}"])) {
                    $count++;
                }
            });
            
        return $count;
    }

    /**
     * Get all evaluations that have rated this question
     */
    public function evaluations()
    {
        // This is a custom relationship since we're using JSON storage
        $evaluationIds = [];
        
        Evaluation::whereNotNull('ratings')
            ->whereNotNull('submitted_at')
            ->each(function($evaluation) use (&$evaluationIds) {
                $ratings = $evaluation->ratings ?? [];
                
                if (isset($ratings[$this->id]) || isset($ratings["q{$this->id}"])) {
                    $evaluationIds[] = $evaluation->id;
                }
            });
            
        return Evaluation::whereIn('id', $evaluationIds);
    }

    /**
     * Scope for active questions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for default questions
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope ordered by order field
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}