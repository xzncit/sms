<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\aliyun;

use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;
use AlibabaCloud\Tea\Exception\TeaError;
use AlibabaCloud\Tea\Utils\Utils;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;
use Darabonba\OpenApi\Models\Config;
use xzncit\exception\ConfigNotFoundException;

/**
 * @package xzncit\aliyun
 * @class Aliyun
 * @author xzncit 2024-01-19
 */
class Aliyun {

    private $client = null;

    /**
     * Aliyun constructor.
     * @param array $options
     * @throws ConfigNotFoundException
     */
    public function __construct($options=[]){
        if(empty($options["accessKeyId"])){
            throw new ConfigNotFoundException("AliyunSMS【accessKeyId】字段不能为空");
        }

        if(empty($options["accessKeySecret"])){
            throw new ConfigNotFoundException("AliyunSMS【accessKeySecret】字段不能为空");
        }

        $config = new Config([ "accessKeyId" => $options["accessKeyId"], "accessKeySecret" => $options["accessKeySecret"] ]);
        $config->endpoint = "dysmsapi.aliyuncs.com";
        $this->client = new Dysmsapi($config);
    }

    /**
     * 发送短信
     * @param array $params
     * @return bool
     * @throws \Exception
     * @example
     * send([
     *      "mobile"        => "18000000000",
     *      "singName"      => "签名模板",
     *      "templateCode"  => "SMS_100000",
     *      "templateParam" => '{"code":"1234"}'
     * ])
     */
    public function send($params=[]){
        try {
            $sendSmsRequest = new SendSmsRequest([
                "phoneNumbers"      => $params["mobile"],
                "signName"          => $params["singName"],
                "templateCode"      => $params["templateCode"],
                "templateParam"     => $params["templateParam"]
            ]);

            $runtime = new RuntimeOptions([]);
            $response = $this->client->sendSmsWithOptions($sendSmsRequest, $runtime);
            if($response->statusCode != 200){
                throw new \Exception('code：' . $response->body->code . ' msg: ' . $response->message,0);
            }

            if($response->body->code == "OK" && $response->body->message == "OK"){
                return true;
            }

            return false;
        } catch (\Exception $error) {
            if (!($error instanceof TeaError)) {
                $error = new TeaError([], $error->getMessage(), $error->getCode(), $error);
            }
            
            throw new \Exception($error->getMessage(),0);
        }
    }

}