<?php
/**
 * Created by PhpStorm.
 * User: LY
 * Date: 2018/1/19
 * Time: 11:33
 */
namespace app\admin\validate;

use think\Validate;

class DataList extends Validate {
    protected $rule = [
        'id' => 'number'
    ];

    protected function checkName($value, $rule, $data) {
        return $rule == $value ? true : 'ID错误';
    }
}