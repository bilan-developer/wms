<?php

namespace App\Http\Controllers;

use App\Marriage;
use App\Product;
use App\Purchase;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function balance(){
        $balance = Product::balance();
        $salesAmount = Purchase::salesAmount();
        $marriageAmount = Marriage::marriageAmount();

        return ['balance' => $balance, 'salesAmount' => $salesAmount, 'marriageAmount' => $marriageAmount];
    }
}
