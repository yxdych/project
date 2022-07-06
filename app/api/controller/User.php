<?php

namespace app\api\controller;


use function Symfony\Component\VarDumper\Dumper\esc;

class User extends AuthBase
{

     public  function index()
     {
         $user= (new \app\common\business\User())->getNormalUserById($this->userId);
         $result=[
             'id'=>$user['id'],
             'username'=>$user['username'],
             'sex'=>$user['sex'],
         ];
         return show(config('status.status'), 'ok',$result);
     }
     public  function  update()
     {
          $username=input('param.username',"",'trim');
          $sex=input('sex','','trim');
          $data=[
              'username'=>$username,
               'sex'=>$sex
              ];
          $validate=(new \app\api\validate\User())->scene('update_user');
          if (!$validate->check($data)){
              return show(config('status.error'), $validate->getError());
          }

          $userBusObj=new  \app\common\business\User();
          $user= $userBusObj->update($this->userId,$data);
          if (!$user){
              return show(config('status.error'), '更新失败');
          }
         return show(1, 'ok');
     }
}