<?php

namespace App\Providers;

use App\ClientMessageFile;
use App\Observers\ClientMessageFileObserver;
use App\Observers\ProfileObserver;
use App\Observers\QuoteFileObserver;
use App\Observers\QuoteObserver;
use App\Profile;
use App\Quote;
use App\QuoteFile;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', 'App\Http\View\Composers\ProfileComposer');
        // View::share('name', 'Valentin');

        Profile::observe(ProfileObserver::class);
        QuoteFile::observe(QuoteFileObserver::class);
        ClientMessageFile::observe(ClientMessageFileObserver::class);
        Quote::observe(QuoteObserver::class);
        // WorkProjectPhoto::observe(WorkProjectPhotoObserver::class);

    }
}
