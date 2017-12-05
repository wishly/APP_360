<?php
/**
 * Created by PhpStorm.
 * User: LY
 * Date: 2017/12/4
 * Time: 15:45
 */
namespace app\admin\controller;

use app\admin\common\Base as BaseController;

class BaseFunction extends BaseController {
    public function getParentsNames($id) {
        /*查找datalist数据*/
        $data = model('Datalist')->where(array('id'=> $id))->find();
        /*如果不存在中级分类，只显示高级分类*/
        if(!empty($data['middle_id'])) {
            $parent = $this->getParentsByMiddleId($data['middle_id']);
            $elementary = model('elementary_level')->where(array('id'=>$data['elementary_id']))->value('elementary_name');
            if(!empty($elementary)){
                $string = $parent.'-->'.$elementary;
            } else {
                $string = $parent;
            }
        } else {
            $string = model('high_level')->where(array('id'=> $data['high_id']))->value('high_name');
        }

        return $string;
    }

    public function getParentsByMiddleId($middle_id) {
        /*获取中级分类信息*/
        $middle = model('middle_level')->where(array('id'=> $middle_id))->field('id,high_id,middle_name')->find();
        /*获取高级分类信息*/
        $high   = model('high_level')->where(array('id'=> $middle['high_id']))->value('high_name');
        $middle_name = $middle['middle_name'];

        if(!empty($middle_name)){
            $string = $high.'-->'.$middle_name;
        } else {
            $string = $high;
        }
        return $string;
    }
}