<?php

declare(strict_types=1);

namespace VergilLai\LaravelGoeasy;

use Illuminate\Support\ServiceProvider;
use Illuminate\Broadcasting\BroadcastManager;

class GoeasyServiceProvider extends ServiceProvider
{
    public function boot(BroadcastManager $broadcastManager): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/goeasy.php' => config_path('goeasy.php'),
            ], 'goeasy');
        }

        $broadcastManager->extend('goeasy', function ($app) {
            return new GoeasyBroadcaster($app->make('goeasy')->pubsub());
        });
    }

    public function register(): void
    {
        $this->app->singleton('goeasy', function ($app) {
            $config = $app->make('config')->get('goeasy');
            return new GoEasy($config);
        });
    }
}