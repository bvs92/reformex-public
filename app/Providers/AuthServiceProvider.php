<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        // Post::class => PostPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Gates

        // Gate::define('update-notification', function($user, $notification){
        //     return $user->id === $notification->notifiable_id;
        // });

        /**
         * Is user id 1
         */

        //  Gate::before(function($user, $ability){
        //      if($user->isAdmin()){
        //          return true;
        //      }
        //  });

        //  Gate::define('is-one', function($user){
        //      return $user->id === 2;
        //  });

        //  Gate::define('can-update', function($user, $post){
        //      if($user->id === $post->user_id){
        //          return true;
        //      }

        //      return false;
        //  });
    }
}
