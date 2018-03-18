<?php

namespace App\Http\Controllers;

use App\Operation;
use Illuminate\Http\Request;

class OperationController extends Controller
{
    /**
     * Вывод таблици с продуктами.
     *
     * @return array
     */
    public function index()
    {
        return Operation::all()->toArray();
    }
}
