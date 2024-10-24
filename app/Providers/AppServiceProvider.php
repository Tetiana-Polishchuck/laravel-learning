<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Notifications\MailNotificationChannel;
use App\Notifications\TelegramNotificationChannel;
use App\Listeners\SendAppointmentNotification;
use App\Contracts\NotificationChannelInterface;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(NotificationChannelInterface::class, function ($app) {
            return new MailNotificationChannel();
        });

        $this->app->bind(NotificationChannelInterface::class, function ($app) {
            return new TelegramNotificationChannel();
        });

        $this->app->when(SendAppointmentNotification::class)
            ->needs('$telegramChannel')
            ->give(TelegramNotificationChannel::class);

        $this->app->when(SendAppointmentNotification::class)
            ->needs('$mailChannel')
            ->give(MailNotificationChannel::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
