<?php

namespace App\Http\Controllers;

use App\Product;
use App\Purchase;
use App\PurchasesProduct;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $headers = [

            [ 'text' => 'Дата',       'value' => 'created_at', 'align' => 'left'],
            [ 'text' => 'Коментарий', 'value' => 'comment',    'align' => 'left'],
            [ 'text' => 'Сумма',      'value' => 'amount',     'align' => 'center']
        ];

        $purchase = Purchase::orderBy('id','DESC')->get()->toArray();
        $salesAmount = array_sum(array_column($purchase, 'amount'));
        $result = ['headers' => $headers, 'items' => $purchase, 'salesAmount' => $salesAmount];

        return json_encode($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return array
     */
    public function show($id)
    {
        $headers = [
            [ 'text' => 'Название', 'value' => 'name',  'align' => 'left'],
            [ 'text' => 'Марка',    'value' => 'tm',    'align' => 'left'],
            [ 'text' => 'Ед.',      'value' => 'units', 'align' => 'center'],
            [ 'text' => 'Количество.','value' => 'number',  'align' => 'center'],
            [ 'text' => 'Цена',     'value' => 'price', 'align' => 'center'],
            [ 'text' => 'Сумма',     'value' => 'amount', 'align' => 'center'],
        ];

        $products = PurchasesProduct::products($id);
        $salesAmount = array_sum(array_column($products, 'amount'));
        $result = ['headers' => $headers, 'items' => $products, 'salesAmount' => $salesAmount];

        return json_encode($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
