<?php

namespace App\Contracts;

interface NotificationChannelInterface
{
    public function send($recipient, $appointment);
}
