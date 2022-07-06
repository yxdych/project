<?php

namespace app\common\lib;

class Arr
{
    public static function getTree($data)
    {
        $items=[];
        foreach ($data as $v){
            $items[$v['category_id']]=$v;
        }
        foreach ($items as $id=>$item){
            if (isset($items[$item['pid']])){
                $items[$item['pid']]['list'][]=&$items[$id];
            }else{
                $tree[]=&$items[$id];
            }
        }
        return $tree;
    }
    /**
     * 分页默认返回的数据
     * @param $num
     * @return array
     */
    public static function getPaginateDefaultData($num) {
        $result = [
            "total" => 0,
            "per_page" => $num,
            "current_page" => 1,
            "last_page" => 0,
            "data" => [],
        ];
        return $result;
    }
    public static function getCategoryGroup($categoryDefaultId=[],$categoryGroup=[])
    {
        $result=[];
        foreach ($categoryGroup as $k => $v) {
            foreach ($v['categorys'] as $key =>$item){
                if ($item['category_id']==$categoryDefaultId[$k])
                {
                    $result[$k]['categorys']['category_id']=$item['category_id'];
                    $result[$k]['categorys']['name']=$item['name'];
                    $result[$k]['categorys']['icon']=$item['icon'];
                }elseif($item['pid']==$categoryDefaultId[$k]){
                    $result[$k]['categorys']['list'][]=$item;
                }
//                $result[$k]['goods']=$v['goods'];
            }
        }
        return $result;
    }
}