<?php
declare(strict_types=1);
namespace app\common\business;

use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\AddShortUrlResponseBody\data;
use app\common\lib\Str;
use app\common\lib\Time;
use Darabonba\GatewaySpi\Models\InterceptorContext\response;
use think\Exception;
use think\facade\Request;

class User
{
    public $userObj = null;

    public function __construct()
    {
        $this->userObj = new \app\common\model\mysql\User();
    }

    /**
     * @param $data
     * @return array|false
     * @throws Exception
     */
    public function login($data)
    {
        $redisCode = cache(config('redis.code_pre') . $data['phone_number']);
        if (empty($redisCode) || $redisCode != $data['code']) {
//            throw new \think\Exception('不存在该验证码','-1');
//            throw new \think\Exception\HttpException('1','不存在该验证码');
        }
        $user = $this->userObj->getAdminUserByPhoneNumber($data['phone_number']);
        if (!$user) {
            $username = "si_w_" . $data['phone_number'];
            $userData = [
                'username' => $username,
                'phone_number' => $data['phone_number'],
                'type' => $data['type'],
                'status' => config('status.mysql.table_normal'),
            ];

            try {
                $this->userObj->save($userData);
            } catch (\Exception $e) {
                throw   new  Exception('数据库内部异常', 500);
            }
        } else {
            $userid = $user->id;
            $username = $user->username;
            $upData = [
                'last_login_ip' => Request::ip()
            ];
            $this->userObj->updateById($userid, $upData);
        }
        $token = Str::getLoginToken($data['phone_number']);
        $redisData = [
            'id' => $userid,
            'username' => $username,
            'token' => $token,
        ];
        $res = cache(config('redis.token_pre') . $token, $redisData,Time::userLoginExpiresTime($data['type']));
        return $res ? ['token' => $token, 'username' => $username] : false;
    }

    public function getNormalUserById($id){
        $user=$this->userObj->getUserById($id);
        if (!$user||$user->status!=config('status.mysql.table_normal')){
            return [];
        }
        return $user->toArray();
    }
    public function getNormalUserByUsername($username){
        $user=$this->userObj->getUserByUsername($username);

        if (!$user||$user->status!=config('status.mysql.table_normal')){
            return [];
        }
        return $user->toArray();
    }
    public function update($id,$data)
    {
        $user=$this->getNormalUserById($id);

        if (!$user){
            throw   new  Exception('不存在该用户');
        }

        $userResult=$this->getNormalUserByUsername($data['username']);

        if ($userResult&&$userResult['id']!=$id){

            throw   new  Exception('该用已存在，请重新设置');
        }
        return $this->userObj->updateById($id,$data);
    }
}