<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BTC_USD extends Model
{
    protected $table = 'btc_usd';
    protected $fillable = [
        'id',
        'buy_price',
        'sell_price',
        'last_trade',
        'high',
        'low',
        'avg',
        'vol',
        'vol_curr'
    ];
}
