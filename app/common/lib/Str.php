<?php

namespace app\common\lib;

class Str
{

    /**
     * 生产登录token
     * @param $string
     * @return string
     */
    public static function getLoginToken($string)
    {
        //生产token
        $str=md5(uniqid(md5(microtime(true)),true));//不会重复字符串
        return sha1($str.$string);//加密
    }

}