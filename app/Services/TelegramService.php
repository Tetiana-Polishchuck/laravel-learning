<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;

class TelegramService{
    protected $token;
    protected $chatId;

    public function __construct()
    {
        $this->token = config('services.telegram.token');
        $this->chatId = config('services.telegram.chat_id');

        
    }
    public function sendMessage($message)
    {
        Log::info('sendMessage', [$this->token, $this->chatId]);
        file_get_contents("https://api.telegram.org/bot{$this->token}/sendMessage?chat_id={$this->chatId}&text=" . urlencode($message));
    }

    public function checkAvailability(){
        // Перевіряє, чи обидві властивості token та chatId існують у об'єкті
        return $this?->token && $this?->chatId;
    }
}