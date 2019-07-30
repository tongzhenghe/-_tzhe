<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/29
 * Time: 8:17
 */

namespace app\admin\controller;

use app\admin\model\Admin;
use app\admin\model\system\SystemAdmin;
use think\Controller;
use think\Cookie;
use think\Db;
use think\Session;

class Sign extends Controller
{
    public function  signin()
    {
        $comname  = Cookie::get('comname');
        $password  = Cookie::get('password');
        $is_remember  = Cookie::get('is_remember');

        //酒泉华美医疗美容医院
        if ( true === request()->isPost()) {
            $user_info = request()->post();
            //$captcha = trim($user_info['captcha']);
            if (empty( $user_info['comname'] ))
                exit(iJson('', 400,  false, '用户名不能为空！'));
            if (empty( $user_info['password'] ))
                exit(iJson('', 400,  false, '密码不能为空！'));
            //if(!captcha_check($captcha))
                //exit(iJson('', 400,  false, '验证码输入错误'));

            $result = Admin::check_login( $user_info );

            if (true === $result ) {
                //记录log
                exit(iJson('/admin/index', 200,  true,  '欢迎'. $user_info['comname']. '回来!'));

            } else {

                exit(iJson('', 400,  false, '系统错误！'));

            }

        }

        if ($comname && $password ) {
            $_user['comname'] = $comname;
            $_user['password'] = $password;
            $_user['is_remember'] = $is_remember;
            $this->assign('_user', $_user);
        }

        return view();


    }


        /**
         * logout
         */
        public function sign_out()
        {
            SystemAdmin::clearLoginInfo();
            $this->redirect('sign/signin');
        }

    }