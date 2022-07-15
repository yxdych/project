<?php

namespace app\api\controller;

use app\common\lib\Show;

class Cart extends AuthBase
{
    public function add()
    {
//        if (!$this->request->isPost()){
//            return Show::error();
//        }
        $id = input('id', 0, 'intval');
        $num = input('num', 0, 'intval');
        if (!$id || !$num) {
            return Show::error([], '参数不合法');
        }

        $result = (new  \app\common\business\Cart())->insertRedis($this->userId, $id, $num);
        return Show::success($result);
    }

    public function lists()
    {
        $userId = $this->userId;
        $ids = input('id', '', 'trim');

        if (empty($userId)) {
            return Show::error('请登录');
        }
        $res = (new \app\common\business\Cart())->getRedisCartLists($userId, $ids);
        return  Show::success($res);
    }
}