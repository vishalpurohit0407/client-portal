<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Uuid;
use Config;
use Storage;

class Guide extends Authenticatable
{
    protected $table = 'guide';
    /**protected $table = 'guide';
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
        'main_title', 'main_image', 'description', 'type','duration','duration_type','difficulty','cost','tags','introduction','introduction_video_type','introduction_video_link', 'guide_type', 'status'
    ];

    protected $appends = [ 'main_image_url', 'completion_guide_count' ];

    public function guide_category()
    {
        return $this->hasMany('App\Guidecategory', 'guide_id','id');
    }

    public function getMainImageUrlAttribute()
    {
        return (isset($this->main_image) && Storage::disk(env('FILESYSTEM_DRIVER'))->exists($this->main_image) ? Config('filesystems.disks.public.url').'/'.$this->main_image : asset('assets/img/theme/no-image.jpg'));
    }

    public function getCompletionGuideCountAttribute()
    {
        return \App\GuideCompletion::where('guide_id', $this->id)->count();
    }

    public function guide_step()
    {
        return $this->hasMany('App\GuideSteps', 'guide_id','id')->orderBy('step_no', 'asc');
    }
    public function guide_flowchart()
    {
        return $this->hasOne('App\GuideFlowchart', 'guide_id','id');
    }
}
