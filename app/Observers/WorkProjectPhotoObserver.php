<?php

namespace App\Observers;

use App\WorkProjectPhoto;
use Illuminate\Support\Facades\Storage;

class WorkProjectPhotoObserver
{

    public $afterCommit = true;

    /**
     * Handle the work project photo "created" event.
     *
     * @param  \App\WorkProjectPhoto  $workProjectPhoto
     * @return void
     */
    public function created(WorkProjectPhoto $workProjectPhoto)
    {
        //
    }

    /**
     * Handle the work project photo "updated" event.
     *
     * @param  \App\WorkProjectPhoto  $workProjectPhoto
     * @return void
     */
    public function updated(WorkProjectPhoto $workProjectPhoto)
    {
        //
    }

    /**
     * Handle the work project photo "deleted" event.
     *
     * @param  \App\WorkProjectPhoto  $workProjectPhoto
     * @return void
     */
    public function deleted(WorkProjectPhoto $workProjectPhoto)
    {

        // $pathToFile = storage_path('app/public') . '/work_projects/' . $workProjectPhoto->name;
        // if (file_exists($pathToFile)) {
        //     unlink($pathToFile);
        // }

        $pathToFile = 'uploads/work_projects/' . $workProjectPhoto->name;
        if (Storage::disk('do_spaces')->exists($pathToFile)) {
            Storage::disk('do_spaces')->delete($pathToFile);
        }

        // $pathToFile = public_path() . '/storage\/work_projects\/' . $workProjectPhoto->name;
        // if (file_exists($pathToFile)) {
        //     unlink($pathToFile);
        // }
    }

    /**
     * Handle the work project photo "restored" event.
     *
     * @param  \App\WorkProjectPhoto  $workProjectPhoto
     * @return void
     */
    public function restored(WorkProjectPhoto $workProjectPhoto)
    {
        //
    }

    /**
     * Handle the work project photo "force deleted" event.
     *
     * @param  \App\WorkProjectPhoto  $workProjectPhoto
     * @return void
     */
    public function forceDeleted(WorkProjectPhoto $workProjectPhoto)
    {
        //
    }
}
