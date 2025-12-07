<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectLine;
    public $content;
    public $imagePath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $content, $imagePath = null)
    {
        $this->subjectLine = $subject;
        $this->content = $content;
        $this->imagePath = $imagePath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subjectLine)
                    ->view('emails.notification')
                    ->with(['mail_data' => $this->content]);
    }
}
