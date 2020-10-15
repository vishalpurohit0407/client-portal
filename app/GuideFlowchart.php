<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Uuid;
use Storage;

class GuideFlowchart extends Model
{
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate(4);
        });
    }

	protected $table = 'guide_flowchart';
    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'guide_id', 'flowchart_id'
    ];
}
