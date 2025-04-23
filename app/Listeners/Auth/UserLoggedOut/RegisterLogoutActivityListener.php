<?php

namespace App\Listeners\Auth\UserLoggedOut;

use App\Events\Auth\UserLoggedOutEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterLogoutActivityListener implements ShouldQueue
{
    public $queue = 'activity_log';

    /**
     * Handle the event.
     */
    public function handle(UserLoggedOutEvent $event): void
    {
        activity()
            ->useLog('authentication')
            ->event('logout_success')
            ->causedBy($event->user)
            ->log('User logged out successfully');
    }
}
