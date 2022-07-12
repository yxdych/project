<?php
namespace app\api\validate;
use think\Validate;
/**
 * 
 */
class Cart extends Validate
{
	
	protected $rule=[
		'id'=>'require|number',
		'num'=>'require|number',
	];
	protected $message=[
		'id.number'=>'参数类型错误',
		'num.number'=>'参数类型错误',
		'id.require'=>'缺少参数1',
		'num.require'=>'缺少参数2'
	];
	protected $scene=[
		 'add'=>['id','num'],
	];
}