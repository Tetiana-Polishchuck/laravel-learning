<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;

class AppointmentChangedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $appointment;

    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;

    }
    public function build()
    {
        return $this->view('email.appointment_changed')
                    ->with(['appointment' => $this->appointment]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Appointment Changed Mail',
        );
    }

   
}
