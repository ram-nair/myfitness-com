<?php

namespace App\Providers;

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
        ],
        'App\Events\TwilioRegister' => [
            'App\Listeners\twilioRegisteration',
        ],
        'App\Events\TwilioPush' => [
            'App\Listeners\twilioPushNotification',
        ],
        'App\Events\TwilioSms' => [
            'App\Listeners\twilioSendSms',
        ],
        'App\Events\LoginEvent' => [
            'App\Listeners\LoginEventListener',
        ],
        'App\Events\LogoutEvent' => [
            'App\Listeners\LogoutEventListener',
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

        //
    }
}
