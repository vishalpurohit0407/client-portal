<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Uuid;
use Storage;

class GuideStepMedia extends Authenticatable
{
    protected $table = 'guide_step_media';
    /**protected $table = 'category';
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate(4);
        });
    }
    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'step_id', 'step_key', 'media'
    ];

    protected $appends = [ 'media_url' ];

    public function getMediaUrlAttribute()
    {
        return (isset($this->media) && Storage::disk(env('FILESYSTEM_DRIVER'))->exists($this->media) ? Config('filesystems.disks.public.url').'/'.$this->media : asset('assets/img/theme/no-image.jpg'));
    }
}
