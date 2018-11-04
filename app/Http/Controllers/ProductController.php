<?php

namespace App\Http\Controllers;

use App\History;
use App\Marriage;
use App\MarriageProduct;
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
    public function writeOff(Request $request)
    {
        $post = current($request->toArray());
        $operation = $post['operation'];

        if($operation == 'bay'){
            Purchase::saveData($post);
        }else{
            Marriage::saveData($post);
        }

        $product = new Product();
        $product->updatePosition($post['positions']);

        return json_encode(['status' => 'ok']);
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
     * @param History $history
     */
    public function store(Product $product, Request $request, History $history)
    {
        $data = $request->toArray();
        $product = $product->create($data);
        return $history->add($product);
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
     * @param History $history
     *
     * @return string
     */
    public function update(Request $request, $id, History $history)
    {
        $data = $request->toArray();
        $product = Product::find($id);

        $numberTo = $product->total;
        $numberFrom = $data['total'];

        $product->update($data);

        if($numberTo > $numberFrom ){
            $history->deleteProduct($product, $numberTo - $numberFrom);
        }elseif ($numberTo < $numberFrom){
            $history->edit($product, $numberFrom - $numberTo);
        }




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
        $history = new History();
        $product = Product::find($id);
        $history->deleteProduct($product, $product->total);
        $product->categories()->detach();
        $product->delete();
    }

    /**
     * Показать товар в покупке.
     *
     * @param  int  $id
     * @return array
     */
    public function showPurchase($id)
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
     * Выборка всех покупок.
     *
     * @return \Illuminate\Http\Response
     */
    public function purchase()
    {
        $headers = [

            [ 'text' => 'Дата',       'value' => 'created_at', 'align' => 'left'],
            [ 'text' => 'Коментарий', 'value' => 'comment',    'align' => 'left'],
            [ 'text' => 'Сумма',      'value' => 'amount',     'align' => 'center']
        ];

        $purchase = Purchase::orderBy('id','DESC')->get()->toArray();
        $salesAmount = 0;

        foreach ($purchase as $item){
            if(!$item['is_return']){
                $salesAmount += $item['amount'];
            }
        }

        $result = ['headers' => $headers, 'items' => $purchase, 'salesAmount' => $salesAmount];

        return json_encode($result);
    }

    /**
     * Выборка всех списаний.
     *
     * @return \Illuminate\Http\Response
     */
    public function writeOffList()
    {
        $headers = [

            [ 'text' => 'Дата',       'value' => 'created_at', 'align' => 'left'],
            [ 'text' => 'Коментарий', 'value' => 'comment',    'align' => 'left'],
            [ 'text' => 'Сумма',      'value' => 'amount',     'align' => 'center']
        ];

        $purchase = Marriage::orderBy('id','DESC')->get()->toArray();
        $salesAmount = 0;
        foreach ($purchase as $item){
            if(!$item['is_return']){
                $salesAmount += $item['amount'];
            }
        }
        $result = ['headers' => $headers, 'items' => $purchase, 'salesAmount' => $salesAmount];

        return json_encode($result);
    }

    /**
     * Показать товар в списании.
     *
     * @param  int  $id
     * @return array
     */
    public function showWriteOff($id)
    {
        $headers = [
            [ 'text' => 'Название', 'value' => 'name',  'align' => 'left'],
            [ 'text' => 'Марка',    'value' => 'tm',    'align' => 'left'],
            [ 'text' => 'Ед.',      'value' => 'units', 'align' => 'center'],
            [ 'text' => 'Количество.','value' => 'number',  'align' => 'center'],
            [ 'text' => 'Цена',     'value' => 'price', 'align' => 'center'],
            [ 'text' => 'Сумма',     'value' => 'amount', 'align' => 'center'],
        ];

        $products = MarriageProduct::products($id);
        $salesAmount = array_sum(array_column($products, 'amount'));
        $result = ['headers' => $headers, 'items' => $products, 'salesAmount' => $salesAmount];

        return json_encode($result);
    }

}
