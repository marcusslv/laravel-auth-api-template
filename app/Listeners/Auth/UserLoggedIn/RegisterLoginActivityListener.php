<?php

namespace App\Listeners\Auth\UserLoggedIn;

use App\Events\Auth\UserLoggedIn;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterLoginActivityListener implements ShouldQueue
{
    public $queue = 'activity_log';

    /**
     * Handle the event.
     */
    public function handle(UserLoggedIn $event): void
    {
        activity()
            ->useLog('authentication')
            ->event('login_success')
            ->causedBy($event->user)
            ->log('User logged in successfully');
    }
}
