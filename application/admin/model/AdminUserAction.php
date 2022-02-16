<?php

namespace app\admin\model;

use think\Model;

class AdminUserAction extends Model {

    /*
     * 根据查询条件获取列表
     */
    public function getListByWhere($where)
    {
        return $this->where($where)->select();
    }

    /*
     * 根据限制数量获取列表
     */
    public function getListLimit($where, $start, $limit, $order='')
    {
        if(empty($limit))
        {
            return [];
        }

        return $this->where($where)->limit($start, $limit)->order($order)->select();
    }

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
     * 根据查询条件获取数量
     */
    public function getCountByWhere($where)
    {
        return $this->where($where)->count();
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
}