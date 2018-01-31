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
}
