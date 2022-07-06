<?php

namespace app\demo\controller;

use app\BaseController;
use app\common\lib\snowflake\Snowflake;


class Test extends BaseController
{
        public  function index()
        {
            $snowflake = new  Snowflake(1, 1);

            $res=$snowflake->id();
            dd($res);
        }
}