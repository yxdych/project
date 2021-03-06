<?php

namespace app\admin\controller;

use app\common\model\mysql\AdminUser;



class  login extends AdminBase
{

    public function  initialize()
    {
//        return parent::initialize(); // TODO: Change the autogenerated stub
    if ($this->isLogIn()){
        return $this->redirect(url('index/index'));
     }
    }

    public function index()
    {

        return view();
    }

    public function md5()
    {
        halt(session(config('admin.session_admin')));
        return md5('admin_singwa_');
    }

    public function check()
    {
        if (!$this->request->isPost()) {
            return show(config('status.error'), '请求方式错误');
        }
        //参数校验 1.原生方式 2.TP6验证机制
        $username = $this->request->param('username', '', 'trim');
        $password = $this->request->param('password', '', 'trim');
        $captcha = $this->request->param('captcha', '', 'trim');
//        if (empty($username) || empty($password) || empty($captcha)) {
//            return show('status.error', '参数不能为空');
//        }
//        //需要校验验证码
//        if (!captcha_check($captcha)) {
//            // 验证失败
//            return show('status.error', '验证码不正确');
//        }
        $data=[
            'username'=>$username,
            'password'=>$password,
            'captcha'=>$captcha,
        ];
        $validate=new \app\admin\validate\AdminUser();
        if (!$validate->check($data)){
            return show('status.error', $validate->getError());
        }
        //调用模型
        try {
        $userModel= new  AdminUser();
        $adminUser= $userModel->getAdminUserByUsername($username);
        if (empty($adminUser)||$adminUser['status']!=config('status.mysql.table_normal')) {

            return  show(config('status.error'),'不存在该用户');
        }
        //验证密码
        $adminUser=$adminUser->toArray();
        if ($adminUser['password']!=md5($password.'_singwa_') ){
            return  show(config('status.error'),'密码错误');
        }
        //更新数据
        $data=[
            'last_login_time'=>time(),
            'last_login_ip'=>$this->request->ip(),
            'update_time'=>time()
        ];
        $res=$userModel->updateById($adminUser['id'],$data);
        if (empty($res)){
            return show(config('status.error'), '登录失败');
        }
        }catch (\Exception $e) {
            return show(config('status.error'), '内部错误,登录失败');
        }
        session(config('admin.session_admin'),$adminUser);
        //
        return show(config('status.status'), '登录成功');
    }
}