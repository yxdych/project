<?php

namespace app\api\controller\mall;

use app\api\controller\ApiBase;
use app\common\lib\Show;

class Lists extends  ApiBase
{
        public function  index()
        {
            $pageSize = input("param.page_size", 10, "intval");
            $categoryId = input("param.category_id", 0, "intval");
            if(!$categoryId) {
                return Show::success();
            }
            $data = [
                "category_path_id" => $categoryId,
            ];

            //排序 销量价格
            $field = input("param.field", "listorder", "trim");
            $order = input("param.order", 2, "intval");
            $order = $order == 2 ? "desc" : "asc";
            $order = [$field => $order];
            //end
            $goods = (new \app\common\business\Goods())->getNormalLists($data, $pageSize,$order);
            return Show::success($goods);

        }
}