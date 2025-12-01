<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportFile extends Model
{
    protected $fillable = ['folder_id', 'name', 'path', 'type', 'size', 'user_id'];

    public function folder()
    {
        return $this->belongsTo(ReportFolder::class, 'folder_id');
    }
}
