<?php
/**
 * Created by PhpStorm.
 * Admin: LY
 * Date: 2017/10/9
 * Time: 10:15
 */

namespace app\admin\controller;

//use think\Db;
use app\admin\model\Admin;
use app\admin\common\Base as BaseController;
use think\console\command\make\Model;
use think\Request;
use think\Session;


class Index extends BaseController {
    public function index() {
        $this->assign('username', Session::get('user_name')); //显示登录用户名
//        Db::connect([]);
//        dump(Admin::get(1));
//        $res = Admin::get(1);
//        $res = $res->toArray();
//        dump($res);
//        var_dump($_SESSION);
//        dump(session('session_start_time'));
//        dump(time());
        $userName = Session::get('user_name');
        $title = '修改密码';
        return $this->fetch('changePwd');
    }

    public function changePwd() {
        $oldPwd = input('old_password', '', 'md5');
        $newPwd = input('new_password', '', 'md5');
        $userId = Session::get('user_id');
        $admin  = model('Admin')->where(['id' => $userId])->find();
        if( $oldPwd == $admin['password'] ){
            $userData['password']  = $newPwd;
            $userData['logintime'] = date("Y-m-d H:i:s" );
            $userData['loginip']   = Request::instance()->ip();
            if( model('Admin')->save($userData, array('id' => $userId)) ){
                $res['state'] = 1;
                $res['message'] = '密码修改成功';
            } else {
                $res['state'] = 0;
                $res['message'] = '密码修改失败';
            }
        } else {
            $res['state'] = 0;
            $res['message'] = '原始密码错误，密码修改失败';
        }
        return json_encode($res);
    }

}