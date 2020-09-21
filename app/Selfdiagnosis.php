<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Uuid;

class Selfdiagnosis extends Authenticatable
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
        'main_title', 'main_image', 'description', 'type','duration','duration_type','difficulty','cost','tags','introduction','introduction_video_type','introduction_video_link','status'
    ];

    protected $casts = [
        "status" => "int"
    ];

    public function guide_category()
    {
        return $this->hasMany('App\Guidecategory', 'guide_id','id');
    }

}
