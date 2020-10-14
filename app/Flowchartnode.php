<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Uuid;
use Storage;

class Flowchartnode extends Model
{
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate(4);
        });
    }

	protected $table = 'flowchart_node';
    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'flowchart_id', 'label', 'type', 'text','yes','no','tips_title','tips_text','next','orient_yes','orient_no','link_text','link_url','link_target'
    ];
}
