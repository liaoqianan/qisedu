<?php
/**
 * Created by PhpStorm.
 * User: design_02
 * Date: 2020/11/16
 * Time: 10:43
 */

namespace app\admin\controller;

use app\admin\model\Banner as BannerModel;

class Banner extends Base
{
    //列表
    public function index()
    {
        $list = collection(model('banner')->where('is_del',0)->order('order desc')->order('create_time desc')->select())->toArray();
        return $this->fetch("banner/index",['list'=>$list]);
    }
    //添加
    public function add()
    {
        if (request()->isPost()){
            $data = input('post.');
            $data['status'] = isset($data['status']) ? 1 : 0;
            unset($data['file']);
            $BannerModel = new BannerModel();
            $res = $BannerModel->save($data);
            if ($res) {
                return $this->ajaxSuccess('添加成功');
            } else {
                return $this->ajaxError('添加失败');
            }
        }
        return $this->fetch("banner/add");
    }
    //编辑
    public function edit()
    {
        if (request()->isGet()) {
            $id = input('get.id/d');
            $detail = model('banner')->where('id', $id)->find();
            return view("banner/add", ['detail'=>$detail]);
        }
        if (request()->isPost()) {
            $postData = input('post.');
            $postData['status'] = isset($postData['status']) ? 1 : 0;
            $id = $postData['id'];
            unset($postData['id']);
            unset($postData['file']);
            $res = model('banner')->save($postData, ['id'=>$id]);

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
        $res = model('banner')->save(['is_del'=>1], ['id'=>$id]);
        if ($res === false) {
            return $this->ajaxError('删除失败');
        } else {
            return $this->ajaxSuccess('删除成功');
        }
    }
    //显示
    public function open() {
        if ($this->request->isPost()) {
            $id = input('post.id', '0', 'trim');
            $res = model('banner')->update(['status' => 1], ['id' => $id]);
            if ($res === false) {
                return $this->ajaxError('操作失败');
            } else {
                return $this->ajaxSuccess('操作成功');
            }
        }
    }

    //隐藏
    public function close() {
        if ($this->request->isPost()) {
            $id = input('post.id', '0', 'trim');
            $res = model('banner')->update(['status' => 0], ['id' => $id]);
            if ($res === false) {
                return $this->ajaxError('操作失败');
            } else {
                return $this->ajaxSuccess('操作成功');
            }
        }
    }
}