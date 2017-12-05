<?php
/**
 * Created by PhpStorm.
 * User: LY
 * Date: 2017/11/16
 * Time: 17:00
 */

namespace app\admin\controller;

use think\Session;
use app\admin\common\Base as BaseController;
//use think\paginator;

class DataList extends BaseController {


    public function lists() {
        $this->assign('username', Session::get('user_name')); //显示登录用户名
        $title = '数据管理';    //管理标题显示
        $this->assign('title', $title);
        $keyword = input('keyword','', 'trim');
        if($keyword) {
            $map['title'] = array('like',"%$keyword%"); //数组模糊查询
            $this->assign('keyword', $keyword);
        }
        $row = 5;
        $count = model('Datalist')->where($map)->count();
        $datalist = model('Datalist')->where($map)->paginate($row, $count);
        $page = $datalist->render();
//        dump($datalist);
        $this->assign('page', $page);
        $this->assign('datalist', $datalist);

        return $this->fetch('DataList/lists');
    }
}