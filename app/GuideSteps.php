<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Uuid;
use Storage;
use App\GuideStepMedia;

class GuideSteps extends Authenticatable
{
    protected $table = 'guide_steps';
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
        'guide_id', 'title', 'description', 'step_key', 'video_type', 'video_media'
    ];

    public function media()
    {
        return $this->hasMany('App\GuideStepMedia', 'step_id','id');
    }
}
