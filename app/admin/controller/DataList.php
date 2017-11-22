<?php
/**
 * Created by PhpStorm.
 * User: LY
 * Date: 2017/11/16
 * Time: 17:00
 */

namespace app\admin\controller;

use app\admin\common\Base;
use think\Session;
use app\admin\common\Base as BaseController;

class DataList extends BaseController {


    public function lists() {
        $this->assign('username', Session::get('user_name')); //显示登录用户名
        return $this->fetch('DataList/lists');
    }

    public function search() {

    }
}