<?php

namespace app\api\controller;

class Error
{
    public function __call($method, $args)
    {
        return show(config('status.error'), "找不到该{$method}", null, 400);
    }

}