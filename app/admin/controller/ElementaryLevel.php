<?php
/**
 * Created by PhpStorm.
 * User: LY
 * Date: 2018/1/25
 * Time: 9:47
 */
namespace app\admin\controller;

use think\Request;
use think\Session;
use app\admin\common\Base as BaseController;

class ElementaryLevel extends BaseController {
    public function lists() {
        $this->assign('username', Session::get('user_name')); //显示登录用户名
        $title = '初级分类管理';    //管理标题显示
        $this->assign('title', $title);
        $keyword = input('keyword','', 'trim');
        if($keyword) {
            $map['elementary_name'] = array('like',"%$keyword%"); //数组模糊查询
            $this->assign('keyword', $keyword);
        }
        $row = 8;
        $count = model('ElementaryLevel')->where($map)->count();
        $datalist = model('ElementaryLevel')->where($map)->paginate($row, $count);
        $page = $datalist->render();        //分页渲染
//        dump($datalist);
        $this->assign('page', $page);
        $this->assign('datalist', $datalist);

        return $this->fetch('ElementaryLevel/lists');
    }

    public function add(){
        $this->assign('username', Session::get('user_name')); //显示登录用户名
        $title = '初级分类管理';    //管理标题显示
        $this->assign('title', $title);

        if(Request::instance()->isPost()){
            $inputData['elementary_name']  = input('elementary_name', '');
            $inputData['sort']             = input('sort', '', 'int');
            $inputData['middle_id']        = input('middle_id', '', 'int');

            $res = model("ElementaryLevel")->create($inputData);
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
            return $this->fetch('ElementaryLevel/add');
        }
    }

    public function delete(){
        $id = $_POST['ids'];    //获取checked 数组值
        if( empty($id) ) {
            $id = input('id',0,'int');
        }
        $map['id'] = array('in', $id);
        $elementaryLevel = model("ElementaryLevel")->where($map)->field('elementary_name, id')->select();
        $res['state'] = 0;
        if( $elementaryLevel ){
            $elementaryTitle = array();
            $elementaryId    = array();
            $elementaryLevel = $elementaryLevel->toArray();
            foreach($elementaryLevel as $key => $value) {
                array_push($elementaryTitle, $value['elementary_name']);
                array_push($elementaryId, $value['id']);
            }
            $res['state']  = 1;
            $res['title']  = $elementaryTitle;
            $res['elementaryId'] = $elementaryId;
            model("ElementaryLevel")->where($map)->delete();
        }
        return json_encode($res);
    }

    public function edit(){
        $this->assign('username', Session::get('user_name')); //显示登录用户名
        $title = '编辑数据';
        $this->assign('title', $title);

        if(Request::instance()->isPost()) {

            $id = input('id', '', 'int');
            $inputData['elementary_name']  = input('elementary_name', '');
            $inputData['sort']             = input('sort', '', 'int');
            $inputData['middle_id']        = input('middle_id', '', 'int');

            $data = model("ElementaryLevel")->save($inputData, array('id' => $id));
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

            $data             = model("ElementaryLevel")->where(['id' => $id])->find();
            $middleId         = model("ElementaryLevel")->where(['id' => $id])->field('middle_id')->find(); //获取中级分类ID
            $highId           = model("MiddleLevel")->where(['id' => $middleId['middle_id']])->field('high_id')->find()['high_id']; //获取高级分类ID
            $highData         = model("HighLevel")->where('')->select();   //获取高级分类数据
            $middleData       = model("MiddleLevel")->where('')->select();  //获取中级分类数据
            $this->assign('id', $id);
            $this->assign('data', $data);
            $this->assign('highData', $highData);
            $this->assign('highId', $highId);
            $this->assign('middleData', $middleData);

            return $this->fetch("ElementaryLevel/edit");
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
}