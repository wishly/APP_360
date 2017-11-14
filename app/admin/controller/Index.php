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


class Index extends BaseController {
    public function index() {
//        Db::connect([]);
//        dump(Admin::get(1));
//        $res = Admin::get(1);
//        $res = $res->toArray();
//        dump($res);
//        var_dump($_SESSION);
//        dump(session('session_start_time'));
//        dump(time());
        return $this->fetch();
    }

    public function hello($id = 2) {
        return 'hello11111111'.$id;
    }
}