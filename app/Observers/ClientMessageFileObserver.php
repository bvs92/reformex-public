<?php

namespace App\Observers;

use App\ClientMessageFile;

class ClientMessageFileObserver
{
    /**
     * Handle the client message file "created" event.
     *
     * @param  \App\ClientMessageFile  $clientMessageFile
     * @return void
     */
    public function created(ClientMessageFile $clientMessageFile)
    {
        //
    }

    /**
     * Handle the client message file "updated" event.
     *
     * @param  \App\ClientMessageFile  $clientMessageFile
     * @return void
     */
    public function updated(ClientMessageFile $clientMessageFile)
    {
        //
    }

    /**
     * Handle the client message file "deleted" event.
     *
     * @param  \App\ClientMessageFile  $clientMessageFile
     * @return void
     */
    public function deleted(ClientMessageFile $clientMessageFile)
    {
        $pathToFile = public_path() . '/storage\/client_messages\/' . $clientMessageFile->name;
        if(file_exists($pathToFile)){
            unlink($pathToFile);
        }
    }

    /**
     * Handle the client message file "restored" event.
     *
     * @param  \App\ClientMessageFile  $clientMessageFile
     * @return void
     */
    public function restored(ClientMessageFile $clientMessageFile)
    {
        //
    }

    /**
     * Handle the client message file "force deleted" event.
     *
     * @param  \App\ClientMessageFile  $clientMessageFile
     * @return void
     */
    public function forceDeleted(ClientMessageFile $clientMessageFile)
    {
        //
    }
}
