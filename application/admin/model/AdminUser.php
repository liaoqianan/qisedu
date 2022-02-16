<?php
namespace app\admin\model;

use think\Model;

class AdminUser extends Model {

    /*
     * 获取一条目录信息
     */
    public function getOneByData($data)
    {
        if(empty($data))
        {
            return [];
        }

        return $this->get($data);
    }

    /*
     * 保存数据
     */
    public function saveData($data)
    {
        if(empty($data))
        {
            return false;
        }

        $this->save($data);
        return true;
    }

    /*
     * 根据查询条件保存数据
     */
    public function saveDataByWhere($data, $where)
    {
        if(empty($data) && empty($where))
        {
            return false;
        }

        $this->save($data, $where);
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
     * 根据UID查询数据
     */
    public function getOneUser($uid)
    {
        if(!$uid)
        {
            return false;
        }

        
        return $this->where(['id'=>$uid])->find();
    }
}