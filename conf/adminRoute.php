<?php
/**
 * Created by PhpStorm.
 * User: LY
 * Date: 2017/11/2
 * Time: 22:02
 */

return [
//  'hello/[:id]' => 'admin/Index/hello'
    '__pattern__' => [
        'name' => '\w+'
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],
    'captcha/[:id]' => ['\think\captcha\CaptchaController@index',['method'=>'get'], ['id' => '\d+']],
    'test/[:id]'    => 'admin/Login/test',
    'hello/[:id]'   => 'Index/hello',
];