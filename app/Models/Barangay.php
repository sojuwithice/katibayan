<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'city_id'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function puroks()
    {
        return $this->hasMany(Purok::class);
    }
     public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function organizationalCharts()
    {
        return $this->hasMany(OrganizationalChart::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
