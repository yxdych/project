<?php

namespace app\admin\controller;

class Index extends AdminBase
{
    public function index()
    {
        return view();
    }

    public function welcome()
    {
        return view();
    }

    public function cs()
    {
        return '测试';
    }
}