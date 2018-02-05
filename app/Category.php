<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['id','name'];

    /**
     * Категории, принадлежащие товару.
     */
    public function products()
    {
        return $this->belongsToMany('App\Product', 'category_product', 'id_category', 'id_product');
    }
}