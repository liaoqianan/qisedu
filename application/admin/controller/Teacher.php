<?php
/**
 * Created by PhpStorm.
 * User: design_02
 * Date: 2020/11/16
 * Time: 15:31
 */

namespace app\admin\controller;
use app\admin\model\Course as CourseModel;
use app\admin\model\CourseNode;

class Teacher extends Base
{
    //首页
    public function index()
    {

        return view("Teacher/index",['type'=>[['id'=>1,'title'=>'老师'],['id'=>2,'title'=>'助教']]]);
    }
    //ajax获取课程数据
    public function ajaxGetIndex() {
        $start = input('post.start', '0', 'trim');
        $limit = input('post.length', '20', 'trim');
        $name = input('get.name/s');
        $type = input('get.type', '', 'trim');
        $where['is_del'] = 0;
        if($type != '' && $type != ' ')
        {
            $where['type'] = ['=', $type];
        }
        if($name != '')
        {
            $where['name'] = ['like', '%'.$name.'%'];
        }
        $total = db('teacher_assistant')->where($where)->count();
        $list = db('teacher_assistant')->where($where)->limit($start, $limit)->order('id desc')->select();

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
        if (request()->isAjax()){
            $data = input('post.');

            $column_id = get_teacher($data['rule']);
            $data['column_id'] = $column_id;
            unset($data['file']);
            unset($data['pic']);
            unset($data['rule']);
            $res = db('teacher_assistant')->insert($data);
            if ($res) {
                return $this->ajaxSuccess('添加成功');
            } else {
                return $this->ajaxError('添加失败');
            }
        }
        $column = model('column')->where(['is_del'=>0])->field('id,title')->select();

        return view("Teacher/add",['column'=>$column]);
    }
    //修改
    public function edit()
    {
        if (request()->isGet()) {
            $id = input('get.id/d');
            $detail = db('teacher_assistant')->where('id', $id)->find();
            $column = model('column')->where(['is_del'=>0])->field('id,title')->select();
            return view("Teacher/add", ['detail'=>$detail,'column'=>$column]);
        } elseif (request()->isPost()) {
            $postData = input('post.');
            $column_id = get_teacher($postData['rule']);
            $data['column_id'] = $column_id;
            unset($postData['file']);
            $id = $postData['id'];
            unset($postData['id']);
            unset($postData['pic']);
            unset($data['rule']);
            $res = db('teacher_assistant')->where(['id'=>$id])->update($postData);

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
    public function del() {
        $id = input('post.id/d');

        $res = db('teacher_assistant')->where(['id'=>$id])->update(['is_del'=>1]);

        if ($res === false) {
            return $this->ajaxError('删除失败');
        } else {
            return $this->ajaxSuccess('删除成功');
        }
    }
    //课节列表
    public function course_node()
    {
        $course = model('course')->where(['is_del'=>0])->field('id,title as title')->select();
        return view("Course_node/index",['course'=>$course]);
    }
    //ajax获取课节数据
    public function ajaxGetCourse_node() {
        $start = input('post.start', '0', 'trim');
        $limit = input('post.length', '20', 'trim');
        $title = input('get.title/s');
        $course_id = input('get.course_id', '', 'trim');
        $where['is_del'] = 0;
        if($course_id != '' && $course_id != ' ')
        {
            $where['course_id'] = ['=', $course_id];
        }
        if($title != '')
        {
            $where['title'] = ['like', '%'.$title.'%'];
        }
        $total = model('course_node')->where($where)->count();
        $list = model('course_node')->where($where)->limit($start, $limit)->order('order desc')->order('create_time desc')->select();

        foreach ($list as &$item){
            $item['course_title'] = model('course')->where(['id'=>$item['course_id'],'is_del'=>0])->value('title');
            $item['broadcast_time'] = date('Y-m-d H:i:s',$item['broadcast_time']);
        }
        $data = array(
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $list
        );
        return json($data);
    }
    //课节添加
    public function course_node_add()
    {
        $course = model('course')->where(['is_del'=>0])->select();
        if (request()->isAjax()){
            $data = input('post.');
            unset($data['file']);

            $data['broadcast_time'] = strtotime($data['broadcast_time']);

            $CourseNode = new CourseNode();
            $CourseNode->save($data);

            if ($CourseNode->id) {
                return $this->ajaxSuccess('添加成功');
            } else {
                return $this->ajaxError('添加失败');
            }
        }
        return view("Course_node/add",['course'=>$course]);
    }
    //修改
    public function course_node_edit()
    {
        if (request()->isGet()) {
            $id = input('get.id/d');
            $detail = model('course_node')->where('id', $id)->find();
            $detail['broadcast_time'] = date("Y-m-d H:i:s",$detail['broadcast_time']);
            $course = model('course')->where(['is_del'=>0])->field('id,title')->select();
            return view("Course_node/add", ['detail'=>$detail,'course'=>$course]);
        } elseif (request()->isPost()) {
            $postData = input('post.');
            unset($postData['file']);
            $id = $postData['id'];
            unset($postData['id']);
            $postData['broadcast_time'] = strtotime($postData['broadcast_time']);
            $res = model('course_node')->save($postData, ['id'=>$id]);

            if ($res === false) {
                return $this->ajaxError('修改失败');
            } else {
                return $this->ajaxSuccess('修改成功');
            }
        } else {
            return $this->ajaxError('非法操作');
        }
    }

    //课节删除
    public function course_node_del()
    {
        $id = input('post.id/d');
        $res = model('course_node')->save(['is_del'=>1], ['id'=>$id]);
        if ($res === false) {
            return $this->ajaxError('删除失败');
        } else {
            return $this->ajaxSuccess('删除成功');
        }
    }
}