<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Uuid;
use Storage;

class CompletedGuide extends Authenticatable
{
    protected $table = 'completed_guide';
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
        'guide_id', 'user_id',
    ];

    
}
