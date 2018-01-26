<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function getProducts()
    {
        $headers = [

            [ 'text' => 'Название', 'value' => 'name',  'align' => 'left'],
            [ 'text' => 'Марка',    'value' => 'tm',    'align' => 'left'],
            [ 'text' => 'Ед.',      'value' => 'units', 'align' => 'center'],
            [ 'text' => 'В наличии.', 'value' => 'total',   'align' => 'center'],
            [ 'text' => 'Необходимо.' ,'value' => 'all',    'align' => 'center'],
            [ 'text' => 'Цена',     'value' => 'price', 'align' => 'center'],
            [ 'text' => '',         'value' => 'add',   'align' => 'center']
        ];

        $products = Product::orderBy('id','DESC')->get()->toArray();

        $result = ['headers' => $headers, 'items' => $products];

        return json_encode($result);
    }

    public function getProduct(Request $request)
    {
        $product = Product::all()->where('id', '=', $request->id)->toArray();
        $result = ['product' => array_shift($product)];
        return json_encode($result);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
