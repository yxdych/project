<?php

namespace app\admin\validate;

use app\common\lib\Status;
use think\Validate;

class Category extends Validate
{
    protected $rule = [
        'pid' => 'require',
        'id' => 'require',
        'name' => 'require',
        'status' => 'require|checkTableStatus',
        'listorder' => 'require',
    ];
    protected $message = [
        'pid' => '父及分类不能为空',
        'id' => 'id不能为空',
        'name' => '分类名称不能为空',
        'listorder' => '排序不能为空',
        'status' => '状态不能为空',

    ];
    protected $scene = [
        'id_listorder' => ['id', 'listorder'],
        'id_status' => ['id', 'status'],
        'pid_name' => ['pid', 'name'],
        'pid'=>['pid']
    ];

    protected function checkTableStatus($value, $rule, $data = [])
    {
        if (!in_array($value, $data['tableStatus'])) {
            return '状态码不存在';
        }
        return true;
    }
}