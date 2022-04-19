<?php

namespace App\Observers;

use App\Quote;

class QuoteObserver
{
    /**
     * Handle the quote "created" event.
     *
     * @param  \App\Quote  $quote
     * @return void
     */
    public function created(Quote $quote)
    {
        //
    }

    /**
     * Handle the quote "updated" event.
     *
     * @param  \App\Quote  $quote
     * @return void
     */
    public function updated(Quote $quote)
    {
        //
    }

    /**
     * Handle the quote "deleted" event.
     *
     * @param  \App\Quote  $quote
     * @return void
     */
    public function deleted(Quote $quote)
    {
        if($quote->files && $quote->files->count() > 0){
            foreach($quote->files as $file){
                // $pathToFile = public_path() . '/storage\/quotes\/' . $file->name;
                // if(file_exists($pathToFile)){
                //     unlink($pathToFile);
                // }

                $file->delete();
            }
        }
    }

    /**
     * Handle the quote "restored" event.
     *
     * @param  \App\Quote  $quote
     * @return void
     */
    public function restored(Quote $quote)
    {
        //
    }

    /**
     * Handle the quote "force deleted" event.
     *
     * @param  \App\Quote  $quote
     * @return void
     */
    public function forceDeleted(Quote $quote)
    {
        //
    }
}
