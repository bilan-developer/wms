<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PurchasesProduct extends Model
{
    protected $table = 'purchases_products';
    protected $fillable = [
        'id_purchases',
        'id_product',
        'number',
        'amount'
    ];
    public $timestamps = false;

    /**
     * @param positions
     * @param $id_purchases
     *
     * @return bool
     */
    public function savePurchases($positions, $id_purchases)
    {
        DB::beginTransaction();
        try {
            foreach ($positions as $position){
                DB::table($this->table)->insert([
                        'id_purchases' => $id_purchases,
                        'id_product' => $position['id'],
                        'number' => $position['number'],
                        'amount' => $position['amount'],
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

    public function product()
    {
        return $this->hasOne('App\Product', 'id', 'id_product');
    }

    public static function products($id)
    {
        $products = PurchasesProduct::all()->where('id_purchases', $id);
        $result = [];
        foreach ($products as $key=>$product){
            $result[$key] = $product->product;
            $result[$key]['number'] = $product['number'];
            $result[$key]['amount'] = $product['amount'];
        }
        return $result;


    }
}
