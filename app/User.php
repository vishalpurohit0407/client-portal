<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Storage;
use Uuid;


class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate(4);
        });
    }

    protected $keyType = 'string';

    public $incrementing = false;

    protected $appends = [ 'user_image_url' ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','profile_img','status','zendesk_id'
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getUserImageUrlAttribute()
    {
        return (isset($this->profile_img) && Storage::disk(env('FILESYSTEM_DRIVER'))->exists($this->profile_img) ? Config('filesystems.disks.public.url').'/'.$this->profile_img : asset('assets/img/theme/defualt-user.png'));
    }
}
