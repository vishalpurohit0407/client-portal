<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Uuid;

class Guidecategory extends Authenticatable
{
    protected $table = 'guide_category';
    /**protected $table = 'guide_category';
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
        'guide_id', 'category_id'
    ];

    protected $casts = [
        "status" => "int"
    ];

    public function category()
    {
        return $this->hasOne('App\Category', 'id', 'category_id');
    }

}
