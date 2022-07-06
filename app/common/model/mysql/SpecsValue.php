<?php
/**
 * Created by singwa
 * User: singwa
 * motto: 现在的努力是为了小时候吹过的牛逼！
 * Time: 17:40
 */

namespace app\common\model\mysql;
use think\Model;

class SpecsValue extends BaseModel {

    public function getNormalBySpecsId($specsId, $field="*") {
        $where = [
            "specs_id" => $specsId,
            "status" => config("status.mysql.table_normal"),
        ];

        $res = $this->where($where)
            ->field($field)
            ->select();
        return $res;
    }
    public function getNormalInIds($ids) {
        return $this->whereIn("id", $ids)
            ->where("status", "=", config("status.mysql.table_normal"))
            ->select();
    }
}