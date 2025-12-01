<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportFolder extends Model
{
    protected $fillable = ['name', 'color', 'user_id'];

    public function files()
    {
        return $this->hasMany(ReportFile::class, 'folder_id');
    }
}
