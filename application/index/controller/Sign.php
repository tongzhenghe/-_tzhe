<?php/** * Created by PhpStorm. * User: asus * Date: 2019/4/9 * Time: 9:11 */namespace app\index\controller;use app\index\model\User;use think\Controller;use app\index\model\Sign as SignModel;use think\Cookie;use think\Db;use think\Session;class Sign extends  Controller{    public  function  signin()    {        //获取用户信息        wl_debug(Cookie::get());        if (request()->isAjax()) {            $params = request()->param();            $user_name = trim($params['user_name']);            $password = trim($params['password']);            if (empty( $user_name))                exit(iJson('', 400,  false, '请输入手机号码/用户名！'));            if (empty( $password))                exit(iJson('', 400,  false, '请输入密码'));            $result = SignModel::checkSign( $params );            if (true === $result ) {                //记录log:                $userInfo  = User::activeUserInfoOrFail();                User::insert_log($userInfo, '已经登陆');                $user = Db::name('user')->where('id', $userInfo['id'])->field('is_perfect')->find();                if ($user['is_perfect'] == 2 ) {                    $redirect_url = url('/index/perfect');                } else {                    $redirect_url = url('/index/personal');                }                exit(iJson($redirect_url, 200,  true,  '欢迎使用!'));            } else {                exit(iJson('', 400,  false, '系统错误！'));            }        }        if(!is_dir('./tmp/'))mkdir ('./tmp/', 0700);        session_save_path('./tmp/');        $sign_data = Session::get('sign_data');        $cookie_user_info = [            'user_name' =>  Cookie::get()            ,'pass' => Cookie::get('')        ];        if (!empty($sign_data))            $this->assign('sign_data', $sign_data);        return view();    }    //注册    public  function  register()    {        if (request()->param()) {            $params = request()->param();            $pa = intval(rand_captcha());            if(!is_dir('./tmp/'))mkdir ('./tmp/', 0700);            session_save_path('./tmp/');            if ($params['do']  == 'valid') {                if (request()->isAjax()) {                    $preg_phone = '/^1[34578]\d{9}$/ims';                    if(!preg_match($preg_phone,trim($params['tel'])))                        exit(iJson('', 400, false, '手机为空或格式不正确！'));                    $user_tel = Db::name('user')->where(['tel' => $params['tel']])->find();                    if (!empty($user_tel))                        exit(iJson('', 400, false, '此手机号已被注册！'));                    $templateid  = '459696';                    $sms = \Ucpaas::SendSms($templateid, $params['tel'], $pa.',60');                    $sms = (array)json_decode($sms);                    switch ($sms['code']) {                            case '105147':                            exit(ErrorJson( '操作过于频繁， 请于明日再试！'));                            break;                            case '100015':                            exit(ErrorJson('号码不合法， 仅支持国内手机号码'));                            break;                            case '000000':                                Session::set('sys_captcha', $pa);                                exit(json_encode(['code' => 200, 'tel' => $params['tel'] ]));                            break;                        default :                            exit(ErrorJson('系统错误'));                            break;                    }                }                return view('sign/valid');            }            //重发            if ($params['do'] == 'rewire') {                $templateid = '459696';                $sms = \Ucpaas::SendSms($templateid, $params['tel'], $pa.',60');                $sms = (array)json_decode($sms);                switch ($sms['code']) {                    case '105147':                        exit(ErrorJson( '操作过于频繁， 请于明日再试！'));                        break;                    case '100015':                        exit(ErrorJson('号码不合法， 仅支持国内手机号码'));                        break;                    case '000000':                        Session::set('sys_captcha', $pa);                        exit(json_encode(['code' => 200]));                        break;                    default :                        exit(ErrorJson('系统错误'));                        break;                }            }            if ($params['do'] == 'user_valid') {                //获取用户输入的验证码，&& 获取session验证码；比对                if (request()->isAjax()) {                    $user_captcha = $params['captcha'];                    $tel = $params['tel'];                    $sys_code = Session::get('sys_captcha');                    wl_debug_log($sys_code, 'x');                    if (intval($user_captcha) === intval($sys_code) ) {                       // Session::delete('sys_captcha');                        exit(iJson(url('/sign/register', ['do' => 'basic', 'tel' => $tel]),200, true, '注册成功'));                    } else {                        exit(iJson('', 400, false, '验证码错误'));                    }                }            }        }          if ($params['do'] == 'basic') {             $tel = request()->param('tel');              if (request()->isAjax()) {                if (empty($params['user_name'])) exit(iJson('', 400, 400, '请填写您的真实姓名'));                if (empty($params['password'])) exit(iJson('', 400, 400, '请填写密码'));                if ($params['repassword'] !== $params['password']) exit(iJson('', 400, 400, '两次填写密码不一致'));                if ($params['compid'] == 0) exit(iJson('', 400, 400, '请选择您所在公司'));                $user = Db::name('user')->where('user_name', $params['user_name'])->find();                  if (!empty($user))  exit(iJson('', 400, 400, '该用户名已被注册'));                    $data = [                        'state' => 2                        ,'is_del' => 1                        ,'compid' => intval($params['compid'])                        ,'tel' => $params['user_tel']                        ,'user_name' => trim($params['user_name'])                        ,'password' => md5($params['password'])                    ];                    $result = Db::name('user')->insert($data);                    if ($result) {                        Session::set('sign_data', $params);                        exit(iJson(url('/sign/signin')));                    }                    exit(false);                }            }        $company = Db::name('company')            ->where(['is_manage'=> 2, 'state' => 1, 'is_del' => 1])            ->field('comname, id')            ->select();        return view('',[                'company' => $company,                'user_tel' => $tel            ]        );    }    /**     * 退出     */    public  function  logout()    {        if (request()->isAjax()) {            $params = request()->param();            if (true == $params['logout']) {                //记录log                $userInfo  = User::activeUserInfoOrFail();                User::insert_log($userInfo,  '退出系统');                //清除session                //SignModel::clearSignInfo();                exit( iJson(url('/sign/signin'), 200, true, '退出中..'));            }        }    }}