<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute; // Importante para sa Carbon

class Announcement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'barangay_id',
        'title',
        'message',
        'type',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // Ito ay para siguradong date object (Carbon) ang expires_at
        'expires_at' => 'datetime', 
    ];

    /**
     * Optional: Define the relationship to the Barangay model.
     * Palitan mo ang 'Barangay::class' kung iba ang pangalan ng Barangay model mo.
     * Kung wala kang Barangay model, pwede mong alisin itong function.
     */
    public function barangay()
    {
        return $this->belongsTo(Barangay::class); 
    }

        public function event()
{
    return $this->belongsTo(Event::class);
}


    
}