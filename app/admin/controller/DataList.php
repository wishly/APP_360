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

        if(Request::instance()->isPost()){      //如果是表格提交
            $add['high_id']         = input('high_id','0', 'int');
            $add['middle_id']       = input('middle_id','0', 'int');
            $add['elementary_id']   = input('elementary_id','0', 'int');
            $add['href']            = input('href', '');
            $add['title']           = input('title', '');
            $add['is_recommend']    = input('is_recommend', '', 'int');
            $add['is_hot']          = input('is_hot', '0', 'int');
            $add['sort']            = input('sort', '1', 'int');

            if($datalist->create($add)){
                $res['state'] = 1;
                return json_encode($res);
            } else {
                $res['state'] = 0;
                return json_encode($res);
            }
        } else {
            //$map["id"] = input("id", '', "int");
            //var_dump($map["id"]);
            //$datalist = $datalist->where($map)->find();
            $high_level = model("high_level")->where('')->select();
            //var_dump($datalist);
            $this->assign("high_level", $high_level);  //高级分类数据
            //$this->assign("data", $datalist);          //分类数据
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
        $this->assign('username', Session::get('user_name')); //显示登录用户名
        $title = '编辑数据';
        $this->assign('title', $title);
        $datalist = model("Datalist");
        if(Request::instance() -> isPost()) {

            $id = input('id', '', 'int');
            $edit['title']           = input('title', '');
            $edit['high_id']         = input('high_id','0', 'int');
            $edit['middle_id']       = input('middle_id','0', 'int');
            $edit['elementary_id']   = input('elementary_id','0', 'int');
            $edit['href']            = input('href', '');
            $edit['is_hot']          = input('is_hot', '0', 'int');
            $edit['sort']            = input('sort', '1', 'int');
            $edit['is_recommend']    = input('is_recommend', '', 'int');
            $edit['picture']         = input('picture', '');

            $data = $datalist->save($edit, array('id' => $id));
            //$res['id'] = $id;
            if($data !== false){
                $res['state']   = 1;
                $res['message'] = '编辑成功';
            } else {
                $res['state']   = 0;
                $res['message'] = '编辑失败';
            }
            return json_encode($res);
        } else {
            $id               = input('id', '', 'int');
            $data             = $datalist->where(['id' => $id])->find();
            $high_level       = model("HighLevel")->where('')->select();
            $middle_level     = model("MiddleLevel")->where(['high_id' => $data['high_id']])->select();
            $elementary_level = model("ElementaryLevel")->where(['middle_id' => $data['middle_id']])->select();
            $this->assign('id', $id);
            $this->assign("data", $data);
            $this->assign("high_level", $high_level);  //高级分类数据
            $this->assign("middle_level", $middle_level);
            $this->assign("elementary_level", $elementary_level);
            return $this->fetch("DataList/edit");
        }
    }

    public function delete() {
//        $id = input("post.ids/a", '');    //thinkphp5 input 获取数组的时候，必须加post. 以及在后面加/a
//        $result = new \app\admin\validate\DataList();
//        $na = ['id' => $id[0]];
//        if(!$result->check($na)) {
//            $res['state'] = 0;
//            $res['s'] = $result->getError();
//            return json_encode($res);
//        }
        $id = $_POST['ids'];    //获取checked 数组值
        if( empty($id) ) {
            $id = input('id',0,'int');
        }
        $map['id'] = array('in', $id);
        $highLevel = model("Datalist")->where($map)->field('title, id')->select();
        $highTitle = array();
        $res['state'] = 0;
        if( $highLevel ){
            $highId = array();
            $highLevel = $highLevel->toArray();
            foreach($highLevel as $key => $value) {
                array_push($highTitle, $value['title']);
                array_push($highId, $value['id']);
            }
            $res['state']  = 1;
            $res['title']  = $highTitle;
            $res['highId'] = $highId;
            model("Datalist")->where($map)->field('title, id')->delete();
        }
        return json_encode($res);
    }
}