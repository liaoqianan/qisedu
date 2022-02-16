<?php

namespace app\admin\model;

use think\Model;

class AdminAuthRule extends Model {

    /*
     * 根据查询条件获取列表
     */
    public function getListByWhere($where)
    {
        return collection($this->where($where)->select())->toArray();
    }

    /*
     * 保存多条数据
     */
    public function saveAllData($data)
    {
        if(empty($data))
        {
            return false;
        }
        $this->saveAll($data);
        return true;
    }

    /*
    * 根据查询条件删除数据
    */
    public function delByWhere($where)
    {
        if(empty($where))
        {
            return false;
        }

        $this->where($where)->delete();
        return true;
    }

    /*
    * 根据查询条件查询是否存在此权限
    */
    public function getByGroupId($groupId,$url)
    {
        if(!$groupId || !$url)
        {
            return false;
        }
        $res = $this->where(['groupId'=>$groupId,'url'=>$url])->find();

        return !empty($res) ? true : false;
    }
}