<?php

namespace App\Notifications;
 
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Mail;
class WarrantyExtensions extends Notification implements ShouldQueue
{
    use Queueable;
    public $data; 
    public function __construct($data)
    {
          $this->data = $data;
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
            ->subject($this->data['subject'])
            ->line($this->data['username'])
            ->line($this->data['message'])
            ->action('View', $this->data['action']);
    }
}
