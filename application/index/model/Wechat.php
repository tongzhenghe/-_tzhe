<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/22
 * Time: 18:15
 */

namespace app\index\model;


use think\Db;

class Wechat extends Common
{
    private static $app_id = 'wx59cc261df4893e92';

    private static $AppSecret = 'd5d2ea9132bf9d60fb192093b7285562';

    const TEMP_API = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=';

    /**
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function get_access_token()
    {
        $base = Db::name('wechat')->where(['id' => 1 ])->find();
        if (!empty($base['access_token'])) {
            if ( time() < $base['expire_time'] ) {
                //过期时间：7200秒
                return $base['access_token'];
            }
        }
        $access_token_api = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.self::$app_id.'&secret='.self::$AppSecret;
        $data = self::httpGet($access_token_api);
        if (!empty($data->access_token)) {
            //存数据库
            $wechat_data = [
                'access_token' => $data->access_token
                ,'expire_time' => time() +( intval($data->expires_in)-1000)
            ];

            $result = Db::name('wechat')->insert($wechat_data);
            if (true == $result)
                return $data->access_token;
        }
    }

    /**
     * @param $url
     * @return mixed
     */
//    public  static  function  httpPost( $url )
//    {
//        //初始化
//        $curl = curl_init();
//        //设置抓取的url
//        curl_setopt($curl, CURLOPT_URL, $url);
//        //设置头文件的信息作为数据流输出
//        curl_setopt($curl, CURLOPT_HEADER, false);
//        //设置获取的信息以文件流的形式返回，而不是直接输出。
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//        //设置post方式提交
//        curl_setopt($curl, CURLOPT_POST, true);
//        //执行命令
//        $data = json_decode( curl_exec($curl) );
//        //关闭URL请求
//        curl_close($curl);
//        //显示获得的数据
//        if ($data) {
//            return $data;
//        } else {
//            echo $data->errmsg;
//        }
//
//
//    }

    /**
     * @param $url
     * @return mixed
     */
    public  static  function  httpGet( $url )
    {
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, false);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        if ($data) {
            return $data;
        } else {
            echo $data->errmsg;
        }

    }

    /**
     * 微信模板消息发送
     * @param $openid: 接收用户的openid
     * return 发送结果
     */
    public static function send( $openid, $param) {

/*
        $openid = '接收模板信息的用户的openid';
        $param['template_id'] = 'sddsfderwdsd';
        $param['data'] = [
            'first' =>
                [
                    'value' => '您好!您的审批未通过。'
                    ,'color' => '#173177'
                ]
            ,'user' =>
                [
                    'value' => '张三'
                    ,'color' => '#FF0000'
                ]

            ,'ask' =>
                [
                    'value' => '您好,饿哦是的手机号看?'
                    ,'color' => '#173177'
                ]
            ,'remark' =>
                [
                    'value' => '该用户已注册12天'
                    ,'color' => 'blue'
                ]
        ];
        Wechat::send($openid, $param);


        exit;*/





        $token = self::get_access_token();
        $api = self::TEMP_API . $token;
        $params = [
            'touser' => $openid
            ,'template_id' => $param['template_id']
            ,'url' => 'http://jqzxyy.com'
            ,'data' => $param['data']
        ];

        wl_debug($params);

        //当用户进入微信

        $json = json_encode($params,JSON_UNESCAPED_UNICODE);

        return self::curlPost($api, $json);

    }

    /**
     * 通过CURL发送数据
     * @param $url:请求的URL地址
     * @param $data: 发送的数据
     * return 请求结果
     */
    protected function curlPost($url,$data)
    {
        $ch = curl_init();
        $params[CURLOPT_URL] = $url;    //请求url地址
        $params[CURLOPT_HEADER] = FALSE; //是否返回响应头信息
        $params[CURLOPT_SSL_VERIFYPEER] = false;
        $params[CURLOPT_SSL_VERIFYHOST] = false;
        $params[CURLOPT_RETURNTRANSFER] = true; //是否将结果返回
        $params[CURLOPT_POST] = true;
        $params[CURLOPT_POSTFIELDS] = $data;
        curl_setopt_array($ch, $params); //传入curl参数
        $content = curl_exec($ch); //执行
        curl_close($ch); //关闭连接
        return $content;
    }

}