<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Uuid;

class CmsPage extends Authenticatable
{
    use Notifiable;
    protected $table = 'cms_pages';
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate(4);
        });
    }
    
    protected $keyType = 'string';

    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'url_slug', 'content', 'status'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

}
