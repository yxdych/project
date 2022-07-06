<?php

namespace app\common\model\mysql;


use think\Model;

class AdminUser extends Model
{
    /**
     * 自动生成写入时间
     * @var bool
     */
//    protected $table = 'mall_user';
    protected $autoWriteTimestamp=true;
    public function getAdminUserByPhoneNumber($phoneNumber)
    {
        if (empty($phoneNumber)) {
            return false;
        }
        $where = [
            'phone_number' => trim($phoneNumber)
        ];
        return $this->where($where)->find();
    }
    public function getAdminUserByUsername($username)
    {
        if (empty($username)) {
            return false;
        }
        $where = [
            'username' => trim($username)
        ];
        return $this->where($where)->find();
    }

    public function updateById($id,$data)
    {
        $id=intval($id);
        if (empty($id)||empty($data)||!is_array($data)){
            return false;
        }
        $where=[
            'id'=>$id
        ];
        return  $this->where($where)->save($data);
    }
    public function getUserById($id){
        $id=intval($id);
        if (!$id){
            return false;
        }
        return  $this->find($id);
    }
    public function getUserByUsername($username)
    {

        if (empty($username)) {
            return $username;
        }
        $where = [
            'username' => trim($username)
        ];
        return $this->where($where)->find();
    }
}