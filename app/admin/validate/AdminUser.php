<?php

namespace app\admin\validate;

use think\Validate;

class AdminUser extends Validate
{

        protected $rule =[
            'username'=>'require',
            'password'=>'require',
//            'captcha'=>'require|checkCapcha'
        ];
        protected $message = [
            'username'=>'用户名不能为空1',
            'password'=>'密码不能为空',
//            'captcha'=>'验证码不能为空',
        ];

        protected function  checkCapcha($value,$rule,$data=[])
        {

            if (!captcha_check($value)) {
            // 验证失败
             return '输入的验证码不正确';
            }
            return  true;
        }
}