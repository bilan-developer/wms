<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchases';
    protected $fillable = [
        'id',
        'comment',
        'amount',
        'is_return',
    ];

    /**
     * Роли, принадлежащие пользователю.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'purchases_products', 'id_purchases', 'id_product')->withPivot('number', 'amount');
    }

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

    public function returnProduct()
    {
        $products = $this->products;
        foreach ($products as $product){
            History::returnProduct($product->id, $product->pivot->number, $product->price, $product->pivot->amount);
            $product->add($product->pivot->number);
        }

        $this->is_return = true;
        $this->save();
    }
}
