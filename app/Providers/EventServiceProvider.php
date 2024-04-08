<?php

namespace App\Providers;

use App\Events\CreateMemoAdminNotificationEvent;
use App\Events\FixMemoAdminNotificationEvent;
use App\Events\MemoFixRequestUserNotificationEvent;
use App\Events\NotTennisRelatedAdminNotificationEvent;
use App\Listeners\SendCreateMemoAdminNotificationListener;
use App\Listeners\SendFixMemoAdminNotificationListener;
use App\Listeners\SendMemoFixRequestUserNotificationListener;
use App\Listeners\SendNotTennisRelatedAdminNotificationListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        NotTennisRelatedAdminNotificationEvent::class => [
            SendNotTennisRelatedAdminNotificationListener::class,
        ],
        CreateMemoAdminNotificationEvent::class => [
            SendCreateMemoAdminNotificationListener::class,
        ],
        MemoFixRequestUserNotificationEvent::class => [
            SendMemoFixRequestUserNotificationListener::class,
        ],
        FixMemoAdminNotificationEvent::class => [
            SendFixMemoAdminNotificationListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
