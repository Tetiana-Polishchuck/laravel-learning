<?php


namespace App\Notifications;
use App\Contracts\NotificationChannelInterface;
use App\Mail\AppointmentChangedMail;
use Illuminate\Support\Facades\Mail;

class MailNotificationChannel implements NotificationChannelInterface{
    public function send($recipient, $appointment)
    {
        Mail::to($recipient->email)->send(new AppointmentChangedMail($appointment));
    }
}