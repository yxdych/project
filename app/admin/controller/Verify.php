<?php

namespace app\admin\controller;

use think\captcha\facade\Captcha;

class Verify
{


    public function verify()
    {
        return Captcha::create();
    }

}