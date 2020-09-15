<?php

namespace App\Notifications;
 
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Mail;
class Register extends Notification implements ShouldQueue
{
    use Queueable;
    public $token; 
    public function __construct($token)
    {
          $this->token = $token;
    }
    public function via($notifiable)
    {
          return ['mail'];
    }
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Reset Password Notification")
            ->line('Hi Admin')
            ->line('We are sending this email because we recieved a forgot password request.')
            ->action('Reset Password', route('admin.password.token', $this->token, false))
            ->line('If you did not request a password reset, no further action is required. Please contact us if you did not submit this request.');

    }
}
