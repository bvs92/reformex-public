<?php

namespace App\Observers;

use App\DemandFile;
use Illuminate\Support\Facades\Storage;

class DemandFileObserver
{
    /**
     * Handle the demand file "created" event.
     *
     * @param  \App\DemandFile  $demandFile
     * @return void
     */
    public function created(DemandFile $demandFile)
    {
        //
    }

    /**
     * Handle the demand file "updated" event.
     *
     * @param  \App\DemandFile  $demandFile
     * @return void
     */
    public function updated(DemandFile $demandFile)
    {
        //
    }

    /**
     * Handle the demand file "deleted" event.
     *
     * @param  \App\DemandFile  $demandFile
     * @return void
     */
    public function deleted(DemandFile $demandFile)
    {

        $pathToFile = 'uploads/demands/' . $demandFile->name;
        if (Storage::disk('do_spaces')->exists($pathToFile)) {
            Storage::disk('do_spaces')->delete($pathToFile);
        }

        // $pathToFile = storage_path('app/public') . '/demands/' . $demandFile->name;
        // if (file_exists($pathToFile)) {
        //     unlink($pathToFile);
        // }
    }

    /**
     * Handle the demand file "restored" event.
     *
     * @param  \App\DemandFile  $demandFile
     * @return void
     */
    public function restored(DemandFile $demandFile)
    {
        //
    }

    /**
     * Handle the demand file "force deleted" event.
     *
     * @param  \App\DemandFile  $demandFile
     * @return void
     */
    public function forceDeleted(DemandFile $demandFile)
    {
        //
    }
}
