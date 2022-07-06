<?php

namespace app\admin\controller;


use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\AddShortUrlResponseBody\data;

use think\Exception;
use think\facade\View;

class Category extends AdminBase
{
    public function index()
    {
        $pid = input('pid', 0, 'intval');
        $data = [
            'pid' => $pid
        ];
        try {
            $category = (new \app\common\business\Category())->getLists($data, 5);
        } catch (\Exception $e) {
            $result=\app\common\lib\Arr::getPaginateDefaultData(5);
        }
        return View::fetch('', ['category' => $category, 'pid' => $pid]);
    }

    public function add()
    {
        try {
            $categroys = (new  \app\common\business\Category())->getNormalCategory();
        } catch (\Exception $e) {
            $categroys = [];
        }

        return View::fetch('', ['categroys' => json_encode($categroys)

        ]);
    }

    public function save()
    {
        //接收参数
        $pid = input('pid', 0, 'intval');
        $name = input('name', '', 'trim');
        $data = [
            'pid' => $pid,
            'name' => $name,
        ];
        //验证
        $validate = new  \app\admin\validate\Category();
        if (!$validate->scene('pid_name')->check($data)) {
            return show(config('status.error'), $validate->getError());
        }
        //写入数据库
        try {
            (new  \app\common\business\Category())->add($data);
        } catch (\Exception $e) {
            return show(config('status.error'), $e->getMessage());
        }
        return show(config('status.status'), '成功');
    }

    public function listorder()
    {
        $id = input('id', 0, 'intval');
        $listorder = input('listorder', '', 'trim');
        $data = [
            'id' => $id,
            'listorder' => $listorder
        ];
        $validate = new  \app\admin\validate\Category();
        if (!$validate->scene('id_listorder')->check($data)) {
            return show('status.error', $validate->getError());
        }
        try {
            $res = (new \app\common\business\Category())->listorder($id, $listorder);
        } catch (\Exception $e) {
            return show(config('status.error'), $e->getMessage());
        }
        if ($res) {
            return show(config('status.status'), '排序成功');
        } else {
            return show(config('status.error'), '排序失败');
        }
    }

    public function status()
    {
        $id = input('id', '', 'intval');
        $status = input('status', '', 'trim');
        $tableStatus = \app\common\lib\Status::getTableStatus();
        $data = [
            'id' => $id,
            'status' => $status,
            'tableStatus' => $tableStatus
        ];
        $validate = new  \app\admin\validate\Category();
        if (!$validate->scene('id_status')->check($data)) {
            return show(config('status.error'), $validate->getError());
        }
        try {
            $res = (new \app\common\business\Category())->status($id, $status);
        } catch (\Exception $e) {
            return show(config('status.error'), $e->getMessage());
        }
        if ($res) {
            return show(config('status.status'), '状态更新成功');
        } else {
            return show(config('status.error'), '状态更新失败');
        }
    }
    public function dialog()
    {
        // 获取正常的一级分类数据。 代码提供好 带小伙伴解读下代码 @9-5
        $categorys = (new  \app\common\business\Category())->getNormalByPid();
        return view("", [
            "categorys" => json_encode($categorys),
        ]);
    }
    public  function getByPid()
    {
        $pid=input('pid',0,'intval');
        $data=[
            'pid'=>$pid
        ];
        $validate=new  \app\admin\validate\Category();
        if(!$validate->scene('pid')->check($data))
        {
            return show(config('status.error'), $validate->getError());
        }
        try {
            $res=(new  \app\common\business\Category())->getNormalByPid($pid);
        }catch (\Exception $e){
            return show(config('status.error'), $e->getMessage());
        }
            return show(config('status.status'), 'OK',$res);

    }

}