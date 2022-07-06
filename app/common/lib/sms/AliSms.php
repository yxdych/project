<?php
declare(strict_types=1);

namespace app\common\lib\sms;

use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;

use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;
use Decimal\Decimal;
use GuzzleHttp\Exception\ClientException;
use think\facade\Log;


class AliSms implements SmsBase
{
    /**
     * @param string $phoneNumber
     * @param int $code
     * @return bool
     */
    public static function main(string $phoneNumber, int $code): bool
    {
        if (empty($phoneNumber) || empty($code)) {
            return false;
        }
        $templateCode = [
            'code' => $code
        ];

        $client = self::createClient(config('sms.accessKeyId'), config('sms.accessKeySecret'));
        $sendSmsRequest = new SendSmsRequest([
            "signName" => config('sms.signName'),
            "templateCode" => config('sms.templateCode'),
            "phoneNumbers" => $phoneNumber,
            "templateParam" => json_encode($templateCode)
        ]);
        // 复制代码运行请自行打印 API 的返回值
        $res = $client->sendSms($sendSmsRequest);
        if ($res->body->code != 'OK' && $res->body->message != 'OK') {
//            Log::error('发送失败'.json_encode($res,true));
            return false;
        }
//         Log::info('发送成功');
        return true;
    }

    /**
     * @param string $accessKeyId
     * @param string $accessKeySecret
     * @return object
     */
    public static function createClient(string $accessKeyId, string $accessKeySecret): object
    {
        $config = new Config([
            // 您的AccessKey ID
            "accessKeyId" => $accessKeyId,
            // 您的AccessKey Secret
            "accessKeySecret" => $accessKeySecret
        ]);
        // 访问的域名
        $config->endpoint = "dysmsapi.aliyuncs.com";
        return new Dysmsapi($config);
    }
}