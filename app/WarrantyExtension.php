<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Uuid;
use Storage;
use App\Notifications\WarrantyExtensions as WarrantyExtensionsNotification;
use Mail;
use Config;
use Notification;
use Illuminate\Notifications\Notifiable;

class WarrantyExtension extends Model
{
    use Notifiable;

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate(4);
        });
    }

	protected $table = 'warranty_extension';
    protected $keyType = 'string';

    public $incrementing = false;

    protected $appends = [ 'image_by_user', 'image_by_admin' ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_key', 'warranty_valid_date', 'picture_by_admin','picture_by_user','voltage','temperat','thing_on','do_something','status','vid_link_type','vid_link_url','admin_vid_link_type','admin_vid_link_url'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    public function getImageByAdminAttribute()
    {
        return (isset($this->picture_by_admin) && Storage::disk(env('FILESYSTEM_DRIVER'))->exists($this->picture_by_admin) ? Config('filesystems.disks.public.url').'/'.$this->picture_by_admin : asset('assets/img/no_img.png'));
    }

    public function getImageByUserAttribute()
    {
        return (isset($this->picture_by_user) && Storage::disk(env('FILESYSTEM_DRIVER'))->exists($this->picture_by_user) ? Config('filesystems.disks.public.url').'/'.$this->picture_by_user : asset('assets/img/no_img.png'));
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id','user_id');
    }

    public static function sendWarrantyNotification($email, $name, $message, $action)
    {
        $data = array(
            'username' => $name,
            'message' => $message,
            'action' => $action,
            'subject' => 'Warranty Extension'
        );

        return Notification::route('mail', $email)->notify(new WarrantyExtensionsNotification($data));
    }
}
