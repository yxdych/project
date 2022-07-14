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
            if($ids) {
                $ids = explode(",", $ids);
                $res = Cache::hMget(Key::userCart($userId), $ids);
                if(in_array(false, array_values($res))) {
                    return [];
                }
            } else {
                $res = Cache::hGetAll(Key::userCart($userId));
            }
        }catch (\Exception $e) {
            $res = [];
        }
        if(!$res) {
            return [];
        }

        $result = [];
        $skuIds = array_keys($res);

        $skus = (new GoodsSku())->getNormalInIds($skuIds);
        //真是库存 id =》库存
        $stocks = array_column($skus, "stock", "id");
        $skuIdPrice = array_column($skus, "price", "id");
        $skuIdSpecsValueIds = array_column($skus, "specs_value_ids", "id");
        $specsValues = (new SpecsValue())->dealSpecsValue($skuIdSpecsValueIds);
        foreach($res as $k => $v) {
        dd($stocks);

            $price = $skuIdPrice[$k] ?? 0;
            $v = json_decode($v, true);
            if($ids && isset($stocks[$k]) && $stocks[$k] < $v['num']) {
                throw new \think\Exception($v['title']."的商品库存不足");
            }
            $v['id'] = $k;
            $v['image'] = preg_match("/http:\/\//", $v['image']) ? $v['image'] : request()->domain().$v['image'];
            $v['price'] = $price;
            $v['total_price'] = $price * $v['num'];
            $v['sku'] = $specsValues[$k] ?? "暂无规则";
            $result[] = $v;
        }
        if(!empty($result)) {
            $result = Arr::arrsSortByKey($result, "create_time");
        }
        return $result;
    }
} 