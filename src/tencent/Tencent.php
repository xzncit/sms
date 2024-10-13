<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\tencent;

use TencentCloud\Sms\V20210111\SmsClient;
use TencentCloud\Sms\V20210111\Models\SendSmsRequest;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use xzncit\exception\ConfigNotFoundException;


/**
 * @package xzncit\tencent
 * @class Tencent
 * @author xzncit 2024-01-19
 */
class Tencent {

    private $client = null;
    private $appid  = null;

    /**
     * Tencent constructor.
     * @param array $options
     * @throws ConfigNotFoundException
     */
    public function __construct($options=[]){
        if(empty($options["accessKeyId"])){
            throw new ConfigNotFoundException("TencentSMS【accessKeyId】字段不能为空");
        }

        if(empty($options["accessKeySecret"])){
            throw new ConfigNotFoundException("TencentSMS【accessKeySecret】字段不能为空");
        }

        if(empty($options["appid"])){
            throw new ConfigNotFoundException("TencentSMS【appid】字段不能为空");
        }

        $this->appid  = $options["appid"];
        $this->client = new Credential($options["accessKeyId"], $options["accessKeySecret"]);
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
     *      "templateParam" => ["1234"]
     * ])
     */
    public function send($params=[]){
        try{
            $client                 = new SmsClient($this->client, "ap-guangzhou");
            $req                    = new SendSmsRequest();
            $req->SmsSdkAppId       = (string)$this->appid; // 应用 ID 可前往 短信控制台->应用管理->应用列表
            $req->SignName          = (string)$params["singName"]; // 短信签名
            $req->TemplateId        = (string)$params["templateCode"]; // 模板 ID
            $req->TemplateParamSet  = $params["templateParam"]; // 模板参数: 模板参数的个数需要与 TemplateId 对应模板的变量个数保持一致，若无模板参数，则设置为空
            $req->PhoneNumberSet    = ["+86" . $params["mobile"]]; // 下发手机号码，采用 E.164 标准，+[国家或地区码][手机号]
            $req->SessionContext    = ""; // 用户的 session 内容（无需要可忽略）: 可以携带用户侧 ID 等上下文信息，server 会原样返回
            $req->ExtendCode        = ""; // 短信码号扩展号（无需要可忽略）: 默认未开通，如需开通请联系 [腾讯云短信小助手]
            $req->SenderId          = ""; // 国内短信无需填写该项；国际/港澳台短信已申请独立 SenderId 需要填写该字段，默认使用公共 SenderId，无需填写该字段。注：月度使用量达到指定量级可申请独立 SenderId 使用，详情请联系 [腾讯云短信小助手](https://cloud.tencent.com/document/product/382/3773#.E6.8A.80.E6.9C.AF.E4.BA.A4.E6.B5.81)。
            $response               = $client->SendSms($req);

            /*
             {
                "Response": {
                    "RequestId": "a0aabda6-cf91-4f3e-a81f-9198114a2279",
                    "SendStatusSet": [
                        {
                            "Code": "Ok",
                            "Fee": 1,
                            "IsoCode": "CN",
                            "Message": "send success",
                            "PhoneNumber": "+8618511122233",
                            "SerialNo": "5000:1045710669157053657849499619",
                            "SessionContext": "test"
                        },
                        {
                            "Code": "Ok",
                            "Fee": 1,
                            "IsoCode": "CN",
                            "Message": "send success",
                            "PhoneNumber": "+8618511122266",
                            "SerialNo": "5000:1045710669157053657849499718",
                            "SessionContext": "test"
                        }
                    ]
                }
            }
             */
            $result = $response->getSendStatusSet();
            foreach($result as $value){
                if($value->Code == "LimitExceeded.PhoneNumberDailyLimit"){
                    throw new \Exception("当前手机号发送短信已达上限",0);
                }else if($value->Code == "Ok"){
                    return true;
                }
            }

            return true;
        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage(),0);
        }
    }

}