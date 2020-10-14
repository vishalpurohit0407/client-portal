<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Uuid;
use Storage;

class Flowchart extends Model
{
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate(4);
        });
    }

	protected $table = 'flowchart';
    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'status'
    ];

    public function flowchart_node()
    {
        return $this->hasMany('App\Flowchartnode', 'flowchart_id','id');
    }
}
