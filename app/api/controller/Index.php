<?php

namespace app\api\controller;

use app\common\lib\Show;

class Index extends ApiBase
{
    public function getRotationChart()
    {
        $goods = (new  \app\common\business\Goods())->getRotationChart();
        return Show::success($goods, 'ok');

    }

    public function cagegoryGoodsRecommend()
    {
        $categoryIdS = [
            93,
            106
        ];
        //获取商品
        $result =(new  \app\common\business\Goods())->getCagegoryGoodsRecommend($categoryIdS);
        return Show::success($result, 'ok');

    }
}