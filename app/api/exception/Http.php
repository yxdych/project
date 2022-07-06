<?php

namespace app\api\exception;

use think\db\exception\PDOException;
use think\exception\Handle;
use think\Response;
use Throwable;

class Http extends Handle
{
    public $httpStatus = 500;
    public function render($request, Throwable $e): Response
    {
        //reids数据里异常
        if ($e instanceof  PDOException){

            return  show(config('status.error'),$e->getMessage(),'',$this->httpStatus);
        }
        // 添加自定义异常处理机制
        // 其他错误交给系统处理
        return parent::render($request, $e);
    }
}