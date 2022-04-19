<?php

namespace App\Providers;

use App\ClientMessageFile;
use App\DemandAttachment;
use App\DemandFile;
use App\Observers\AttachmentFileObserver;
use App\Observers\ClientMessageFileObserver;
use App\Observers\DemandFileObserver;
use App\Observers\QuoteFileObserver;
use App\Observers\UserObserver;
use App\Observers\WorkProjectPhotoObserver;
use App\QuoteFile;
use App\User;
use App\WorkProjectPhoto;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            ListenToTheRegister::class,
        ],

        'App\Events\DemandRegistered' => [
            'App\Listeners\DemandRegisteredListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // Demand::observe(RegisterDemandObserver::class);
        DemandFile::observe(DemandFileObserver::class);
        DemandAttachment::observe(AttachmentFileObserver::class);
        QuoteFile::observe(QuoteFileObserver::class);
        ClientMessageFile::observe(ClientMessageFileObserver::class);
        User::observe(UserObserver::class);
        WorkProjectPhoto::observe(WorkProjectPhotoObserver::class);
    }
}
