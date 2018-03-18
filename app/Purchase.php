<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchases';
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
        $purchase = new Purchase([
            'amount' => $post['amount'],
            'comment' => $post['comment'],
        ]);

        $purchase->save();

        $purchasesProduct = new PurchasesProduct();
        $history = new History();
        $purchasesProduct->savePurchases($post['positions'], $purchase->id);
        $history->bay($post['positions']);
    }

    /**
     * Общая сумма продаж товара.
     *
     * @return int
     */
    public static function salesAmount()
    {
        $data = Purchase::all(['amount'])->toArray();
        return array_sum(array_column($data, 'amount'));
    }
}
