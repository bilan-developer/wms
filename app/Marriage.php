<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marriage extends Model
{
    protected $table = 'marriage';
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
        return $this->belongsToMany(Product::class, 'marriage_products', 'id_marriage', 'id_product')->withPivot('number', 'amount');
    }

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
