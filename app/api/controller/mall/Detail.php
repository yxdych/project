<?php

namespace app\api\controller\mall;

use app\api\controller\ApiBase;
use app\common\lib\Show;

class Detail extends ApiBase
{
    public  function index()
    {

        $id=input('id','','intval');
         if (!$id){
             return Show::error();
         }

        $result = (new \app\common\business\Goods())->getGoodsDetailBySkuId($id);
        if(!$result) {
            return Show::error();
        }
        return Show::success($result);
     }
}