<?php
declare(strict_types=1);

namespace app\api\controller;

use app\api\validate\User;
use app\BaseController;

class Login extends BaseController
{
    public function index(): object
    {
        $phoneNumber = $this->request->param('phone_number', '', 'trim');
        $code = input('code', '', 'intval');
        $type = input('type', '', 'intval');
        $data = [
            'phone_number' => $phoneNumber,
            'code' => $code,
            'type' => $type,
        ];
        $validate = new User();
        if (!$validate->scene('login')->check($data)) {
            return show(config('status.error'), $validate->getError());
        }
        $result = (new \app\common\business\User())->login($data);
        if ($result) {
            return show(config('status.success'), '登录成功',$result);
        }
        return show(config('status.error'), '登录失败');
    }
}