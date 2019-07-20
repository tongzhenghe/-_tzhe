<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/4
 * Time: 8:52
 */

namespace app\index\model;


use think\Db;
use think\Model;

class Index extends Model
{



    //取审批人和抄送人
    public  static  function  getProAllUser()
    {

        $user = User::activeUserInfoOrFail();

        //部门员工
        $department_user = Db::name('department')->where(['compid'=> $user['compid'], 'is_del' => 1, 'state' => 1 ])->select();

        foreach ($department_user as &$val ) {
            $val['children'] = Db::name('user')->where(['department_id' => $val['id'], 'compid' => $user['compid'] , 'is_del' => 1, 'state' => 1 ])->select();
        }

        /*抄送*/
        $user = Db::name('user')->where(['compid' => $user['compid'], 'is_perfect' => 1])->field('id, user_name, default_appro')->select();

        $settlesRes = [];

        foreach ($user as $sett) {

            $user_nameFirstChar = _getFirstCharter($sett['user_name']); //取出的第一个汉字的首字母

            $settlesRes[$user_nameFirstChar][] = $sett; //以这个首字母作为key

        }

        ksort($settlesRes);

        foreach(range('A','Z') as &$v) {
            $letter[] = $v;
        }

        return array_merge([
            'settlesRes' => $settlesRes
            , 'letter' => $letter
            ,'department_user' => $department_user
        ]);

    }


    public  static  function  randNum()
    {
        return date('YmdHis', time()).rand(1111, 9999999);
    }



}