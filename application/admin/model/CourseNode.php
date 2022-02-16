<?php
/**
 * Created by PhpStorm.
 * User: design_02
 * Date: 2020/12/3
 * Time: 16:20
 */

namespace app\admin\model;


use think\Model;

class CourseNode extends Model
{
    protected $autoWriteTimestamp = true;
    protected $tableName = 'Course_Node';
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
}