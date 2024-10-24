<?php


namespace App\Notifications;
use App\Contracts\NotificationChannelInterface;
use App\Services\TelegramService;

class TelegramNotificationChannel implements NotificationChannelInterface{

    public function send($recipient, $appointment)
    {
        $telegramService = new TelegramService();
        $telegramService->sendMessage($this->formatMessage($appointment));
    }


    protected function formatMessage($appointment): string
    {
        return 'Your appointment has been changed. Details: you have an appoitment';
    }
}