<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Uuid;

class Category extends Authenticatable
{
    protected $table = 'category';
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
        'name', 'parent_id', 'icon', 'status'
    ];

    protected $casts = [
        "status" => "int"
    ];

    public function subcategory()
    {
        return $this->hasMany('App\Category', 'parent_id');
    }
    public function parent()
    {
        return $this->hasOne('App\Category', 'id', 'parent_id');
    }

}
