<?php

namespace App\Listeners;

use App\Events\AppointmentChanged;
use Illuminate\Support\Facades\Log;
use App\Contracts\NotificationChannelInterface;
use App\Services\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;


class SendAppointmentNotification implements ShouldQueue
{
    protected $telegramChannel;
    protected $mailChannel;

    protected $telegramService;
    
    /**
     * Create the event listener.
     */
    public function __construct(NotificationChannelInterface $telegramChannel, NotificationChannelInterface $mailChannel)
    {
        $this->telegramChannel = $telegramChannel;
        $this->mailChannel = $mailChannel;    
    }

    /**
     * Handle the event.
     */
    public function handle(AppointmentChanged $event): void
    {
        Log::info(message:'handle');
        $appointment = $event->appointment;
        $doctor = $appointment->doctor;


        $notificationChannel = $this->determineChannel();


        $notificationChannel->send($doctor, $appointment);
    }


    protected function determineChannel(): NotificationChannelInterface
    {
        $this->telegramService = new TelegramService();
        $res = $this->telegramService->checkAvailability();
        if($res){
            return $this->telegramChannel;
        } else{
            return $this->mailChannel;
        }
               
    }
}
