<?php

namespace App\Http\Controllers;

use App\Adapter;
use Illuminate\Http\Request;

class AdapterController extends Controller
{
    /**
     * Запрос на получение курсов валют
     */
    public function course()
    {
        $adapter = new Adapter();
        $course = $adapter->course();
        unset($course[3]);

        return $course;
    }
}
