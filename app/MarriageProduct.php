<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MarriageProduct extends Model
{
    protected $table = 'marriage_products';
    protected $fillable = [
        'id_marriage',
        'id_product',
        'number',
        'amount'
    ];
    public $timestamps = false;

    /**
     * @param positions
     * @param $id_marriage
     *
     * @return bool
     */
    public function saveMarriage($positions, $id_marriage)
    {
        DB::beginTransaction();
        try {
            foreach ($positions as $position){
                DB::table($this->table)->insert([
                        'id_marriage' => $id_marriage,
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
     * Выборка списанного товара.
     *
     * @param $id
     * @return array
     */
    public static function products($id)
    {
        $marriage = MarriageProduct::where('id_marriage', $id)->get();
        $result = [];
        foreach ($marriage as $key=>$value){
            $product = current($value->product()->withTrashed()->get()->toArray());
            $productInfo = $value->toArray();
            $result[] = array_merge($product, $productInfo);
        }

        return $result;
    }
}
