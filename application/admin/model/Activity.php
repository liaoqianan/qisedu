<?php
/**
 * Created by PhpStorm.
 * User: design_02
 * Date: 2020/12/14
 * Time: 14:07
 */

namespace app\admin\model;


use think\Model;

class Activity extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
}