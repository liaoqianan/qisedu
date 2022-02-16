<?php
/**
 * Created by PhpStorm.
 * User: design_02
 * Date: 2020/12/18
 * Time: 14:14
 */


namespace app\admin\controller;

class Question extends Base
{
    //首页
    public function index()
    {

        /*$list = db('question')->where('q_type',3)->order('create_time')->select();
        $num = 1;
        foreach ($list as $v){
            db('question')->where('id',$v['id'])->update(['number'=>$num]);
            $num++;
        }*/
        return view("Question/index");
    }

    //ajax获取数据
    public function ajaxGetIndex()
    {
        $start = input('post.start', '0', 'trim');
        $limit = input('post.length', '20', 'trim');
        $q_type = input('get.q_type/s');
        $where['is_del'] = 0;
        if ($q_type != '') {
            $where['q_type'] = ['=',$q_type];
        }
        $total = db('question')->where($where)->count();
        $list = db('question')->where($where)->limit($start, $limit)->order('number desc')->select();
        foreach ($list as $k=>&$v){
            $v['num'] = $total - $k;
            $v['create_time'] = date('Y-m-d H:i',$v['create_time']);
            $v['title'] = get_text($v['title'],0,80);
        }
        $data = array(
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $list
        );
        return json($data);
    }

    //添加
    public function add()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            $data['create_time'] = time();
            $data['update_time'] = time();
            unset($data['file']);
            $res = db('question')->insertGetId($data);
            if ($res) {
                return $this->ajaxSuccess('添加成功');
            } else {
                return $this->ajaxError('添加失败');
            }
        }
        return view("Question/add");
    }

    //修改
    public function edit()
    {
        if (request()->isGet()) {
            $id = input('get.id/d');
            $detail = db('question')->where('id', $id)->find();
            return view("Question/add", ['detail' => $detail]);
        } elseif (request()->isPost()) {
            $postData = input('post.');
            unset($postData['file']);

            $id = $postData['id'];
            unset($postData['id']);

            $postData['update_time'] = time();
            $res = db('question')->where('id' ,$id)->update($postData);

            if ($res === false) {
                return $this->ajaxError('修改失败');
            } else {
                return $this->ajaxSuccess('修改成功');
            }
        } else {
            return $this->ajaxError('非法操作');
        }
    }

    //删除
    public function del()
    {
        $id = input('post.id/d');

        $res = db('question')->where('id' ,$id)->update(['is_del' => 1]);

        if ($res === false) {
            return $this->ajaxError('删除失败');
        } else {
            return $this->ajaxSuccess('删除成功');
        }
    }
}