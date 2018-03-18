<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class History extends Model
{
    const add = 1;
    const bay = 2;
    const writeOff = 3;
    const delete = 4;
    protected $table = 'histories';
    protected $fillable = [
        'id',
        'number',
        'price',
        'amount',
        'product_id',
        'operation_id'
    ];

    /**
     * Категории, принадлежащие товару.
     */
    public function product()
    {
        return $this->hasOne('App\Product', 'id', 'id_product')->withTrashed();
    }

    /**
     * Категории, принадлежащие товару.
     */
    public function operation()
    {
        return $this->hasOne('App\Operation');
    }

    /**
     * Списание.
     *
     * @param $positions
     * @return bool
     */
    public function writeOff($positions){
        DB::beginTransaction();
        try {
            foreach ($positions as $position){
                DB::table($this->table)->insert([
                        'id_product' => $position['id'],
                        'number' => $position['number'],
                        'price' => round($position['amount']/$position['number'], 2),
                        'amount' => $position['amount'],
                        'operation_id' => self::writeOff,
                        'created_at' => date("Y-m-d H-i-s"),
                        'updated_at' => date("Y-m-d H-i-s"),

                    ]
                );
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
        }
        return false;
    }

    /**
     * Покупки.
     *
     * @param $positions
     * @return bool
     */
    public function bay($positions){
        DB::beginTransaction();
        try {
            foreach ($positions as $position){
                DB::table($this->table)->insert([
                        'id_product' => $position['id'],
                        'number' => $position['number'],
                        'price' => round($position['amount']/$position['number'], 2),
                        'amount' => $position['amount'],
                        'operation_id' => self::bay,
                        'created_at' => date("Y-m-d H-i-s"),
                        'updated_at' => date("Y-m-d H-i-s"),
                    ]
                );
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
        }
        return false;
    }

    /**
     * История добавления товара.
     *
     * @param $product
     */
    public function add($product)
    {
        $history = new History();

        $history->id_product = $product->id;
        $history->number = $product->total;
        $history->price = $product->price;
        $history->amount = round($product->price * $product->total, 2) ;
        $history->operation_id = self::add;

        $history->save();
    }


    /**
     * История редактирования товара.
     *
     * @param $product
     *
     * @param $number
     */
    public function edit($product, $number)
    {
        $history = new History();

        $history->id_product = $product->id;
        $history->number = $number;
        $history->price = $product->price;
        $history->amount = round($number * $product->total, 2) ;
        $history->operation_id = self::add;

        $history->save();
    }

    /**
     * История редактирования товара.
     *
     * @param $product
     *
     * @param $number
     *
     * @return bool|null|void
     */
    public function deleteProduct($product, $number)
    {
        $history = new History();

        $history->id_product = $product->id;
        $history->number = $number;
        $history->price = $product->price;
        $history->amount = round($number * $product->total, 2) ;
        $history->operation_id = self::delete;

        $history->save();
    }
}
