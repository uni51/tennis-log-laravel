<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Factory;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('firebase', function () {
            return (new Factory())
                ->withServiceAccount(file_get_contents(storage_path(env('FIREBASE_CREDENTIALS'))))
                ->withProjectId(env('FIREBASE_PROJECT'));
        });
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [\Kreait\Laravel\Firebase\Facades\Firebase::class];
    }
}
