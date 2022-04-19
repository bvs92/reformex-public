<?php

namespace App\Http\View\Composers;

use App\User;
use Illuminate\View\View;

class ProfileComposer {

    public $users;

    public function __construct(User $users)
    {
        $this->users = $users;
    }

    public function compose(View $view)
    {
        $view->with('count', $this->users->count());
    }
}