<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateSchedule extends Model
{
    // Sa loob ng CertificateSchedule class

// Idagdag ito para ma-mass assign
protected $fillable = [
    'event_id',
    'release_date',
    'release_time',
    'location',
];

public function event()
{
    // Ang isang schedule ay pagmamay-ari ng ISANG event
    return $this->belongsTo(Event::class);
}
}
