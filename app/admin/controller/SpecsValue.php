<?php
/**
 * Created by singwa
 * User: singwa
 * motto: 现在的努力是为了小时候吹过的牛逼！
 * Time: 07:25
 */

namespace app\admin\controller;
use app\common\business\SpecsValue as SpecsValueBis;
class SpecsValue extends AdminBase {
    /**
     * 新增逻辑
     */
    public function save() {
        $specsId = input("param.specs_id", 0, "intval");
        $name = input("param.name", "", "trim");
        // 请大家仿照老师之前讲解的validate验证机制自行验证参数

        $data = [
            "specs_id" => $specsId,
            "name" => $name,
        ];
        $id = (new SpecsValueBis())->add($data);
        if(!$id) {
            return show(config('status.error'), "新增失败");
        }

        return show(config("status.success"), "OK", ["id" => $id]);
    }

    public function getBySpecsId() {
        $specsId = input("param.specs_id", 0, "intval");
        if(!$specsId) {
            return show(config('status.success'), "没有数据哦");
        }

        $result = (new SpecsValueBis())->getBySpecsId($specsId);
        return show(config('status.success'), "OK", $result);
    }
}