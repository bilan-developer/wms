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

    /**
     * Выборка купленного товара.
     *
     * @param $id
     * @return array
     */
    public static function products($id)
    {
        $purchases = PurchasesProduct::where('id_purchases', $id)->get();
        $result = [];
        foreach ($purchases as $key=>$purchase){
            $product = current($purchase->product()->withTrashed()->get()->toArray());
            $productInfo = $purchase->toArray();
            $result[] = array_merge($product, $productInfo);
        }

        return $result;
    }
}
