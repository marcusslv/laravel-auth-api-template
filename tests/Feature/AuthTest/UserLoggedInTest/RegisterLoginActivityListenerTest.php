<?php

namespace Tests\Feature\AuthTest\UserLoggedInTest;

use App\Events\Auth\UserLoggedInEvent;
use App\Listeners\Auth\UserLoggedIn\RegisterLoginActivityListener;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class RegisterLoginActivityListenerTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_activity_login_is_save(): void
    {
        $user = User::factory()->create();
        $event = new UserLoggedInEvent($user);
        $listener = new RegisterLoginActivityListener();

        $listener->handle($event);

        $lastActivity = Activity::all()->last();

        $this->assertEquals('authentication', $lastActivity->log_name);
        $this->assertEquals('login_success', $lastActivity->event);
        $this->assertEquals($user->id, $lastActivity->causer_id);
        $this->assertEquals('App\Models\User', $lastActivity->causer_type);
        $this->assertEquals('User logged in successfully', $lastActivity->description);
    }
}
