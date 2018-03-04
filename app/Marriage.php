<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marriage extends Model
{
    protected $table = 'marriage';
    protected $fillable = [
        'id',
        'comment',
        'amount'
    ];


    /**
     * Сохраняем данные по покупке.
     *
     * @param $post
     */
    public static function saveData($post)
    {
        $marriage = new Marriage([
            'amount' => $post['amount'],
            'comment' => $post['comment'],
        ]);

        $marriage->save();

        $marriageProduct = new MarriageProduct();
        $marriageProduct->saveMarriage($post['positions'], $marriage->id);
    }
}
