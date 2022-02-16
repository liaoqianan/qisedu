<?php
/**
 * Created by PhpStorm.
 * User: design_02
 * Date: 2020/12/18
 * Time: 13:54
 */

namespace app\api\model;


use think\Model;

class CourseNode extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
}