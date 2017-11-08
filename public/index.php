<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//// [ 应用入口文件 ]
//
//// 定义应用目录
//define('APP_PATH', __DIR__ . '/../app/');
//
//// 定义配置文件
//define('CONF_PATH', __DIR__ . '/../conf/');
//
//// 加载框架引导文件
//require __DIR__ . '/../thinkphp/start.php';

// 定义应用目录
define('APP_PATH', __DIR__ . '/../app/');

// 定义配置文件
define('CONF_PATH', __DIR__ . '/../conf/');
require __DIR__ . '/../thinkphp/base.php';// 加载框架基础文件
//开启域名部署后
switch ($_SERVER['HTTP_HOST']) {
    case 'home.360.com:8080':
//    case 'http://localhost:8080/APP_360/public/index.php':
        $model = 'index';// index模块
        $route = true;// 开启路由
        break;
    case 'admin.360.com:8080':
//    case 'http://localhost:8080/APP_360/public/admin.php':
        $model = 'admin';// admin模块
        $route = true;// 关闭路由
        break;
}
\think\Route::bind(isset($model) ? $model : 'index');// 绑定当前入口文件到模块
\think\App::route(isset($route) ? $route : true);// 路由

//  ??仅支持PHP7以上
//\think\Route::bind($model ?? 'index');// 绑定当前入口文件到模块
//\think\App::route($route ?? true);// 路由

\think\App::run()->send();// 执行应用