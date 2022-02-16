<?php
/**
 * Created by PhpStorm.
 * User: design_02
 * Date: 2020/11/16
 * Time: 15:33
 */

namespace app\admin\model;

use think\Model;

class Course extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
}