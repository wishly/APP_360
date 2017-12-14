<?php
/**
 * Created by PhpStorm.
 * User: LY
 * Date: 2017/11/16
 * Time: 17:00
 */

namespace app\admin\controller;

use think\Request;
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
        $row = 7;
        $count = model('Datalist')->where($map)->count();
        $datalist = model('Datalist')->where($map)->paginate($row, $count);
        $page = $datalist->render();
//        dump($datalist);
        $this->assign('page', $page);
        $this->assign('datalist', $datalist);

        return $this->fetch('DataList/lists');
    }

    public function add(){
        $this->assign('username', Session::get('user_name')); //显示登录用户名
        $title = '添加数据';    //添加数据
        $this->assign('title', $title);
        $datalist = model("Datalist");
        if(Request::instance()->isPost()){

        } else {
            $map["id"] = input("id", '', "int");
            $datalist = $datalist->where($map)->find();
            $high_level = model("high_level")->select();
            var_dump($datalist);
            $this->assign("high_level", $high_level);  //高级分类数据
            $this->assign("data", $datalist);          //分类数据
            return $this->fetch('DataList/add');
        }
    }
    public function getMiddleInfo(){
        $map["high_id"] = input('high_id',"", "int");
        $middle = model("MiddleLevel")->where($map)->field("id,middle_name")->select();
        $option = '';
        foreach( $middle as $vo ) {
            $option .= "<option value=".$vo['id'].">".$vo['middle_name']."</option>";
        }
        return $option;
    }
    public function getElementaryInfo(){
        $map["middle_id"] = input("middle_id", "", "int");
        $element = model("ElementaryLevel")->where($map)->field("id,elementary_name")->select();
        $option = '';
        foreach( $element as $vo ) {
            $option .= "<option value=".$vo['id'].">".$vo['elementary_name']."</option>";
        }
        return $option;
    }

    public function edit(){

    }

    public function delete() {

    }
}