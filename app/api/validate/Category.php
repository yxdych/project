<?php

namespace app\api\validate;

use think\Validate;

class Category extends Validate
{
    protected $rule = [
        'id' => 'require',

    ];
    protected $message = [
        'id' => '参数不能为空',

    ];
    protected $scene = [
        'id' => ['id'],
    ];
}