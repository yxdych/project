<?php
declare(strict_types=1);

namespace app\common\business;

use app\common\lib\Num;
use app\common\lib\sms\AliSms;
use app\common\lib\sms\ClassArr;


class Sms
{
    /***
     * @param string $phoneNumber
     * @return bool
     */
    public static function senCode(string $phoneNumber, int $len, $type = 'ali'): bool
    {
        //验证码
        $code = Num::getCode($len);
        //发送短信 调用lib层
//        $sms = AliSms::main($phoneNumber, $code);
//        工厂模式
//        $type = ucfirst($type);
//        $class = "app\common\lib\sms\\" . $type . 'Sms';
//        $sms = $class::main($phoneNumber, $code);
        $classStats=ClassArr::smsClassStat();
        $classObj=ClassArr::initClass($type,$classStats);
        $sms=$classObj::main($phoneNumber, $code);
        if ($sms) {
            //缓存到redis
            cache(config('redis.code_pre') . $phoneNumber, $code, config('redis.c   ode_expire'));
        }
        return $sms;
    }

}