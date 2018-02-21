<?php

namespace App\Http\Controllers;

use App\Product;
use App\Purchase;
use App\PurchasesProduct;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    /**
     * Обработка покупки.
     *
     * @param Request $request \
     * @return string
     */
    public function pay(Request $request)
    {
        $post = current($request->toArray());
        $purchase = new Purchase(array(
            'amount' => $post['amount'],
            'comment' => $post['comment'],
        ));
        $purchase->save();

        if(!$purchase->id){
            return json_encode(['status' => 'error']);
        }

        $purchasesProduct = new PurchasesProduct();
        $result = $purchasesProduct->savePurchases($post['positions'], $purchase->id);

        if(!$result){
            $purchase->delete();
            return json_encode(['status' => 'error']);
        }

        $product = new Product();
        $result = $product->updatePosition($post['positions']);

        if(!$result){
            $purchase->delete();
            return json_encode(['status' => 'error']);
        }

        return json_encode(['status' => 'ok']);

//        dd(11);
    }

    /**
     * Вывод таблици с продуктами.
     *
     * @return string
     */
    public function index()
    {
        $headers = [

            [ 'text' => 'Название', 'value' => 'name',  'align' => 'left'],
            [ 'text' => 'Марка',    'value' => 'tm',    'align' => 'left'],
            [ 'text' => 'Ед.',      'value' => 'units', 'align' => 'center'],
            [ 'text' => 'В наличии', 'value' => 'total',   'align' => 'center'],
            [ 'text' => 'Необходимо' ,'value' => 'all',    'align' => 'center'],
            [ 'text' => 'Цена',     'value' => 'price', 'align' => 'center']
        ];

        $products = Product::orderBy('id','DESC')->get()->toArray();
        $result = ['headers' => $headers, 'items' => $products];

        return json_encode($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return json_encode(['create' => 11]);

    }

    /**
     * Создание нового продукта.
     *
     * @param Product $product
     * @param  \Illuminate\Http\Request $request
     */
    public function store(Product $product, Request $request)
    {
        $data = $request->toArray();
        $product = $product->create($data);

        foreach ($data['categories'] as $category){
            $product->categories()->attach(['id_category' => $category]);
        }
    }

    /**
     * Выборка товара по его id.
     *
     * @param  int $id
     *
     * @return string
     */
    public function show($id)
    {
        $result = Product::one($id);
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
        return json_encode(['edit' => 11]);

    }

    /**
     * Обновление продукта.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return string
     */
    public function update(Request $request, $id)
    {
        $data = $request->toArray();
        $product = Product::find($id);
        $product->update($data);

        $product->categories()->detach();
        foreach ($data['categories'] as $category){
            $product->categories()->attach(['id_category' => $category]);
        }

        return json_encode($product);
    }

    /**
     * Удаление продукта.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->categories()->detach();
        $product->delete();
    }
}
