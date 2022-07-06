<?php

namespace app\common\business;


use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\AddShortUrlResponseBody\data;
use app\common\lib\Arr;
use Symfony\Component\VarDumper\Dumper\DataDumperInterface;

class Category
{
    public $model = null;

    public function __construct()
    {
        $this->model = new \app\common\model\mysql\Category();
    }

    public function add($data)
    {
        $data['status'] = config('status.mysql.table_normal');
        $name = [
            'name' => $data['name']
        ];

        $categoryName = $this->model->where($name)->find();
        if (!empty($categoryName)) {
            throw  new \think\Exception('分类已存在');
        }
        try {
            $this->model->save($data);
        } catch (\Exception $e) {
            throw  new \think\Exception('内部服务器异常');
        }

        return $this->model->getLastInsID();

    }

    public function getNormalCategory()
    {
        $filed = 'id,name,pid';
        $categorys = $this->model->getNormalCategorys($filed);
        if (!$categorys) {
            return [];

        }
        $categorys = $categorys->toArray();
        return $categorys;

    }

    public function getNormalAllCategory()
    {
        $filed = 'id as category_id,name,pid';
        $categorys = $this->model->getNormalCategorys($filed);
        if (!$categorys) {
            return [];

        }
        $categorys = $categorys->toArray();
        return $categorys;

    }

    public function getLists($data, $num)
    {
        $list = $this->model->getLists($data, $num);

        if (!$list) {
            return [];
        }
        $result = $list->toArray();
        $result['render'] = $list->render();
        $pids = array_column($result['data'], 'id');
        if ($pids) {
            $idCountResult = $this->model->getChildCountInPids(['pid' => $pids]);
            $idCountResult = $idCountResult->toArray();
            $idCounts = [];
            foreach ($idCountResult as $countResult) {
                $idCounts[$countResult['pid']] = $countResult['count'];
            }
        }
        if ($result['data']) {
            foreach ($result['data'] as $k => $v) {
                $result['data'][$k]['childCount'] = $idCounts[$v['id']] ?? 0;
            }
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

    public function listorder($id, $listorder)
    {
        $res = $this->getById($id);
        if (!$res) {
            throw  new  \think\Exception('不存在该条记录');
        }
        $data = [
            'listorder' => $listorder
        ];
        try {
            $res = $this->model->updateById($id, $data);
        } catch (\Exception $e) {
            return false;
        }
        return $res;
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
            return false;
        }
        return $res;
    }

    public function getNormalByPid($pid = 0, $field = "id, name, pid")
    {
        //$field = "id,name,pid";
        try {
            $res = $this->model->getNormalByPid($pid, $field);
        } catch (\Exception $e) {
            // 记得记录日志。
            return [];
        }
        $res = $res->toArray();
        return $res;
    }

    public function getCategoryRecommend($categoryId)
    {
        $where = [
            'id' => $categoryId
        ];
        $field = "id as category_id,pid,name,icon";
        $whereOr = [
            'pid' => $categoryId
        ];
        try {
            $res = $this->model->getCategoryRecommend($where, $whereOr, $field);
        } catch (\Exception $e) {
            $res = [];
        }

        return $res->toArray();
    }
    public function getSubCategoryRecommend($categoryId)
    {

        $field = "id,name";

        try {
            $res = $this->model->getNormalByPid($categoryId, $field);
        } catch (\Exception $e) {
            $res = [];
        }

        return $res->toArray();
    }
    public function getSearchCategory($categoryId)
    {
        //当前分类
        $where = ['id' => $categoryId];
        $filed = 'id,name,pid';
        try {
        $category = $this->model->getSearchCategory($where, $filed);
        $result = $category->toArray(); //当前分类
        $resultList = [];
        $resultList['name'] = $result[0]['name'];

        if (!$result[0]['pid'])  // pid=0 一级分类
        {
            $where = ['pid' => $result[0]['id']];
            $twoCategory = $this->model->getSearchCategory($where, $filed)->toArray(); //二级分类
            $threeCategory = $this->getSubCategory($twoCategory, $filed); //三级分类
            $resultList['focus_ids'] = [$twoCategory[0]['id'], $threeCategory[0]['id']];
            $resultList['list'] = [$twoCategory, $threeCategory];
        } elseif ($result[0]['pid']) { //二级

            $where = ['id' => $result[0]['pid']];
            $category = $this->model->getSearchCategory($where, $filed)->toArray(); //上级分类
            if (!$category[0]['pid'])  // pid=0 当前二分类
            {
                $where = ['pid' => $category[0]['id']];
                $twoCategory = $this->model->getSearchCategory($where, $filed)->toArray(); //同及分类  二级
                $threeCategory = $this->getSubCategory($twoCategory, $filed); //三级分类
                $resultList['focus_ids'] = [$twoCategory[0]['id'], $threeCategory[0]['id']];
                $resultList['list'] = [$twoCategory, $threeCategory];
            } else { //当前三级
                $where = ['pid' => $category[0]['pid']]; //上级上级分类id
                $twoCategory = $this->model->getSearchCategory($where, $filed)->toArray(); //二级分类
                $threeCategory = $this->getSubCategory($twoCategory, $filed); //三级分类
                $resultList['focus_ids'] = [$category[0]['id'], intval($categoryId)];
                $resultList['list'] = [$twoCategory, $threeCategory];
            }

        }
        }catch (\Exception $e){
            $resultList=[];
        }

        return $resultList;

    }

    /**
     * @param array $twoCategory
     * @param string $filed
     * @return array
     */
    protected function getSubCategory($twoCategory = [], $filed = '')
    {
        $res = [];
        $arr = [];
        foreach ($twoCategory as $k => $v) {
            $where = ['pid' => $v['id']];
            $res[$k] = $this->model->getSearchCategory($where, $filed)->toArray(); //三级
        }

        foreach ($res as $v) {
            foreach ($v as $item) {
                $arr[] = $item;
            }
        }
        return $arr;
    }
}