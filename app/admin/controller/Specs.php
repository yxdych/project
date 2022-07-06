<?php
/**
 * Created by singwa
 * User: singwa
 * motto: 现在的努力是为了小时候吹过的牛逼！
 * Time: 07:25
 */

namespace app\admin\controller;
class Specs extends AdminBase {

    /**
     * 9-4 提前准备好的代码
     * @return \think\response\View
     */
    public function dialog() {

        return view("", [
            "specs" => json_encode(config("specs"))
        ]);
    }
}