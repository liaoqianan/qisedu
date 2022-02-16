<?php
/**
 * Created by PhpStorm.
 * User: design_02
 * Date: 2020/12/18
 * Time: 11:24
 */

namespace app\admin\model;


use think\Model;

class Article extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
}