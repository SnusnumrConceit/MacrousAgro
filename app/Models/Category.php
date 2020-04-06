<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];

    protected $perPage = 15;

    public function products()
    {
        return $this->hasMany(Product::class);
    }
//    public function attributes( )
//    {
//        return $this->belongsToMany('App\Models\Attribute', 'categories_attributes', 'category_id', 'attribute_id');
//    }
}
