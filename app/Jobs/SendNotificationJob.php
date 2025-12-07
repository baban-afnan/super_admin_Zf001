<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Mail\SendNotification;
use Illuminate\Support\Facades\Mail;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $subject;
    protected $message;
    protected $imagePath;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $subject, $message, $imagePath = null)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->message = $message;
        $this->imagePath = $imagePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->user->email)->send(new SendNotification($this->subject, $this->message, $this->imagePath));
    }
}
