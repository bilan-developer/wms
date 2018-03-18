<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $table = 'operations';
    protected $fillable = [
        'id',
        'name',
    ];

    /**
     * Продукты принадлижащие операции.
     */
    public function products()
    {
        return $this->belongsTo('App\Product');
    }
}
