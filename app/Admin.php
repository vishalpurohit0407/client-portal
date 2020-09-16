<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use App\Notifications\ResetPassword as ResetPasswordNotification;

class Admin extends Authenticatable
{
    use Notifiable;
    
    protected $guard = 'admin';

    /**
     * The table associated with the model.
     *
     * @var string
     */
        protected $table = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'role', 'status','profile_img'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Sends the password reset notification.
     *
     * @param  string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        //$this->notify(new ResetPasswordNotification($token));
        $this->notify(new CustomPassword($token));
    }
}

class CustomPassword extends ResetPassword
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Reset Password Request')
            ->line('We are sending this email because we recieved a forgot password request.')
            ->action('Reset Password', url(route('admin.password.token',[ $this->token,$notifiable->email], false)))
            ->line('If you did not request a password reset, no further action is required. Please contact us if you did not submit this request.');
    }
}
