<?php
/**
 * Created by PhpStorm.
 * User: LY
 * Date: 2017/11/9
 * Time: 21:07
 */

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\captcha\Captcha;

class Test extends Controller
{
    public function index() {
        return "hello world";
    }
    public function hello() {
        return "hello ]";
    }
}
