<?php

namespace app\api\controller;


use app\BaseController;


class Sms extends BaseController
{

    public function code(): object
    {
        //接受前端参数,
        $phoneNumber = input('phone_number', '15391136613', 'trim');
        $data = [
            'phone_number' => $phoneNumber
        ];
        //验证前端参数
        try {
            //验证场景
            validate(\app\api\validate\User::class)->scene('edit')->check($data);
        } catch (\think\exception\ValidateException $e) {
            return show(config('status.error'), $e->getError());
        }

        //发送短信 调用业务逻辑层
        $sms = \app\common\business\Sms::senCode($phoneNumber, 6);
        if ($sms) {
            return show(config('status.success'), '发送验证码成功');
        }
        return show(config('status.error'), '发送验证码失败');
    }
}