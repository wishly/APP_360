<?php
/**
 * Created by PhpStorm.
 * User: LY
 * Date: 2018/1/25
 * Time: 16:08
 */

namespace app\admin\controller;

use think\Request;
use think\Session;
use app\admin\common\Base as BaseController;

class Hot extends BaseController {
    public function lists(Request $request) {
        $this->assign('username', Session::get('user_name')); //显示登录用户名
        $keyword = input('keyword','', 'trim');
        if($keyword) {
            $map['title'] = array('like',"%$keyword%"); //数组模糊查询
            $this->assign('keyword', $keyword);
        }
        $row = 5;
        $map['type'] = input('type', 0, 'int');
        $count = model('Hot')->where($map)->count();
        $datalist = model('Hot')->where($map)->paginate($row, $count);
        $page = $datalist->render();        //分页渲染
//        dump($datalist);
        $this->assign('type', $request->param('type', 0, 'int'));
        $this->assign('page', $page);
        $this->assign('datalist', $datalist);

        return $this->fetch('hot/lists');
    }

    public function add(Request $request){
        $this->assign('username', Session::get('user_name')); //显示登录用户名
        $title = '热门管理';    //管理标题显示
        $this->assign('title', $title);
        $this->assign('hottype', $request->param('hottype', 0, 'int'));
        if(Request::instance()->isPost()){
            $date = date('Y-m-d');
            $path = ROOT_PATH . 'public' . DS . 'Uploads' . DS . $date;
            $dir = iconv('UTF-8', 'GBK', $path);
            if(!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $file = $_FILES['picture'];
            if($file && $file['size'] < 5242880){ //上传小于5M 5M = 5120K = 5242880b
                if(move_uploaded_file($file['tmp_name'], $dir . DS . $file['name'])){
                    $res['state']   = 1;
                    $res['message'] = $file['name'].'上传成功';
                } else {
                    $res['state']   = 0;
                    $res['message'] = $file['name'].'文件移动失败';
                }
            } else {
                $res['state']   = 0;
                $res['message'] = '文件上传大小超过最大限制';
            }
            $inputData['title']        = input('title', '');
            $inputData['href']         = input('href', '');
            $inputData['type']         = input('type', '', 'int');
            $inputData['sort']         = input('sort', '', 'int');
            $inputData['is_recommend'] = input('is_recommend', '', 'int');

            if($inputData['type'] == 2){
                $inputData['picture']  = $date . DS . $file['name'];
            } else {
                $inputData['picture']  = '';
            }

            $res = model("Hot")->create($inputData);
            if($res !== false){
                $res['state']   = 1;
                $res['message'] = '添加成功';
            }else{
                $res['state']   = 0;
                $res['message'] = '添加失败';
            }
            return json_encode($res);
            //            $file = $request->file('picture');
//            $info  = $file->validate(['size'=>156780,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'Uploads', '' );
//            echo ROOT_PATH;
//            if($info){
//                // 成功上传后 获取上传信息
//                // 输出 jpg
//                echo $info->getExtension();
//                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
//                echo $info->getSaveName();
//                // 输出 42a79759f284b767dfcb2a0197904287.jpg
//                echo $info->getFilename();
//            }else{
//                // 上传失败获取错误信息
//                echo $file->getError();
//            }
//            $files = $_FILES['picture'];
//            echo $files['name'];
//            if($files){
//                if(move_uploaded_file($files['tmp_name'], ROOT_PATH . 'public' . DS . 'Uploads' . DS . $files['name'])){
//                    echo '文件移动成功';
//                }
//            }
        }else{
            $hot = model("Hot")->where('')->find();
            $this->assign('data', $hot);
            return $this->fetch('hot/add');
        }
    }

    public function delete(){
        $id = $_POST['ids'];    //获取checked 数组值
        if( empty($id) ) {
            $id = input('id',0,'int');
        }
        $map['id'] = array('in', $id);
        $hot = model("Hot")->where($map)->field('title, id')->select();
        $hotTitle = array();
        $res['state'] = 0;
        if( $hot ){
            $hotId = array();
            $highLevel = $hot->toArray();
            foreach($hot as $key => $value) {
                array_push($hotTitle, $value['title']);
                array_push($hotId, $value['id']);
            }
            $res['state']  = 1;
            $res['title']  = $hotTitle;
            $res['highId'] = $hotId;
            model("Hot")->where($map)->delete();
        }
        return json_encode($res);
    }

    public function edit(){
        $this->assign('username', Session::get('user_name')); //显示登录用户名
        $title = '编辑数据';
        $this->assign('title', $title);

        if(Request::instance()->isPost()) {
            $id = input('id', '', 'int');

            $inputData['title']        = input('title', '');
            $inputData['href']         = input('href', '');
            $inputData['type']         = input('type', '', 'int');
            $inputData['sort']         = input('sort', '', 'int');
            $inputData['is_recommend'] = input('is_recommend', '', 'int');

            if($inputData['type'] == 2){
                if($_FILES['picture']['tmp_name']){//是否有上传文件，如果有才更新picture，否则会无法加载图片
                    $date = date('Y-m-d');
                    $path = ROOT_PATH . 'public' . DS . 'Uploads' . DS . $date;
                    $dir = iconv('UTF-8', 'GBK', $path);
                    $file = $_FILES['picture'];
                    if($file && $file['size'] < 5242880){ //上传小于5M 5M = 5120K = 5242880b
                        if(move_uploaded_file($file['tmp_name'], $dir . DS . $file['name'])){
                            $res['state']   = 1;
                            $res['message'] = $file['name'].'上传成功';
                        } else {
                            $res['state']   = 0;
                            $res['message'] = $file['name'].'文件上传失败';
                        }
                    } else {
                        $res['state']   = 0;
                        $res['message'] = '文件上传大小超过最大限制';
                    }
                    $inputData['picture']  = $date . DS . $file['name'];
                }
            } else {
                $inputData['picture']  = '';
            }

            $data = model("Hot")->save($inputData, array('id' => $id));
            if($data !== false){
                $res['state']   = 1;
                $res['message'] = '编辑成功';
            } else {
                $res['state']   = 0;
                $res['message'] = '编辑失败';
            }
            return json_encode($res);
        } else {
            $id   = input('id', '', 'int');
            $data = model("Hot")->where(['id' => $id])->find();
            $this->assign('id', $id);
            $this->assign("data", $data);

            return $this->fetch("hot/edit");
        }
    }
}
