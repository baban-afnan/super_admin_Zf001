<?php

namespace App\Mail;

use App\Models\AiChat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketClosed extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;

    /**
     * Create a new message instance.
     *
     * @param AiChat $ticket
     * @return void
     */
    public function __construct(AiChat $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Support Conversation #' . $this->ticket->reference . ' Closed')
                    ->view('emails.support.closed');
    }
}
