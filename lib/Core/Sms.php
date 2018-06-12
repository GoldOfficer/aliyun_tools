<?php
/**
 * Created by PhpStorm.
 * User: Baihuzi
 * Date: 2018/6/12
 * Time: 11:49
 */

namespace MyOK\AliyunTools\Core;

use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;
use Aliyun\Api\Sms\Request\V20170525\SendBatchSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Core\Config;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Core\Profile\DefaultProfile;

class Sms
{
    static    $acsClient = null;
    protected $config    = [];
    
    public function __construct($config = [])
    {
        $this->config = $config;
        static::loadConfig();
        $this->getAcsClient($config);
    }
    
    private static function loadConfig()
    {
        Config::load();
    }
    
    public function getAcsClient()
    {
        $product         = "Dysmsapi";
        $domain          = "dysmsapi.aliyuncs.com";
        $accessKeyId     = $this->config['AccessKeyId']; // AccessKeyId
        $accessKeySecret = $this->config['AccessKeySecret']; // AccessKeySecret
        $region          = $this->config['region'];
        $endPointName    = $this->config['endPointName'];
        
        if (static::$acsClient == null) {
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);
            static::$acsClient = new DefaultAcsClient($profile);
        }
        
        return static::$acsClient;
    }
    
    public function sendSms($phoneNumber,
                            $templateParam = [],
                            $templateCode = '',
                            $smsSign = '',
                            $https = true,
                            $outId = 'yourOutId',
                            $smsUpExtendCode = '1234567')
    {
        
        $request = new SendSmsRequest();
        if ($https) {
            $request->setProtocol("https");
        }
        $request->setPhoneNumbers($phoneNumber);
        $request->setSignName($smsSign);
        $request->setTemplateCode($templateCode);
        $request->setTemplateParam(
            json_encode(
                $templateParam,
                JSON_UNESCAPED_UNICODE
            )
        );
        $request->setOutId($outId);
        $request->setSmsUpExtendCode($smsUpExtendCode);
        
        $acsResponse = $this->getAcsClient()->getAcsResponse($request);
        
        return $acsResponse;
    }
    
    public function sendBatchSms($phoneNumbers = [],
                                 $templateParam = [],
                                 $templateCode = '',
                                 $smsSign = [],
                                 $https = true,
                                 $smsUpExtendCode = [])
    {
        $request = new SendBatchSmsRequest();
        
        if ($https) {
            $request->setProtocol("https");
        }
        $request->setPhoneNumberJson(
            json_encode(
                $phoneNumbers,
                JSON_UNESCAPED_UNICODE
            )
        );
        $request->setSignNameJson(
            json_encode(
                $smsSign,
                JSON_UNESCAPED_UNICODE
            )
        );
        
        $request->setTemplateCode($templateCode);
        
        $request->setTemplateParamJson(
            json_encode(
                $templateParam,
                JSON_UNESCAPED_UNICODE
            )
        );
        if ($smsUpExtendCode) {
            $request->setSmsUpExtendCodeJson(json_encode($smsUpExtendCode, JSON_UNESCAPED_UNICODE));
        }
        
        $acsResponse = $this->getAcsClient()->getAcsResponse($request);
        
        return $acsResponse;
    }
    
    public function querySendDetails($phoneNumber,
                                     $date = '',
                                     $currentPage = 1,
                                     $pageSize = '10',
                                     $https = true,
                                     $bizId = 'yourBizId')
    {
        $request = new QuerySendDetailsRequest();
        
        if ($https) {
            $request->setProtocol("https");
        }
        
        $request->setPhoneNumber($phoneNumber);
        $request->setSendDate($date);
        $request->setPageSize($pageSize);
        $request->setCurrentPage($currentPage);
        $request->setBizId($bizId);
        
        $acsResponse = $this->getAcsClient()->getAcsResponse($request);
        
        return $acsResponse;
    }
}