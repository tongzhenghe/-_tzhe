<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/6 0006
 * Time: 上午 10:34
 */

namespace app\admin\model;

use app\admin\model\system\SystemAdmin;
use basic\ModelBasic;
use think\Cookie;
use think\Db;
use think\Session;
class Admin extends  ModelBasic
{

    static  public  function  valid_admin( $data )
    {
        $comname = trim( $data['comname']);
        $password = trim($data['password']);
        $repassword = trim($data['repassword']);

        if (strlen($comname) >= 5 ) {

                if ($password  === $repassword) {
                    return true;
                } else {
                    exit(iJson('', 400, false, '两次密码输入不一致'));
                }
        } else {
            exit(iJson('', 400, false, '用户名长度不够！'));
        }
    }


  /*  static  public  function  update_admin( $userInfo )
    {
        $oldpassword = md5( $userInfo['oldpassword']);
        $userInfo  = Db::name('admin')->where( 'comname' , trim( $userInfo['comname']))->find();

        if ( !empty($userInfo) && $userInfo['password'] === $oldpassword ) {

            return true;

        } else {

            exit( iJson('', 400, false, '旧密码验证失败！'));
        }

    }*/

    static  public  function  check_login( $_user )
    {
        if(!is_dir('./tmp/'))mkdir ('./tmp/', 0700); session_save_path('./tmp/');
        $second  = Session::get('second');

        if ( (int)$second  > 5 ) {
            $second_time = Session::get('second_time');
            if ($second_time  +3600 <= time() ) {
                SystemAdmin::clearLoginInfo();
            }
            jsondebug(222);
            exit( iJson('', 400, false, '您已超出登录次数， 请于1小时后在尝试登录！'));
        }

        if ( !empty($_user)  || is_array( $_user )) {
            //验证
            $userInfo = Db::name('company')->where('comname',  trim($_user['comname']))->find();

            if ($userInfo['state'] === 2 ) {
                exit( iJson('', 400, false, '该账号还未未审核， 暂时无法登陆！'));
            }

            if (!empty($userInfo)) {
                if ( md5( $_user['password'] )  === $userInfo['password']) {
                    SystemAdmin::setUserInfo($userInfo);

                    //记住密码
                    if ( intval( $_user['is_remember'] ) == 1 ) {
                        Cookie::set('comname', $_user['comname'], time() + 3600 * 7 );
                        Cookie::set('password', md5( $_user['password'] ),  time() + 3600 * 7 );
                        Cookie::set('is_remember', $_user['is_remember'] ,  time() + 3600 * 7 );
                    } else {
                        Cookie::delete('comname');
                        Cookie::delete('password');
                        Cookie::delete('is_remember' );
                    }
                    return true;
                } else {
                    Session::set('second', $second +1);
                    Session::set('second_time',  time());
                    exit( iJson('', 400, false, '密码输入错误！'));
                }
            } else {
                exit( iJson('', 400, false, '用户不存在！'));
            }
        } else {
            exit( iJson('', 400, false, '数据错误！'));
        }
    }


    /**
     * 获得登陆用户信息
     * @return mixed
     */
    public static function getAdminId()
    {
        $adminId = Session::get('adminId');
        return $adminId;

    }



    //状态
    public static function changeState($tablename, $post )
    {
        $field =  trim($post['field']);
        $id  =  intval($post['id']);
        $value =intval( $post['value']);

        if (empty($field)  || empty($id) || empty($value )) return false;

        $data[$field] = $value;
        $result =  Db::name($tablename)->where('id', $id)->update($data);
        return $result;

    }

    //删除
    public static function deleteFindOne($tablename, $id )
    {
        if (empty($id) || empty($tablename)) return false;
        $data['is_del'] = 2;
        $result =  Db::name( $tablename )->where('id', $id)->update($data);
        return $result;
    }

}