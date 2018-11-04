<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'product';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'id',
        'tm',
        'name',
        'units',
        'total',
        'all',
        'price',
        'nds'
    ];

    public static function one($id)
    {
        $product = Product::where('id', $id)->first();

        $categories = $product->categories()->get()->toArray();
        $result = ['product' => $product->toArray()];
        $result['product']['categories'] = array_column($categories, 'name');

        return $result;
    }

    /**
     * Обновляем количество продуктов оставшихся в магазине.
     *
     * @param $positions
     * @return bool
     */
    public function updatePosition($positions)
    {
        DB::beginTransaction();
        try {
            foreach ($positions as $position){
                DB::table($this->table)->where('id', $position['id'])->decrement('total', $position['number']);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
        }
        return false;

    }

    /**
     * Категории, принадлежащие товару.
     */
    public function categories()
    {
        return $this->belongsToMany('App\Category', 'category_product', 'id_product', 'id_category');
    }

    /**
     * Общая сумма остатка товара.
     *
     * @return int
     */
    public static function balance()
    {
        $data = Product::all(['price', 'total'])->toArray();
        $amount = 0;
        foreach ($data as $value){
            $amount += $value['price'] *  $value['total'];
        }

        return $amount;
    }

    public function add($number)
    {
        $this->total = round($this->total + $number, 2);
        $this->save();
    }

}
