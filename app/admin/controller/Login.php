<?php
/**
 * Created by PhpStorm.
 * User: LY
 * Date: 2017/10/25
 * Time: 15:42
 */

namespace app\admin\controller;

use think\Model;
use think\Request;
use think\Controller;
use app\admin\model\Admin;
use think\Session;

class Login extends Controller
{
    public function login()
    {
        return $this->fetch("login");
    }

    public function checkLogin()
    {
        if (Request::instance()->isPost()) {
            $username = input('userid', '', 'trim');    //获取用户名
            $userPwd  = input('password', '', 'md5');    //获取密码
            $verify   = input('verify');      //获取输入验证码

            //检测用户名密码是否正确
            $userData = model("Admin")->where(["username" => $username])->find();
            if (!$userData || $userData["password"] != $userPwd) {
                $res["state"] = false;
                $res["message"] = "用户名或密码错误";
                return json_encode($res);
            } else {
                // 验证码检测
                if (!captcha_check($verify)) {
                    $res["state"] = false;
                    $res["message"] = "验证码输入错误";
//                    echo $verify;
                    return json_encode($res);
                }
                $data = [
                    "loginip" => Request::instance()->ip(),
                    "logintime" => date("Y-m-d H:i:s")
                ];
                $userData->save($data); //存储登录IP和登录
                session('session_start_time', time());
                Session::set("user_id", $userData["id"]);
                Session::set("user_name", $userData["username"]);
                $res["state"] = true;
                $res["message"] = "登录成功";
                return json_encode($res);
            }
        }
    }

    public function loginOut() {
//        Session::delete("user_id");
//        Session::delete("user_name");
        Session::clear('admin360');
        return $this->fetch("login");
    }

    public function test($id = 2) {
        echo "hello111".$id;
    }
}

//        $admin = new Admin();
//        $this->userName = $_POST['userid'];
//        $this->password = md5($_POST['password']);
//        $this->verify   = $_POST['verify'];
//
////        $userData = $admin->where([
////            "username" => $userName,
////            "password" => $password
////        ])->find();
//        $userData = $admin::get(function($query){
//            $query->where([
//                "username" => $this->userName,
//                "password" => $this->password]);
//        });
//        if(!empty($userData) && captcha_check($this->verify)){
//            $res["login"]  = true;
//            $res["verify"] = true;
//            return json_encode($res);
//        } else {
//            if(empty($userData) && captcha_check($this->verify)) {
//                $res['login']  = false;
//                $res['verify'] = true;
//                return json_encode($res);
//            }
//            if(empty($userData) && !captcha_check($this->verify)) {
//                $res['login']  = false;
//                $res['verify'] = false;
//                return json_encode($res);
//            }
//            if(!empty($userData) && !captcha_check($this->verify)) {
//                $res['login']  = true;
//                $res['verify'] = false;
//                return json_encode($res);
//            }
//        }

//        return json_encode($res,JSON_UNESCAPED_UNICODE); //第一个参数为值，第二个参数为保持中文不转义