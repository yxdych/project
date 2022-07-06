<?php

namespace app\admin\controller;


class Goods extends AdminBase
{
    public function index()
    {
        $title=input('title','','trim');
        $time=input('time','','trim');
        $data=[];
        if (!empty($title)){
            $data['title']=$title;
        }
        if (!empty($time)){
            $data['create_time']=explode(" - ",$time);
        }
        $goods=(new  \app\common\business\Goods())->getLists($data,1);
        return view('',['goods'=>$goods,'data'=>$data]);
    }
    public  function  add()
    {
        return view();
    }
    public function save(){

        if (!$this->request->isPost()){
            return show(config('status.error','非法请求'));
        }
        $title=input('title','','trim');
        $category_id=input('category_id','','trim');
        $sub_title=input('sub_title','','trim');
        $promotion_title=input('promotion_title','','trim');
        $keywords=input('keywords','','');
        $goods_unit=input('goods_unit','','intval');
        $is_show_stock=input('is_show_stock','','intval');
        $stock=input('stock','','trim');
        $production_time=input('production_time','','strtotime');
        $goods_specs_type=input('goods_specs_type','','intval');
        $big_image=input('big_image','','trim');
        $carousel_image=input('carousel_image','','trim');
        $recommend_image=input('recommend_image','','trim');
        $skus=input('skus','','trim');
        $description=input('description','','trim');
        $add_spec_arr=input('add_spec_arr','','trim');
        $data=[
            'title'=>$title,
            'category_id'=>$category_id,
            'sub_title'=>$sub_title,
            'promotion_title'=>$promotion_title,
            'keywords'=>$keywords,
            'goods_unit'=>$goods_unit,
            'is_show_stock'=>$is_show_stock,
            'stock'=>$stock,
            'production_time'=>$production_time,
            'goods_specs_type'=>$goods_specs_type,
            'big_image'=>$big_image,
            'carousel_image'=>$carousel_image,
            'recommend_image'=>$recommend_image,
            'skus'=>$skus,
            'goods_specs_data'=>json_encode($skus),
            'description'=>$description,
            'add_spec_arr'=>$add_spec_arr,
            'is_index_recommend'=>1,
        ];
        $token = $this->request->checkToken('__token__');
//        if(false === $token) {
//            return show(config('status.error'), '非法请求');
//        }
        $validate=new  \app\admin\validate\Goods();
        if(!$validate->check($data))
        {
            return show(config('status.error'), $validate->getError());
        }
        $data['category_path_id']=$data['category_id'];
        $result=explode(',',$data['category_path_id']);
        $data['category_id']=end($result);
        try {
            $res=(new  \app\common\business\Goods())->insetdata($data);
        }catch (\Exception $e){
            return show(config('status.error'), $e->getMessage());
        }
        if (!$res)
        {
            return show(config('status.error'), '商品新增失败');
        }
        return show(config('status.status'), '商品新增成功');

    }

    public function status()
    {
        $id = input('id', '', 'intval');
        $status = input('status', '', 'trim');
        $data=[
            'id'=>$id,
            'status'=>$status
        ];
        $validate=new \app\admin\validate\Goods();
        if (!$validate->scene('id_status')->check($data)) {
            return show(config('status.error'), $validate->getError());
        }
        $res = (new \app\common\business\Goods())->status($id, $status);
        if ($res) {
            return show(config('status.status'), '状态更新成功');
        } else {
            return show(config('status.error'), '状态更新失败');
        }
    }
    public function  indexStatus()
    {
        $id = input('id', '', 'intval');
        $is_index_recommend = input('is_index_recommend', '', 'trim');
        $data=[
            'id'=>$id,
            'is_index_recommend'=>$is_index_recommend
        ];
        $validate=new \app\admin\validate\Goods();
        if (!$validate->scene('id_index')->check($data)) {
            return show(config('status.error'), $validate->getError());
        }
        $res = (new \app\common\business\Goods())->indexStatus($id, $is_index_recommend);
        if ($res) {
            return show(config('status.status'), '状态更新成功');
        } else {
            return show(config('status.error'), '状态更新失败');
        }
    }
}