<?php

namespace app\common\business;

use app\common\lib\Key;
use think\facade\Cache;

class Cart extends BusBase
{
    public function insertRedis($userId, $id, $num)
    {
        $goodsSku = (new GoodsSku())->getNormalSkuAndGoods($id);
        if (!$goodsSku) {
            return FALSE;
        }
        $data = [
            'title' => $goodsSku['goods']['title'],
            'image' => $goodsSku['goods']['recommend_image'],
            'num' => $num,
            'goods_id' => $goodsSku['goods']['id'],
            'create_time' => time(),
        ];

        try {
            $get = Cache::hGet(key::userCart($userId), $id);
            if ($get) {
                $get = json_decode($get, true);
                $data['num'] = $data['num'] + $get['num'];
            }
            $res = Cache::hSet(Key::userCart($userId), $id, json_encode($data));
        } catch (\Exception $E) {
            return FALSE;
        }
        return $res;
    }

    public function getRedisCartLists($userId, $ids)
    {
        try {
            if ($ids) {
                $ids = explode(',', $ids);
                $res = Cache::hMget(Key::userCart($userId), $ids);
                if (in_array(false,array_values($res))){
                        return [];
                }
            } else {
                $res = Cache::hGetAll(Key::userCart($userId));
            }
        } catch (\Exception $e) {
            $res = [];
        }
        if (empty($res)) {
            return [];
        }
        $result = [];
        $skuId = array_keys($res);
        $skus = (new GoodsSku())->getNormalInIds($skuId);
        $skuIdPrice = array_column($skus, "price", "id");
        $skuIdSpecsValueIds = array_column($skus, "specs_value_ids", "id");
        $specsValues = (new SpecsValue())->dealSpecsValue($skuIdSpecsValueIds);
        foreach ($res as $k => $v) {
            $v = json_decode($v, true);
            $v['id'] = $k;
            $v['price'] = $skuIdPrice[$k] ?? O;
            $v['total_price'] = '';
            $v['sku'] = $specsValues[$k] ?? "暂无规则";
            $result[] = $v;
        }
        return  $result;
    }
}