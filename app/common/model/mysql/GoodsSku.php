<?php

namespace app\common\model\mysql;


class GoodsSku extends BaseModel
{

    public function goods() {
        return $this->hasOne(Goods::class, "id",  "goods_id");
    }
    public function getNormalByGoodsId($goodsId = 0) {
        $where = [
            "goods_id" => $goodsId,
            "status" => config("status.mysql.table_normal"),
        ];

        return $this->where($where)->select();
    }


}