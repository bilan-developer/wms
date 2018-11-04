<?php

namespace App\Http\Controllers;

use App\Marriage;
use App\Product;
use App\Purchase;
use Illuminate\Http\Request;

class ReturnProductController extends Controller
{
    /**
     * Возврат  товара с покупок.
     *
     * @param Purchase $purchase
     * @return array
     */
    public function purchase(Purchase $purchase)
    {
        if($purchase->is_return){
            return ['status' => 'error', 'message' => 'Товар возвращён на склад'];
        }

        $purchase->returnProduct();

        return ['status' => 'ok'];

    }

    /**
     * Возврат  товара с списания.
     *
     * @param Marriage $marriage
     * @return array
     */
    public function writeOff(Marriage $marriage)
    {
        if($marriage->is_return){
            return ['status' => 'error', 'message' => 'Товар возвращён на склад'];
        }

        $marriage->returnProduct();

        return ['status' => 'ok'];

    }
}
