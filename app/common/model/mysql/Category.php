<?php

namespace app\common\model\mysql;

use think\db\Where;
use think\Model;

class Category extends Model
{
    protected $autoWriteTimestamp = true;

    /**
     * @param string $filed
     * @return Category[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalCategorys($filed = '*')
    {
        $where = [
            'status' => config('status.mysql.table_normal')
        ];
        return $this->where($where)->field($filed)->select();

    }

    public function getLists($where, $num = 10)
    {
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        $result = $this->where('status', '<>', config('status.mysql.table_delete'))
            ->where($where)
            ->order($order)
            ->paginate($num);
        return $result;
    }

    public function updateById($id, $data)
    {

        $data['update_time'] = time();
        return $this->where(['id' => $id])->save($data);
    }

    public function getChildCountInPids($condition)
    {
        $where[] = ['pid', 'in', $condition['pid']];
        $where[] = ['status', '<>', config('status.mysql.table_delete')];
        $res = $this->where($where)
            ->field(['pid', 'count(*) as count'])
            ->group('pid')
            ->select();

        return $res;

    }

    public function getNormalByPid($pid = 0, $field)
    {
        $where = [
            "pid" => $pid,
            "status" => config("status.mysql.table_normal"),
        ];
        $order = [
            "listorder" => "desc",
            "id" => "desc"
        ];

        $res = $this->where($where)
            ->field($field)
            ->order($order)
            ->select();
        return $res;
    }


    public function getSearchCategory($where,$filed = '*')
    {
        $where[ 'status']= config('status.mysql.table_normal');
        $res=$this->where($where)->field($filed)->select();
        return $res;

    }

}