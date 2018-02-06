<?php
/**
 * Created by PhpStorm.
 * User: LY
 * Date: 2018/1/24
 * Time: 15:11
 */
namespace app\admin\controller;

use think\Request;
use think\Session;
use app\admin\common\Base as BaseController;

class MiddleLevel extends BaseController {
    public function lists() {
        $this->assign('username', Session::get('user_name')); //显示登录用户名
        $title = '中级分类管理';    //管理标题显示
        $this->assign('title', $title);
        $keyword = input('keyword','', 'trim');
        if($keyword) {
            $map['middle_name'] = array('like',"%$keyword%"); //数组模糊查询
            $this->assign('keyword', $keyword);
        }
        $row = 8;
        $count = model('MiddleLevel')->where($map)->count();
        $datalist = model('MiddleLevel')->where($map)->paginate($row, $count);
        $highList = model("HighLevel")->where('')->select();            //获取高级分类名称
        $page = $datalist->render();        //分页渲染
//        dump($datalist);
        $this->assign('page', $page);
        $this->assign('highList', $highList);
        $this->assign('datalist', $datalist);

        return $this->fetch('middleLevel/lists');
    }

    public function add(){
        $this->assign('username', Session::get('user_name')); //显示登录用户名
        $title = '中级分类管理';    //管理标题显示
        $this->assign('title', $title);

        if(Request::instance()->isPost()){
            $inputData['middle_name']  = input('middle_name', '');
            $inputData['is_recommend'] = input('is_recommend', '', 'int');
            $inputData['sort']       = input('sort', '', 'int');

            $res = model("MiddleLevel")->create($inputData);
            if($res !== false){
                $res['state']   = 1;
                $res['message'] = '添加成功';
            }else{
                $res['state']   = 0;
                $res['message'] = '添加失败';
            }
            return json_encode($res);
        }else{
            $high_level = model("HighLevel")->where('')->select();
            $this->assign('highLevel', $high_level);
            return $this->fetch('MiddleLevel/add');
        }
    }

    public function delete(){
        $id = $_POST['ids'];    //获取checked 数组值
        if( empty($id) ) {
            $id = input('id',0,'int');
        }
        $map['id'] = array('in', $id);
        $middleLevel = model("MiddleLevel")->where($map)->field('middle_name, id')->select();
        $res['state'] = 0;
        if( $middleLevel ){
            $middleTitle = array();
            $middleId    = array();
            $highLevel = $middleLevel->toArray();
            foreach($highLevel as $key => $value) {
                array_push($middleTitle, $value['middle_name']);
                array_push($middleId, $value['id']);
            }
            $res['state']  = 1;
            $res['title']  = $middleTitle;
            $res['middleId'] = $middleId;
            model("MiddleLevel")->where($map)->delete();
        }
        return json_encode($res);
    }

    public function edit(){
        $this->assign('username', Session::get('user_name')); //显示登录用户名
        $title = '编辑数据';
        $this->assign('title', $title);

        if(Request::instance()->isPost()) {

            $id = input('id', '', 'int');
            $inputData['middle_name']  = input('middle_name', '');
            $inputData['is_recommend'] = input('is_recommend', '', 'int');
            $inputData['sort']       = input('sort', '', 'int');

            $data = model("MiddleLevel")->save($inputData, array('id' => $id));
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

            $data             = model("MiddleLevel")->where(['id' => $id])->find();
            $highData         = model("HighLevel")->where('')->select();
            $this->assign('id', $id);
            $this->assign("data", $data);
            $this->assign('highData', $highData);

            return $this->fetch("MiddleLevel/edit");
        }
    }
}