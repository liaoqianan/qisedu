<?php
/**
 * Created by PhpStorm.
 * User: design_02
 * Date: 2020/11/16
 * Time: 16:56
 */

namespace app\admin\controller;


class Column extends Base
{
    public function index()
    {
        $list = collection(model('column')->where('is_del',0)->select())->toArray();
        return $this->fetch("Column/index",['list'=>$list]);
    }

    //添加
    public function add()
    {
        if (request()->isPost()){
            $data = input('post.');
            unset($data['file']);
            $res = model('column')->save($data);
            if ($res) {
                return $this->ajaxSuccess('添加成功');
            } else {
                return $this->ajaxError('添加失败');
            }
        }
        return $this->fetch("Column/add");
    }
    //编辑
    public function edit()
    {
        if (request()->isGet()) {
            $id = input('get.id/d');
            $detail = model('column')->where('id', $id)->find();
            return view("Column/add", ['detail'=>$detail]);
        }
        if (request()->isPost()) {
            $postData = input('post.');
            $id = $postData['id'];
            unset($postData['id']);
            unset($postData['file']);
            $res = model('column')->save($postData, ['id'=>$id]);

            if ($res === false) {
                return $this->ajaxError('修改失败');
            } else {
                return $this->ajaxSuccess('修改成功');
            }
        }
    }

    //删除
    public function del()
    {
        $id = input('post.id/d');
        $res = model('column')->save(['is_del'=>1], ['id'=>$id]);
        if ($res === false) {
            return $this->ajaxError('删除失败');
        } else {
            return $this->ajaxSuccess('删除成功');
        }
    }
}