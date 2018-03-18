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
        $history = new History();
        $marriageProduct->saveMarriage($post['positions'], $marriage->id);
        $history->writeOff($post['positions']);
    }

    /**
     * Общая сумма списаний товара.
     *
     * @return int
     */
    public static function marriageAmount()
    {
        $data = Marriage::all(['amount'])->toArray();
        return array_sum(array_column($data, 'amount'));
    }
}
