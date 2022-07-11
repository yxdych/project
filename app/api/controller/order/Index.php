<?php

namespace app\api\controller\order;

use app\api\controller\AuthBase;
use app\common\business\Order;
use app\common\lib\Show;

class Index extends AuthBase
{

    public function save()
    {
        $addressId = input('address_id', 0, 'intval');
        $ids = input('ids', '', 'trim');
        if (!$addressId || !$ids) {
            return Show::error();
        }
        $data = [
            'ids' => $ids,
            'address_id' => $addressId,
            'user_id' => $this->userId
        ];
        try {
            $result=(new Order())->save($data);
        }catch (\Exception $e){
            return  Show::error($e->getMessage());
        }
        if (!$result)
        {
            return Show::error('提交订单失败，请重试');
        }
        return Show::success($result);
    }

}