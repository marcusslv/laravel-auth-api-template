<?php

namespace Tests\Feature\AuthTest\UserLoggedOutTest;

use App\Events\Auth\UserLoggedOut;
use App\Events\Auth\UserLoggedOutEvent;
use App\Listeners\Auth\UserLoggedOut\RegisterLogoutActivityListener;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class RegisterLogoutActivityListenerTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_activity_logout_is_save(): void
    {
        $user = User::factory()->create();
        $event = new UserLoggedOutEvent($user);
        $listener = new RegisterLogoutActivityListener();

        $listener->handle($event);

        $lastActivity = Activity::all()->last();

        $this->assertEquals('authentication', $lastActivity->log_name);
        $this->assertEquals('logout_success', $lastActivity->event);
        $this->assertEquals($user->id, $lastActivity->causer_id);
        $this->assertEquals('App\Models\User', $lastActivity->causer_type);
        $this->assertEquals('User logged out successfully', $lastActivity->description);
    }
}
