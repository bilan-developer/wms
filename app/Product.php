<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $fillable = [
        'id',
        'tm',
        'name',
        'units',
        'total',
        'all',
        'prise',
        'nds'
    ];
}
