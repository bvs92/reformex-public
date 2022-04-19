<?php

namespace App\Observers;

use App\DemandAttachment;

class AttachmentFileObserver
{
    /**
     * Handle the demand attachment "created" event.
     *
     * @param  \App\DemandAttachment  $demandAttachment
     * @return void
     */
    public function created(DemandAttachment $demandAttachment)
    {
        //
    }

    /**
     * Handle the demand attachment "updated" event.
     *
     * @param  \App\DemandAttachment  $demandAttachment
     * @return void
     */
    public function updated(DemandAttachment $demandAttachment)
    {
        //
    }

    /**
     * Handle the demand attachment "deleted" event.
     *
     * @param  \App\DemandAttachment  $demandAttachment
     * @return void
     */
    public function deleted(DemandAttachment $demandAttachment)
    {
        $pathToFile = storage_path('app/public') . '/demands/' . $demandAttachment->name;
        // $pathToFile = public_path() . '/storage\/demands\/' . $demandAttachment->name;
        if (file_exists($pathToFile)) {
            unlink($pathToFile);
        }
    }

    /**
     * Handle the demand attachment "restored" event.
     *
     * @param  \App\DemandAttachment  $demandAttachment
     * @return void
     */
    public function restored(DemandAttachment $demandAttachment)
    {
        //
    }

    /**
     * Handle the demand attachment "force deleted" event.
     *
     * @param  \App\DemandAttachment  $demandAttachment
     * @return void
     */
    public function forceDeleted(DemandAttachment $demandAttachment)
    {
        //
    }
}
