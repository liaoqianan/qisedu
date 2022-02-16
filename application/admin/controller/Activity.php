<?php
/**
 * Created by PhpStorm.
 * User: design_02
 * Date: 2020/12/14
 * Time: 14:04
 */

namespace app\admin\controller;
use app\admin\model\Activity as ActivityModel;

class Activity extends Base
{
    //首页
    public function index()
    {
        return view("Activity/index");
    }
    //ajax获取课程数据
    public function ajaxGetIndex() {
        $start = input('get.start', '0', 'trim');
        $limit = input('get.length', '20', 'trim');
        $title = input('get.title/s');
        $where['is_del'] = 0;

        if($title != '')
        {
            $where['title'] = ['like', '%'.$title.'%'];
        }
        $total = model('activity')->where($where)->count();
        $list = model('activity')->where($where)->limit($start, $limit)->order('order desc')->order('create_time desc')->select();
        foreach ($list as &$item){
            $item['time'] = date('Y-m-d H:i:s',$item['time']);
        }
        $data = array(
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $list
        );
        return json($data);
    }
    //添加
    public function add()
    {
        if (request()->isPost()){
            $data = input('post.');
            unset($data['file']);
            $data['time'] = strtotime($data['time']);
            $data['surplus_num'] = $data['enroll_num'];
            $ActivityModel = new ActivityModel();
            $ActivityModel->save($data);

            if ($ActivityModel->id) {
                return $this->ajaxSuccess('添加成功');
            } else {
                return $this->ajaxError('添加失败');
            }
        }
        return view("Activity/add");
    }
    //修改
    public function edit()
    {
        if (request()->isGet()) {
            $id = input('get.id/d');
            $detail = model('Activity')->where('id', $id)->find();
            $detail['time'] = date("Y-m-d H:i:s",$detail['time']);
            return view("Activity/add", ['detail'=>$detail]);
        } elseif (request()->isPost()) {
            $postData = input('post.');
            unset($postData['file']);

            $id = $postData['id'];
            unset($postData['id']);
            $postData['time'] = strtotime($postData['time']);
            $res = model('Activity')->save($postData, ['id'=>$id]);

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

        $res = model('Activity')->save(['is_del'=>1], ['id'=>$id]);

        if ($res === false) {
            return $this->ajaxError('删除失败');
        } else {
            return $this->ajaxSuccess('删除成功');
        }
    }

    /**
    报名人数详情
     */
    public function details()
    {
        return view("Activity/details",['id'=>input('id')]);
    }
    //ajax获取课程数据
    public function ajaxGetdetails() {
        $start = input('get.start', '0', 'trim');
        $limit = input('get.length', '20', 'trim');
        $title = input('get.name/s');
        $where['activity_id'] = input('id');
        if($title != '')
        {
            $where['name|mobile'] = ['like', '%'.$title.'%'];
        }
        $total = db('partake_activity')->where($where)->count();
        $list = db('partake_activity')->where($where)->limit($start, $limit)->order('id desc')->select();
        foreach ($list as &$item){
            $item['partake_time'] = date('Y-m-d H:i:s',$item['partake_time']);
        }
        $data = array(
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $list
        );
        return json($data);
    }
}