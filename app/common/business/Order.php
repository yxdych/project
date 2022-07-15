<?php

namespace app\common\business;

use app\common\lib\snowflake\Snowflake;

class Order extends BusBase
{
    public $model = NULL;

    public function __construct()
    {
        $this->model = new \app\common\model\mysql\Order();
    }

    public function save($data)
    {
         //订单号
//      $work=rand(1,1023);
        $snowflake = new Snowflake();
        $orderId= $snowflake->id();
        //获取用户redis 购物车数据
        $carObj= new  Cart();
        $res=$carObj->getRedisCartLists($data['user_id'],$data['ids']);
        dd($res);
    }
}