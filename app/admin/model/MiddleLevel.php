<?php
/**
 * Created by PhpStorm.
 * User: LY
 * Date: 2017/12/5
 * Time: 16:17
 */
namespace app\admin\model;

use think\Model;

class MiddleLevel extends Model {

    public function highLevel(){

        return $this->belongsTo("HighLevel", "id");

    }
}