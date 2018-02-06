<?php
/**
 * Created by PhpStorm.
 * User: LY
 * Date: 2018/1/23
 * Time: 17:02
 */

namespace app\admin\controller;

use think\Request;
use think\Session;
use app\admin\common\Base as BaseController;

class HighLevel extends BaseController {
    public function lists() {
        $this->assign('username', Session::get('user_name')); //显示登录用户名
        $title = '高级分类管理';    //管理标题显示
        $this->assign('title', $title);
        $keyword = input('keyword','', 'trim');
        if($keyword) {
            $map['high_name'] = array('like',"%$keyword%"); //数组模糊查询
            $this->assign('keyword', $keyword);
        }
        $row = 5;
        $count = model('HighLevel')->where($map)->count();
        $datalist = model('HighLevel')->where($map)->paginate($row, $count);
        $page = $datalist->render();        //分页渲染
//        dump($datalist);
        $this->assign('page', $page);
        $this->assign('datalist', $datalist);

        return $this->fetch('highLevel/lists');
    }

    public function add(){
        $this->assign('username', Session::get('user_name')); //显示登录用户名
        $title = '高级分类管理';    //管理标题显示
        $this->assign('title', $title);

        if(Request::instance()->isPost()){
            $inputData['high_name']  = input('high_name', '');
            $inputData['is_display'] = input('is_display', '', 'int');
            $inputData['layout']     = input('layout', '', 'string');
            $inputData['sort']       = input('sort', '', 'int');

            $res = model("HighLevel")->create($inputData);
            if($res !== false){
                $res['state']   = 1;
                $res['message'] = '添加成功';
            }else{
                $res['state']   = 0;
                $res['message'] = '添加失败';
            }
            return json_encode($res);
        }else{
            return $this->fetch('highLevel/add');
        }
    }

    public function delete(){
        $id = $_POST['ids'];    //获取checked 数组值
        if( empty($id) ) {
            $id = input('id',0,'int');
        }
        $map['id'] = array('in', $id);
        $highLevel = model("HighLevel")->where($map)->field('high_name, id')->select();
        $highTitle = array();
        $res['state'] = 0;
        if( $highLevel ){
            $highId = array();
            $highLevel = $highLevel->toArray();
            foreach($highLevel as $key => $value) {
                array_push($highTitle, $value['high_name']);
                array_push($highId, $value['id']);
            }
            $res['state']  = 1;
            $res['title']  = $highTitle;
            $res['highId'] = $highId;
            model("HighLevel")->where($map)->delete();
        }
        return json_encode($res);
    }

    public function edit(){
        $this->assign('username', Session::get('user_name')); //显示登录用户名
        $title = '编辑数据';
        $this->assign('title', $title);

        if(Request::instance()->isPost()) {

            $id = input('id', '', 'int');
            $inputData['high_name']  = input('high_name', '');
            $inputData['is_display'] = input('is_display', '', 'int');
            $inputData['layout']     = input('layout', '', 'string');
            $inputData['sort']       = input('sort', '', 'int');

            $data = model("HighLevel")->save($inputData, array('id' => $id));
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

            $data             = model("HighLevel")->where(['id' => $id])->find();
            $this->assign('id', $id);
            $this->assign("data", $data);

            return $this->fetch("highLevel/edit");
        }
    }
}