<?php

namespace App\Http\Controllers;

use App\BTC_USD;
use Illuminate\Http\Request;

class ExmoController extends Controller
{
    public function index()
    {
        $url = 'https://api.exmo.com/v1/ticker/';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $json = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($json, true);

        $btc_usd = new BTC_USD($result['BTC_USD']);
        $btc_usd->save();
        dd($result);
//        dd(1); https://api.exmo.com/v1/ticker/
        return view('exmo/index', []);
    }
}
