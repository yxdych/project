<?php

namespace app\admin\validate;


use think\Validate;

class Goods extends Validate
{
        protected $rule=[
            'title'=>'require|max:25',
            'category_id'=>'require',
            'sub_title'=>'require|max:25',
            'promotion_title'=>'require|max:25',
            'keywords'=>'require|max:25',
            'goods_unit'=>'require|max:1',
            'is_show_stock'=>'number|between:0,1',
            'stock'=>'number|between:1,999',
            'production_time'=>'number',
            'goods_specs_type'=>'number|between:1,2',
            'big_image'=>'require',
            'carousel_image'=>'require',
            'recommend_image'=>'require',
            'skus'=>'require',
            'description'=>'require',
            'add_spec_arr'=>'require',
            'is_index_recommend'=>'require'
        ];
        protected $message=[
            'title'=>'商品标题不能为空',
            'category_id'=>'商品分类不能为空',
            'sub_title'=>'副标题不能为空',
            'promotion_title'=>'商品促销语不能为空',
            'keywords'=>'关键词不能为空',
            'goods_unit'=>'商品单位不能为空',
            'is_show_stock'=>'是否显示库存不能为空',
            'stock'=>'库存不能为空',
            'production_time'=>'生产日期不能为空',
            'goods_specs_type'=>'商品规则 1统一，2多规格不能为空',
            'big_image'=>'大图不能为空',
            'carousel_image'=>'详情页轮播图不能为空',
            'recommend_image'=>'商品推荐图不能为空',
            'skus'=>'规格属性不能为空',
            'description'=>'商品详情不能为空',
            'is_index_recommend'=>'首页推荐状态不能为空'
        ];
        protected $scene = [
            'id_status' => ['id', 'status'],
            'id_index' => ['id', 'is_index_recommend'],

        ];
}