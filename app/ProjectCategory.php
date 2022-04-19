<?php

namespace App;

use App\WorkProject;
use Illuminate\Database\Eloquent\Model;

class ProjectCategory extends Model
{
    protected $fillable = ['uuid', 'name', 'slug', 'description'];

    // Relations

    public function projects()
    {
        return $this->belongsToMany(WorkProject::class);
        // return $this->belongsToMany(WorkProject::class, 'project_category_work_project');
    }

}
