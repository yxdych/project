<?php

namespace app\api\controller\order;

use app\api\controller\AuthBase;
use app\common\business\Order;
use app\common\lib\Show;

class Index extends AuthBase
{
    public function save() {
        $addressId = input("param.address_id", 0, "intval");
        $ids = input("param.ids", "", "trim");
        if(!$ids) {
            // 参数适配
            $ids = input("param.cart_ids", "", "trim");
        }
        if(!$addressId || !$ids) {
            return Show::error("参数错误");
        }

        $data = [
            "ids" => $ids,
            "address_id" => $addressId,
            "user_id" => $this->userId,
        ];
        try {
            $result = (new Order())->save($data);
        }catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        if(!$result) {
            return Show::error("提交订单失败，请稍候重试");
        }
        return Show::success($result);

    }


}