<?php
/**
 * Created by singwa
 * User: singwa
 * motto: 现在的努力是为了小时候吹过的牛逼！
 * Time: 17:40
 */

namespace app\common\model\mysql;

use think\Model;

class Goods extends BaseModel
{

    /**
     * 获取后端列表数据
     * @param $where
     * @param int $num
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getLists0($data, $num = 10)
    {
        $order = ["listorder" => "desc", "id" => "desc"];
        // "status", "<>", config("status.mysql.table_delete")
        //$list = $res->where("status", "<>", config("status.mysql.table_delete"))
        $list = $this->whereIn("status", [0, 1])
            ->order($order)
            ->paginate($num);
        ///echo $this->getLastSql();exit;
        return $list;


    }


    public function searchTitleAttr($query, $value, $data)
    {
        $query->where('title', 'like', $value . '%');
    }

    public function searchCreateTimeAttr($query, $value, $data)
    {
        $query->whereBetweenTime('create_time', $value[0], $value[1]);
    }

    public function getLists($likeKeys, $data, $num = 10)
    {
        $order = ["listorder" => "desc", "id" => "desc"];
        if (!empty($likeKeys)) {
            // 搜索器
            $res = $this->withSearch($likeKeys, $data);
        } else {
            $res = $this;
        }
        $pageQuery = [];
        if (!empty($data)) {
            $pageQuery = [
                'title' => $data['title'],
                'time' => implode(' - ', $data['create_time'])
            ];
        }
        $list = $res->whereIn("status", [0, 1])
            ->order($order)
            ->paginate(['list_rows' => $num, 'query' => $pageQuery]);

        echo $res->getLastSql();
        return $list;
    }

    public function getNormalGoodsByCondition($where, $field = true, $limit = 5)
    {
        $order = ["listorder" => "desc", "id" => "desc"];

        //$where["status"] = config("status.success");   // 修改如下内容，
        $where["status"] = config("status.mysql.table_normal");

        $result = $this->where($where)
            ->order($order)
            ->field($field)
            ->limit($limit)
            ->select();
        return $result;
    }

    public function getImageAttr($value)
    {
        // 1
        // 2
        return request()->domain() . $value;
    }
    public function getRecommendImageAttr($value)
    {
        // 1
        // 2
        return request()->domain() . $value;
    }
    public function getCarouselImageAttr($value)
    {

        // 1
        // 2
        if (!empty($value)) {
            $value = explode(",", $value);
            $value = array_map(function ($v) {
                return request()->domain() . $v;
            }, $value);
        }
        return $value;
    }

    public function getNormalGoodsFindInSetCategoryId($categoryId, $field = true, $limit = 10)
    {
        $order = ["listorder" => "desc", "id" => "desc"];

        $result = $this->whereFindInSet("category_path_id", $categoryId)
            ->where("status", "=", config("status.mysql.table_normal"))
            ->order($order)
            ->field($field)
            ->limit(10)
            ->select();

//        echo $this->getLastSql();
        return $result;
    }

    public function getNormalLists($data, $num = 10, $field = true, $order)
    {
        $res = $this;
        if (isset($data['category_path_id'])) {
            $res = $this->whereFindInSet("category_path_id", $data['category_path_id']);
        }
        $list = $res->where("status", "=", config("status.mysql.table_normal"))
            ->order($order)
            ->field($field)
            ->paginate($num);

        //echo $this->getLastSql();exit;
        return $list;
    }

    public function getRotationChart($where, $field, $limit = 5)
    {
        $order = ['listorder' => 'desc', 'id' => 'desc'];
        $where['status'] = config('status.mysql.table_normal');
        return $this->where($where)->field($field)->order($order)->limit($limit)->select();

    }
    public function getBigImageAttr($value) {
        return request()->domain().$value;
    }
    public  function getCagegoryGoodsRecommend($categoryIds)
    {
        if(!$categoryIds) {
            return [];
        }
        // 栏目的获取预留给大家的作业 71 51  in category   pid
        foreach ($categoryIds as $k => $categoryId) {
            $result[$k]["categorys"] = [];
        }
        foreach($categoryIds as $key => $categoryId) {
            $result[$key]["goods"] = $this->getNormalGoodsFindInSetCategoryId($categoryId);
        }
        return $result;
    }
}