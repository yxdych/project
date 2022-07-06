<?php

namespace app\api\controller;

use app\common\lib\Show;

class Category extends ApiBase
{
    public  function index()
    {
        $categoryBusObj=new \app\common\business\Category();
        $categorys=$categoryBusObj->getNormalAllCategory();
        $result= \app\common\lib\Arr::getTree($categorys);
        return show(config('status.status'),'ok',$result);
    }
    public function search()
    {
        $id = input('id', '', 'intval');
        $data = [
            'id' => $id
        ];
        $validate = new \app\api\validate\Category();
        if (!$validate->scene('id')->check($data)){
            return Show::error();
        }

        $result =(new \app\common\business\Category())->getSearchCategory($id);
        return show(config('status.status'),'ok',$result);
    }
    public  function subcategory()
    {
        $id = input('id', '', 'intval');
        $data = [
            'id' => $id
        ];
        $validate = new \app\api\validate\Category();
        if (!$validate->scene('id')->check($data)){
            return Show::error();
        }
        $result =(new \app\common\business\Category())->getSubCategoryRecommend($id);
        return Show::success($result);
    }
}