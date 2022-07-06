<?php

namespace app\admin\middleware;

use think\facade\Session;

class Auth
{

    public function handle($request, \Closure $next)
    {
//        Session::clear();
//
        //前置
        if (empty( session(config('admin.session_admin')))&&!preg_match('/login/',$request->pathinfo())){
             return  redirect((string)url('login/index'));
        }

        $response = $next($request);
        //后置
//     if (empty( session(config('admin.session_admin')))&&$request->controller()!='Login'){
//         return  redirect((string)url('admin/login/index'));
//     }
        return $response;
    }
}