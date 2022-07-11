<?php

namespace app\common\business;

class OrderGoods extends  BusBase
{

    public $model = NULL;

    public function __construct()
    {
        $this->model = new \app\common\model\mysql\OrderGoods();
    }
}