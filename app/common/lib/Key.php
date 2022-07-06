<?php

namespace app\common\lib;

class Key
{
    public static function userCart($userId){
        return config('redis.cart_pre') . $userId;
    }
}