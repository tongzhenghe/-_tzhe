<?php
namespace app\admin\controller;

use app\admin\model\Admin;
use app\admin\model\system\SystemAdmin;
use app\extra\Upload;
use think\Db;
use Workerman\Lib\Timer;

class Index extends Common
{
    public function index()
    {
         return view();

    }

    public function table2()
    {

        return view();

    }

    public function app()
    {
        return view();

    }

    //插件管理
    public  function  general()
    {

        $table = 'module';
        $compId =  Admin::getAdminId();
        $do = request()->param('do');
        if (!empty($do) && $do == 'add') {

            if (request()->isAjax()) {
                $post = request()->post();
//
                if (empty($post['name'])) exit(iJson('', 400, 400, '请填写名称'));
                if (empty($post['url'])) exit(iJson('', 400, 400, '请填写请求地址'));

                //处理自定义审批人
                $custom_type = intval($post['custom_type']);
                $app_people = null;
                if ($custom_type == 1 ) {
                    $app_people = array_filter($post['app_people']);
                }


                $data = [
                    'name' => trim($post['name']),
                    'pid' => intval($post['pid']),
                    'sort' => intval($post['sort']),
                    'icon' => !empty($post['icon']) ? trim($post['icon']) : null,
                    'url' => trim($post['url']),
                    'bgcolor' => trim($post['bgcolor']),
                    'icon_code' => trim($post['icon_code']),
                    'custom_type' => $custom_type,
                    'settinged_appro_member' => json_encode($app_people),
                    'time' => time(),
                ];

                if (empty($post['id'])) {
                    $result = Db::name($table)->insert($data);
                } else {
                    $id = intval($post['id']);
                    $result = Db::name($table)->where('id', $id)->update($data);
                }

                if ($result) {
                    exit(iJson('' ));
                }
                exit(false);

            }

            $id = request()->param('id');
            $row = [];
            if (!empty($id)) {
                $row = Db::name($table)->where('id', intval($id))->find();
                if (!empty($row['settinged_appro_member'])) {
                    $row['settinged_appro_member'] = (array)json_decode($row['settinged_appro_member']);
                    $row['settinged_appro_member'] = implode(",", $row['settinged_appro_member']);
                    $row['settinged_appro_member'] = explode(",", $row['settinged_appro_member']);

                    wl_debug(count( $row['settinged_appro_member']));
                    for($i = 1; $i<count( $row['settinged_appro_member']); $i++) {
                        $row['settinged_appro_member'][] = Db::name('user')->where('id', $row['settinged_appro_member'][$i])->find();
                    }

                    wl_debug(  $row['settinged_appro_member']);
                }
            }

            $general = Db::name($table)->where(['state' => 1])->field('id, pid, name')->select();
            $general = tree($general);

            //所有有效成员

            $department_mem = Db::name('department')
                ->where(['state' => 1, 'compid' => $compId, 'is_del' => 1])
                ->field('id, name')
                ->select();

            if (!empty($department_mem)) {
                foreach ($department_mem as &$vo ) {
                    $vo['mem'] = Db::name('user')
                        ->where(['compid' => $compId, 'state' => 1, 'is_perfect' => 1, 'is_del' => 1, 'department_id' => $vo['id']])
                        ->field('user_name, id')
                        ->select();
                }
            }

            return view('generaladd', [
                'do'  => $do,
                'general' => $general ? $general : [],
                'department_mem' => $department_mem ? $department_mem : [],
                'row' => $row
            ]);

        }

        //state
        if ( !empty($do) && $do == '_state') {
            $post = request()->post();
            $result =  Admin::changeState($table, $post);
            if(!$result ) exit(false);
            exit(iJson('' ));
        }

           //delete
        if ( !empty($do) && $do == '_delete') {
            $id = request()->param('id');
            $result =  Admin::deleteFindOne($table, $id);
            if(!$result ) exit(false);
            exit(iJson('' ));
        }


        $data = Db::name($table)->where(['state' => 1])->order('sort asc')->select();
        $data = tree($data);

        return view('' , ['data' => $data]);

    }




    //审批列表
    public  function  approval()
    {
        //获取所有该公司的审批文件；
        $admin =  SystemAdmin::activeAdminInfoOrFail();
        $approval = Db::name('general_approval a')
            ->join('user b', 'a.send_user_id = b.id', 'LEFT')
            ->join('department c', 'b.department_id = c.id', 'LEFT')
            ->field('a.*, b.user_name approval_username, b.photo approval_user_photo, c.name approval_user_department')
            ->order('a.create_time DESC')
            ->where(['a.compid ' => $admin['id']])
            ->select();

        foreach ($approval as &$vaL) {
            if (!empty($vaL['images'])) {
                $vaL['images'] = unserialize($vaL['images']);
            }

            $vaL['create_time'] = timeTran($vaL['create_time']);

        }

        return view('', ['approval' => $approval]);


    }

    //财务报销--》采购报销
    public  function  procurement()
    {
        $table = 'procurement_type';
        $table_pay = 'paytype';
        $params = request()->param();

        if (!empty($params['do'])) {

            if ( !empty($params['do']) && $params['do'] == '_state') {
                $result =  Admin::changeState($table, $params);
                if(!$result ) exit(false);
                exit(iJson('' ));
            }

            if (trim($params['do'])  ==  'add') {

                if (request()->isAjax()) {

                    $data  = [
                        'state' =>1,
                        'compid' => Admin::getAdminId(),
                        'name' => trim($params['name']),
                        'sort' => intval($params['sort']),
                        'intro' => trim($params['intro']),
                        'time' => time(),
                    ];

                    if (empty($params['id'])) {
                        $result = Db::name($table)->insert($data);
                    } else {
                        $id = intval($params['id']);
                        $result = Db::name($table)->where('id', $id)->update($data);
                    }

                    if ($result) {
                        exit(iJson('' ));
                    }
                    exit(false);

                }

                $row = [];
                if (!empty($params['id'])) {
                    $row = Db::name($table)->where('id', intval($params['id']))->find();
                }

                return view('addprocurement_type', [
                    'do' => '_'.trim($params['do'])
                    ,'row' => $row
                ]);

            }


            /*支付方式*/
            if ( !empty($params['do']) && $params['do'] == '_state_pay') {
                $result =  Admin::changeState($table_pay, $params);
                if(!$result ) exit(false);
                exit(iJson('' ));
            }

            if (trim($params['do'])  ==  'addpaytype') {

                if (request()->isAjax()) {

                    $data  = [
                        'state' =>1,
                        'compid' => Admin::getAdminId(),
                        'name' => trim($params['name']),
                        'sort' => intval($params['sort']),
                        'intro' => trim($params['intro']),
                        'time' => time(),
                    ];

                    if (empty($params['id'])) {
                        $result = Db::name($table_pay)->insert($data);
                    } else {
                        $id = intval($params['id']);
                        $result = Db::name($table_pay)->where('id', $id)->update($data);
                    }

                    if ($result) {
                        exit(iJson('' ));
                    }
                    exit(false);

                }

                $row_paytype = [];

                if (!empty($params['id'])) {
                    $row_paytype = Db::name($table_pay)->where('id', intval($params['id']))->find();
                }

                return view('addpaytype', [ 'do' => '_'.trim($params['do']), 'row_paytype' => $row_paytype]);
            }


        }

        //采购类型
        $data = Db::name($table)->select();
        foreach ($data as &$v ) {
            $v['time'] = date('Y/m/d H:i:s', $v['time']);
        }

        //支付方式
        $data_pay = Db::name($table_pay)->select();
        foreach ($data_pay as &$vs ) {
            $vs['time'] = date('Y/m/d H:i:s', $vs['time']);
        }

        return view('', ['data' => $data, 'data_pay' => $data_pay]);

    }


    //报销
    public  function  reimbursement()
    {

        $table = 'reimbursement';
        $table_type = 'reimbursement_type';
        $compId =  Admin::getAdminId();
        $params = request()->param();
        if (!empty($params['do']) && trim($params['do'])  ==  'addtype') {

            if (request()->isAjax()) {

                $data  = [
                    'state' => 1,
                    'compid' => $compId,
                    'name' => trim($params['name']),
                    'sort' => intval($params['sort']),
                    'intro' => trim($params['intro']),
                    'time' => time(),
                ];

                if (empty($params['id'])) {
                    $result = Db::name($table_type)->insert($data);
                } else {
                    $id = intval($params['id']);
                    $result = Db::name($table_type)->where('id', $id)->update($data);
                }

                if ($result) {
                    exit(iJson('' ));
                }
                exit(false);

            }

            $row = [];
            if (!empty($params['id'])) {
                $row = Db::name($table_type)->where('id', intval($params['id']))->find();
            }

            return view('addreimbursement_type', [
                'do' => '_'.trim($params['do'])
                ,'row' => $row
            ]);

        }


        $data_type = Db::name($table_type)->where(['state' => 1, 'compid' => $compId ])->select();
        foreach ($data_type as &$vs ) {
            $vs['time'] = date('Y/m/d H:i:s', $vs['time']);
        }

        return view('', ['data_type' => $data_type]);


    }


    //财务审批 =》 工资审批
    public  function salary()
    {

        $table = 'salary_type';
        $table_type = 'salary';
        $params = request()->param();

        if (!empty($params['do'])) {

            if ( !empty($params['do']) && $params['do'] == '_state') {
                $result =  Admin::changeState($table, $params);
                if(!$result ) exit(false);
                exit(iJson('' ));
            }

            if (!empty($params['do']) && trim($params['do'])  ==  'addtype') {

                if (request()->isAjax()) {

                    $data  = [
                        'state' => 1,
                        'compid' => Admin::getAdminId(),
                        'name' => trim($params['name']),
                        'sort' => intval($params['sort']),
                        'intro' => trim($params['intro']),
                        'time' => time(),
                    ];

                    if (empty($params['id'])) {
                        $result = Db::name($table)->insert($data);
                    } else {
                        $id = intval($params['id']);
                        $result = Db::name($table)->where('id', $id)->update($data);
                    }

                    if ($result) {
                        exit(iJson('' ));
                    }
                    exit(false);

                }

                $row = [];
                if (!empty($params['id'])) {
                    $row = Db::name($table)->where('id', intval($params['id']))->find();
                }

                return view('addsalary_type', [
                    'do' => '_'.trim($params['do'])
                    ,'row' => $row
                ]);

            }

        }

        //类型
        $data = Db::name($table)->select();
        foreach ($data as &$v ) {
            $v['time'] = date('Y/m/d H:i:s', $v['time']);
        }

        //支付方式
        $data_pay = Db::name($table_type)->select();
        foreach ($data_pay as &$vs ) {
            $vs['time'] = date('Y/m/d H:i:s', $vs['time']);
        }

        return view('', ['data' => $data, 'data_pay' => $data_pay]);

    }



    //支付方式
    public  function  paytype()
    {

        return view();




    }


    public  function  admin()
    {
        $table = 'company';

        $do = request()->param('do');

        //add
        if (!empty($do) && $do == 'add') {

           /* $row = [];
            $id = request()->param('id');
            if (!empty($id)) {
                $row = Db::name($table)->where('id', $id)->find();
            }*/

            if (request()->isAjax()) {

                $post = request()->post();

                if (empty($post['comname'])) {
                    exit(iJson('', 400, 400, '请填写用户名'));
                }

                if (empty($post['password'])) {
                    exit(iJson('', 400, 400, '请填写登陆密码'));
                }

                if (empty($post['intro'])) {
                    exit(iJson('', 400, 400, '请填写公司简介'));
                }

                $result = Admin::valid_admin($post);

                if ( true === $result ) {

                    $data = [
                        'comname' => trim($post['comname']),
                        'password' => md5($post['password']),
                        'is_manage' => intval($post['is_manage']),
                        'icon' => !empty($post['icon']) ? $post['icon']: '',
                        'intro' =>  serialize($post['intro'])
                    ];

                    if (empty($userInfo)) {
                        $data['create_time'] = time();
                        $result = Db::name($table)->insert($data);
                    } else {
                        $id = intval($post['id']);
                        $data['create_time'] = time();
                        $result = Db::name($table)->where('id', $id)->update($data);
                    }

                    if ($result) {
                        exit(iJson('' ));
                    }
                    exit(false);
                }
            }


            return view('adminadd', [
                'do'  => $do,

            ]);
        }

        //state
        if ( !empty($do) && $do == '_state') {
            $post = request()->post();
            $result =  Admin::changeState($table, $post);
            if(!$result ) exit(false);
            exit(iJson('' ));
        }

        //设置
        if (!empty($do) && $do == 'account_setting') {

            $params = request()->param();

            if (request()->isAjax()) {
                if (empty($params['id'])) return false;

                $data = [
                    'intro' =>  serialize($params['intro'])
                   ,'update_time' => time()
                ];

                $result = Db::name('company')->where(['id' => $params['id']])->update( $data );

                if ($result)
                    exit(iJson('' ));

            }

            if (empty($params['id'])) return false;

            $user_find = Db::name('company')->where(['id' => intval($params['id'])])->find();

            if (!empty($user_find['intro']))
                $user_find['intro'] = unserialize($user_find['intro']);


/*Array
(
    [comname] => admin
    [password] => 21232f297a57a5a743894a0e4a801fc3
    [state] => 1
    [is_del] => 1
    [create_time] => 1554013723
    [update_time] =>
    [icon] => http://pp4834un1.bkt.clouddn.com/2019/03/b6227201903319336.png
    [id] => 1
    [is_manage] => 1
    [intro] =>
)*/

//用户名 不能改
            //原密码
            //新密码
            //确认新密码
            //选择图标
            //简介


            return view('account_setting', [  'do'  => $do, 'user_find' => $user_find]);
        }

        $data = Db::name($table)->where('is_del', 1)->field('id, comname, create_time, icon, is_manage,state')->select();

        return view('', ['data' => ($data)]);

    }

    public function  uploads()
    {
        if ($_FILES) {
            Upload::image($_FILES['file']);
        }
    }


    //用户管理
    public  function  user()
    {
        $table = 'department';
        $user_table = 'user';
        $params =   request()->param();

        if (!empty($params['module']) && $params['module'] == 'department') {

            if ($params['do'] == 'add') {

                if (request()->isAjax()) {
                    $post = request()->post();

                    if (empty($post)) {
                        exit(iJson('', 400, 400, '请填写部门名称'));
                    }

                    $data  = [
                        'state' =>1,
                        'is_del' =>1,
                        'compid' => Admin::getAdminId(),
                        'name' => trim($post['name']),
                        'remarks' => trim($post['remarks']),
                    ];

                    if (empty($post['id'])) {
                        $result = Db::name($table)->insert($data);
                    } else {
                        $id = intval($post['id']);
                        $result = Db::name($table)->where('id', $id)->update($data);
                    }

                    if ($result) {
                        exit(iJson('' ));
                    }
                    exit(false);
                }

                $row = [];
                if (!empty($params['id'])) {
                    $row = Db::name($table)->where('id', intval($params['id']))->find();
                }

                return view('department_add', [
                    'do' => '_'.trim($params['do']),
                    'row' => $row
                ]);

            }

            //state
            if ( !empty($params['do']) && $params['do'] == '_state') {

                $result =  Admin::changeState($table, $params);
                if(!$result ) exit(false);

                exit(iJson('' ));
            }

            //delete
            if ( !empty($params['do']) && $params['do'] == '_delete') {
                $id = intval($params['id']);
                $result =  Admin::deleteFindOne($table, $id);
                if(!$result ) exit(false);
                exit(iJson('' ));
            }

        }


        //state
        if ( !empty($params['do']) && $params['do'] == '_state') {

            $result =  Admin::changeState($user_table, $params);
            if(!$result ) exit(false);

            //发送短信提醒
            if (!empty($params['tel']) && $params['value'] == 1 ) {
                $templateid = '461248';
                \Ucpaas::SendSms($templateid, $params['tel']);
            }

            exit(iJson('' ));
        }

        //delete
        if ( !empty($params['do']) && $params['do'] == '_delete') {
            $id = intval($params['id']);
            $result =  Admin::deleteFindOne($user_table, $id);
            if(!$result ) exit(false);
            exit(iJson('' ));
        }

        //data
        $adminId = Admin::getAdminId();
        $user = Db::name($user_table)->where(['is_del'=> 1, 'compid' => $adminId])->select();
        $data = Db::name($table)->where(['is_del'=> 1, 'compid' => $adminId])->field('name, id, state')->select();
        return view('', [
            'data' => $data,
            'user' => $user
        ]);

    }


    //设置默认审批模块
    public  function default_pro()
    {
        $params = request()->param();
        $modules = Db::name('module')->where(['state' => 1])->where('pid<>0')->field('id, name')->select();

        if (request()->isAjax()) {

            $moduleID = json_encode( $params['moduleID']);
            $data['default_appro'] = $moduleID;
            $data['time'] = time();
            $set = Db::name('user')->where('id', intval($params['userid']))->update($data);

            if (!empty($set))  exit(iJson());

        }

        //编辑
        $set_module = Db::name('user')->where('id', $params['userId'])->field('default_appro')->find();
        $set_module['default_appro'] = json_decode($set_module['default_appro']);

        return view('', ['userName' => $params['userName'], 'userId' => $params['userId'], 'modules' => $modules, 'set_module' => $set_module['default_appro'] ]);

    }


    /**
     * @return \think\response\View
     */
    public  function user_log()
    {
        $company_info =  SystemAdmin::activeAdminInfoOrFail();
        $user_log = Db::name('log')->where(['compid' => $company_info['id']])->select();
        return view('', ['user_log' => $user_log]);

    }


    public  function test()
    {
        return view();
    }


}
