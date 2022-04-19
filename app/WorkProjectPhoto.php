<?php

namespace App;

use App\User;
use App\WorkProject;
use Illuminate\Database\Eloquent\Model;

class WorkProjectPhoto extends Model
{
    protected $fillable = ['work_project_id', 'user_id', 'name', 'extension', 'mime_type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function work_project()
    {
        return $this->belongsTo(WorkProject::class);
    }
}
