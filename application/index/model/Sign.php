<?php/** * Created by PhpStorm. * User: Administrator * Date: 2019/4/3 * Time: 16:21 */namespace  app\index\model;use think\Model;use think\Session;use think\Db;use think\Cookie;class Sign extends Model{    /**     * 清空当前登陆用户信息     */    public static function clearSignInfo()    {        if(!is_dir('./tmp/'))mkdir ('./tmp/', 0700);        session_save_path('./tmp/');        Session::delete('userInfo');        Session::delete('userId');        Session::clear();    }    /**     *  保存当前登陆用户信息     */    public static function setUserInfo($userInfo)    {        Session::set('userId',$userInfo['id']);        Session::set('userInfo',$userInfo);    }    static  public  function  checkSign( $_user )    {        if(!is_dir('./tmp/'))mkdir ('./tmp/', 0700);        session_save_path('./tmp/');        Session::delete('second');        $second  = Session::get('second'.$_user['user_name']);        if ( (int)$second  > 4 ) {            $second_time = Session::get('second_time');            if ($second_time  +3600 <= time() ) {                self::clearSignInfo();            }            exit( iJson('', 400, false, '您已超出规定登录次数， 请于1小时后在尝试登录！'));        }        if ( !empty($_user)  || is_array( $_user )) {            //验证            if (preg_match("/^1[34578]\d{9}$/", $_user['user_name'])) {                $userInfo = Db::name('user')->where('tel',  trim($_user['user_name']))->find();            } else {                $userInfo = Db::name('user')->where('user_name',  trim($_user['user_name']))->find();            }            if ($userInfo['state'] === 2 ) {                exit( iJson('', 400, false, '该账号未审核，请联系管理员 ！'));            }            if (!empty($userInfo)) {                if ( md5( $_user['password'] )  === $userInfo['password']) {                    self::setUserInfo($userInfo);                    //记住密码                    Cookie::set('user_name', $_user['user_name'], time() + 3600 * 7 );                    Cookie::set('password', md5( $_user['password'] ),  time() + 3600 * 7 );                    return true;                } else {                    Session::set('second'.$_user['user_name'], $second +1);                    Session::set('second_time',  time());                    $n = 5;                    $over_n = $n - $second;                    exit( iJson('', 400, false, '密码输入错误, 您还有'.$over_n.'次机会！'));                }            } else {                exit( iJson('', 400, false, '用户不存在！'));            }        } else {            exit( iJson('', 400, false, '数据错误！'));        }    }}