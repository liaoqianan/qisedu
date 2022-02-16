<?php

namespace app\admin\model;

use think\Model;

class AdminMenu extends Model {
	protected $resultSetType = 'collection';

	/*
	 * 获取所有目录
	 */
	public function getAll()
    {
        return $this->order('sort asc')->select()->toArray();
    }

	/*
	 * 排除ID获取目录
	 */
	public function getMenuExcludeById($id)
    {
        if(empty($id))
        {
            return [];
        }

        return $this->where(['id'=>['<>', $id]])->order('sort asc')->select();
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