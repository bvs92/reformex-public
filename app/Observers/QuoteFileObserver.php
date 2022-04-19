<?php

namespace App\Observers;

use App\QuoteFile;

class QuoteFileObserver
{
    /**
     * Handle the quote file "created" event.
     *
     * @param  \App\QuoteFile  $quoteFile
     * @return void
     */
    public function created(QuoteFile $quoteFile)
    {
        //
    }

    /**
     * Handle the quote file "updated" event.
     *
     * @param  \App\QuoteFile  $quoteFile
     * @return void
     */
    public function updated(QuoteFile $quoteFile)
    {
        //
    }

    /**
     * Handle the quote file "deleted" event.
     *
     * @param  \App\QuoteFile  $quoteFile
     * @return void
     */
    public function deleted(QuoteFile $quoteFile)
    {
        // dd('aici suntem');
        $pathToFile = public_path() . '/storage\/quotes\/' . $quoteFile->name;
        if(file_exists($pathToFile)){
            unlink($pathToFile);
        }
    }

    /**
     * Handle the quote file "restored" event.
     *
     * @param  \App\QuoteFile  $quoteFile
     * @return void
     */
    public function restored(QuoteFile $quoteFile)
    {
        //
    }

    /**
     * Handle the quote file "force deleted" event.
     *
     * @param  \App\QuoteFile  $quoteFile
     * @return void
     */
    public function forceDeleted(QuoteFile $quoteFile)
    {
        //
    }
}
