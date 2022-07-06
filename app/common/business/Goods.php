<?php

namespace app\common\business;

use app\common\lib\Arr;

class Goods extends BusBase
{
    public $model = null;

    public function __construct()
    {
        $this->model = new \app\common\model\mysql\Goods();
    }

    public function insetdata($data)
    {

        $this->model->startTrans();
        try {
            $goodsId = $this->add($data);
            if (!$goodsId) {
                return $goodsId;
            }

            // 执行数据插入到 sku表中哦。
            // 如果是 统一规格
            if ($data['goods_specs_type'] == 1) {
                $goodsSkuData = [
                    "goods_id" => $goodsId,
                ];
                // untodo 小伙伴自行完成
                return true;
            } elseif ($data['goods_specs_type'] == 2) { // 多规格他是我们电商的核心
                $goodsSkuBisobj = new GoodsSku();
                $data['goods_id'] = $goodsId;
                $res = $goodsSkuBisobj->saveAll($data);

                // 如果不为空
                if (!empty($res)) {
                    // 总库存
                    $stock = array_sum(array_column($res, "stock"));
                    $goodsUpdateData = [
                        "price" => $res[0]['price'],
                        "cost_price" => $res[0]['cost_price'],
                        "stock" => $stock,
                        "sku_id" => $res[0]['id'],
                    ];
                    // 执行完毕之后 更新 主表中的数据哦。
                    $goodsRes = $this->model->updateById($goodsId, $goodsUpdateData);
                    if (!$goodsRes) {
                        throw  new \think\Exception("insertData:goods主表更新失败");
                    }
                } else {
                    throw new \think\Exception("sku表新增失败");
                }
            }

            // 事务提交
            $this->model->commit();
            return true;
        } catch (\think\Exception $e) {
            // 记录日志 untodo
            // 事务回滚
            $this->model->rollback();
            return false;
        }
    }

    public function getLists($data, $num = 5)
    {
        $likeKeys = [];
        if (!empty($data)) {
            $likeKeys = array_keys($data);
        }
        try {
            $list = $this->model->getLists($likeKeys, $data, $num);
            $result = $list->toArray();
            $result['render'] = $list->render();

//        dd($result);
        } catch (\Exception $e) {
            $result = \app\common\lib\Arr::getPaginateDefaultData($num);
        }
        return $result;
    }

    public function getById($id)
    {
        $result = $this->model->find($id);
        if (empty($result)) {
            return [];
        }
        return $result->toArray();
    }

    public function status($id, $status)
    {
        $res = $this->getById($id);
        if (!$res) {
            throw  new  \think\Exception('不存在该条记录');
        }
        if ($res['status'] == $status) {
            throw  new  \think\Exception('状态和修改后一样');
        }
        $data = [
            'status' => intval($status)
        ];
        try {
            $res = $this->model->updateById($id, $data);
        } catch (\Exception $e) {
            $res = [];
        }
        return $res;
    }

    public function indexStatus($id, $is_index_recommend)
    {
        $res = $this->getById($id);
        if (!$res) {
            throw  new  \think\Exception('不存在该条记录');
        }
        if ($res['is_index_recommend'] == $is_index_recommend) {
            throw  new  \think\Exception('状态和修改后一样');
        }
        $data = [
            'is_index_recommend' => intval($is_index_recommend)
        ];
        try {
            $res = $this->model->updateById($id, $data);
        } catch (\Exception $e) {
            $res = [];
        }
        return $res;
    }

    public function getRotationChart()
    {
        $where = [
            'is_index_recommend' => 1
        ];
        $field = "sku_id as id, title, big_image as image";

        try {
            $res = $this->model->getRotationChart($where, $field, 5);
        } catch (\Exception $e) {
            $res = [];
        }
        return $res->toArray();
    }

    public function getCagegoryGoodsRecommend($categoryIds=[])
    {
        if(!$categoryIds) {
            return [];
        }
        $goods=[];
        //获取默认分类
        foreach ($categoryIds as $k => $v) {
            $goods[$k]['categorys'] =(new  Category())->getCategoryRecommend($v);
            $goods[$k]['goods'] =   $this->getNormalGoodsFindInSetCategoryId($v);
        }
        $result=Arr::getCategoryGroup($categoryIds,$goods);
        return $result;

    }
    public function getNormalGoodsFindInSetCategoryId($categoryId) {
        $field = "sku_id as id, title, price , recommend_image as image";
        try {
            $result = $this->model->getNormalGoodsFindInSetCategoryId($categoryId, $field);
        }catch (\Exception $e) {
            return [];
        }
        return $result->toArray();
    }
    public function getNormalLists($data, $num = 5,$order)
    {
        try {
            $field = "sku_id as id, title, recommend_image as image,price";
            $list = $this->model->getNormalLists($data, $num, $field,$order);
            $res = $list->toArray();
            $result = [
                "total_page_num" => isset($res['last_page']) ? $res['last_page'] : 0,
                "count" => isset($res['total']) ? $res['total'] : 0,
                "page" => isset($res['current_page']) ? $res['current_page'] : 0,
                "page_size" => $num,
                "list" => isset($res['data']) ? $res['data'] : []
            ];
        }catch (\Exception $e) {
            ///echo $e->getMessage();exit;
            // 演示之前的地方
            $result = [];
        }
        return $result;
    }
    public function getGoodsDetailBySkuId($skuId)
    {
        $skuBisObj = new  \app\common\business\GoodsSku();
        $goodsSku = $skuBisObj->getNormalSkuAndGoods($skuId);//商品数据

        if(!$goodsSku) {
            return [];
        }
        if(empty($goodsSku['goods'])) {
            return [];
        }
        $goods = $goodsSku['goods'];
        $skus = $skuBisObj->getSkusByGoodsId($goods['id']);
        if(!$skus) {
            return [];
        }
        $flagValue = "";
        foreach($skus as $sv) {
            if($sv['id'] == $skuId) {
                $flagValue = $sv["specs_value_ids"];
            }
        }
        $gids = array_column($skus, "id", "specs_value_ids");
        $sku = (new  \app\common\business\SpecsValue())->dealGoodsSkus($gids,$flagValue);
        $result = [
            "title" => $goods['title'],
            "price" => $goodsSku['price'],
            "cost_price" => $goodsSku['cost_price'],
            "sales_count" => 0,
            "stock" => $goodsSku['stock'],
            "gids" => $gids,
            "image" => $goods['carousel_image'],
            "sku" =>$sku,
            "detail" => [
                "d1" => [
                    "商品编码" => $goodsSku['id'],
                    "上架时间" => $goods['create_time'],
                ],
                "d2" => preg_replace('/(<img.+?src=")(.*?)/', '$1'.request()->domain().'$2',$goods['description']),
            ],

        ];
        return $result;


    }

}