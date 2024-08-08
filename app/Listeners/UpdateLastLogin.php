<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;

class UpdateLastLogin
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\UserLoggedIn  $event
     * @return void
     */
    public function handle(UserLoggedIn $event)
    {
        $user = $event->user;
        $user->last_login = now();
        $user->save();
    }
}
