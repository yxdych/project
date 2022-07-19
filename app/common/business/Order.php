<?php

namespace app\common\business;

use app\common\lib\snowflake\Snowflake;
use app\common\model\mysql\Order as OrderModel;
use app\common\model\mysql\OrderGoods as OrderGoodsModel;

use app\common\business\Cart;
use app\common\business\OrderGoods;
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
        $result=$carObj->getRedisCartLists($data['user_id'],$data['ids']);
        if (!$result) {
            // code...
            return false;
        }
        $price=array_sum(array_column($result, 'total_price'));
        $oederData=[
            'user_id'=>$data['user_id'],
            'order_id'=>$orderId,
            'total_price'=>$price,
            'address_id'=>$data['address_id']
        ];
        $this->model->startTrans();
        try{
            //新增 order
            $id=$this->add($orderId);
            if (!id) {
                // code...
                return 0;
            }
             // 新增order_goods
            $orderGoodsResult = (new OrderGoodsModel())->saveAll($newResult);
            // goods_sku 更新
            $skuRes = (new GoodsSku())->updateStock($result);
            // goods 更新 =》 小伙伴自行完成
            // 删除购物车里面的商品
            $carObj->deleteRedis($data['user_id'], $data['ids']);
             $this->model->commit();
             return true;
        }catch(\Exception $e){
            $this->model->rollback();
            return false;
        }
    }
}