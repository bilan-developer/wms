<?php

namespace App\Http\Controllers;

use App\BTC_USD;
use Illuminate\Http\Request;

class ExmoController extends Controller
{
    public function index()
    {
        $btc_usd = BTC_USD::all()->toArray();

        $result = [];
        $i = 0;
        foreach ($btc_usd as $info){
            $result[] = [$i++, $info['buy_price']];
        }

        return  $result;
    }

    public function exmo()
    {
        return view('exmo/index');
    }

    public function candles()
    {
        return view('exmo/candles');
    }
}
