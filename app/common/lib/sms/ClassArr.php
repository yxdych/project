<?php

namespace app\common\lib\sms;

class ClassArr
{
    public static function smsClassStat()
    {
        return [
            "ali" => "app\common\lib\sms\AliSms"
        ];
    }

    public static function initClass($type, $classs, $params = [], $needInstance = false)
    {
        if (!array_key_exists($type, $classs)) {
            return false;
        }
        $className = $classs[$type];
        return $needInstance == true ? (new \ReflectionClass($className))->newInstanceArgsw($params) : $className;
    }
    }