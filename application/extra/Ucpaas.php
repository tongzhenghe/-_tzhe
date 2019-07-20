<?php

/**
 * Created by Notepad++
 * User: UCPAAS NickLuo
 * Date: 2017/11/09
 * Time: 08:28
 * Dec : ucpass php sdk
 */
class Ucpaas
{
    //API请求地址
    const BaseUrl = "https://open.ucpaas.com/ol/sms/";
    private static $accountSid = '14ae0d4aebc14dd1481a70ceeaf4ab20';
    private static $token = '8055bc63fa2a078d906e849573d318a0';
    private static $appid = '7cebe95f55f5475d8afc3e7f7d606297';
    //private static $templateid  = '459696';
    private static $uid = '';

   /* public function  __construct()
    {
        $options['accountsid'] = '14ae0d4aebc14dd1481a70ceeaf4ab20';
        $options['token'] = '8055bc63fa2a078d906e849573d318a0';
        if (is_array($options) && !empty($options)) {
           self::$accountSid = !empty($options['accountsid']) ? $options['accountsid'] : '';
            self::$token = !empty($options['token']) ? $options['token'] : '';
        } else {
            throw new Exception("非法参数");
        }
    }*/
    private static function getResult($url, $body = null, $method)
    {
        $data = self::connection($url,$body,$method);
        if (isset($data) && !empty($data)) {
            $result = $data;
        } else {
            $result = '没有返回数据';
        }
        return $result;
    }

    /**
     * @param $url    请求链接
     * @param $body   post数据
     * @param $method post或get
     * @return mixed|string
     */
	 
    private static  function connection($url, $body,$method)
    {
        if (function_exists("curl_init")) {

            $header = array(
                'Accept:application/json',
                'Content-Type:application/json;charset=utf-8',
            );
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            if($method == 'post'){
                curl_setopt($ch,CURLOPT_POST,1);
                curl_setopt($ch,CURLOPT_POSTFIELDS,$body);
            }
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $result = curl_exec($ch);
            curl_close($ch);
        } else {
            $opts = array();
            $opts['http'] = array();
            $headers = array(
                "method" => strtoupper($method),
            );
            $headers[]= 'Accept:application/json';
            $headers['header'] = array();
            $headers['header'][]= 'Content-Type:application/json;charset=utf-8';

            if(!empty($body)) {
                $headers['header'][]= 'Content-Length:'.strlen($body);
                $headers['content']= $body;
            }

            $opts['http'] = $headers;
            $result = file_get_contents($url, false, stream_context_create($opts));
        }
        return $result;
    }

    /**
	单条发送短信的function，适用于注册/找回密码/认证/操作提醒等单个用户单条短信的发送场景
     * @param $appid        应用ID
     * @param $mobile       接收短信的手机号码
     * @param $templateid   短信模板，可在后台短信产品→选择接入的应用→短信模板-模板ID，查看该模板ID
     * @param null $param   变量参数，多个参数使用英文逗号隔开（如：param=“a,b,c”）
     * @param $uid			用于贵司标识短信的参数，按需选填。
     * @return mixed|string 
     * @throws Exception
     */
    public static function SendSms($templateid, $mobile, $param=null ) {
        $url = self::BaseUrl . 'sendsms';
        $body_json = array(
            'sid'=> self::$accountSid,
            'token'=>self::$token,
            'appid'=>self::$appid,
            'templateid'=> $templateid,
			'param'=> $param,
			'mobile'=> $mobile,
			'uid'=>self::$uid,
        );
        $body = json_encode($body_json);
        $data = self::getResult($url, $body,'post');
        return $data;
    }
	
	 /**
	 群发送短信的function，适用于运营/告警/批量通知等多用户的发送场景
     * @param $appid        应用ID
     * @param $mobileList   接收短信的手机号码，多个号码将用英文逗号隔开，如“18088888888,15055555555,13100000000”
     * @param $templateid   短信模板，可在后台短信产品→选择接入的应用→短信模板-模板ID，查看该模板ID
     * @param null $param   变量参数，多个参数使用英文逗号隔开（如：param=“a,b,c”）
     * @param $uid			用于贵司标识短信的参数，按需选填。
     * @return mixed|string 
     * @throws Exception
     */
	public function SendSms_Batch($appid,$templateid,$param=null,$mobileList,$uid){
        $url = self::BaseUrl . 'sendsms_batch';
        $body_json = array(
            'sid'=>self::$accountSid,
            'token'=>self::$token,
            'appid'=>$appid,
            'templateid'=>$templateid,
			'param'=>$param,
			'mobile'=>$mobileList,
			'uid'=>$uid,
        );
        $body = json_encode($body_json);
        $data = self::getResult($url, $body,'post');
        return $data;
    }
} 