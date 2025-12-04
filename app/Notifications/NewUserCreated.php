<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserCreated extends Notification
{
    use Queueable;

    public $password;

    /**
     * Create a new notification instance.
     */
    public function __construct($password)
    {
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
     {
         return (new MailMessage)
        ->subject('Welcome to Arewa Smart – Your Account Is Ready!')
        ->greeting('Hello ' . $notifiable->first_name . ',')
        ->line('Your Arewa Smart account has been successfully created.')
        ->line('You can now log in and start enjoying secure, fast, and reliable digital services — including BVN modification, airtime and data purchase, bill payments, and more. No extra charges, no VAT, and no hassle!')
        ->line('Here are your login details:')
        ->line('Email: ' . $notifiable->email)
        ->line('Password: ' . $this->password)
        ->action('Login to Your Account', 'https://arewasmart.com.ng/login')
        ->line('For your security, please change your password after your first login.')
        ->line('Welcome to a smarter way to manage your digital services!');
        
          }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
