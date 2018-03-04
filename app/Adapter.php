<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adapter extends Model
{
    const BANK_COURSE = 'https://api.privatbank.ua/p24api/pubinfo?exchange&json&coursid=11';

    public function course()
    {
        return $this->getData(self::BANK_COURSE);
    }

    /**
     * Выполнение запроса к серверу
     *
     * @param string $url
     *
     * @return mixed
     */
    public function getData($url)
    {
        $result = file_get_contents($url);

        return $this->prepareResult($result);
    }

    public function prepareResult($result)
    {
        return json_decode($result);
    }
}
