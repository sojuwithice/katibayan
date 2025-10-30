<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\CertificateRequest; // <-- BINURA ITO, hindi mo kailangang i-use 'yung sarili niyang class

class CertificateRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'event_id',
        'program_id',    // <-- 1. IDAGDAG ITO
        'status',
        'request_count', // <-- 2. IDAGDAG DIN ITO (ginagamit 'to sa controller mo)
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Kunin 'yung program na related sa request.
     */
    public function program() // <-- 3. IDAGDAG ITONG BUONG FUNCTION
    {
        return $this->belongsTo(Program::class);
    }
}