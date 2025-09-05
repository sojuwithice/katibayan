<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkOfficial extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'position',
        'term_start',
        'term_end',
        'oath_certificate_path',
        
    ];

  
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}