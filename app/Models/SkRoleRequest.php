<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkRoleRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
    ];

    // Relasyon papunta sa User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}