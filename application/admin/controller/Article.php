<?php
/**
 * Created by PhpStorm.
 * User: design_02
 * Date: 2020/12/18
 * Time: 14:14
 */


namespace app\admin\controller;
use app\admin\model\Article as ArticleModel;

class Article extends Base
{
    //首页
    public function index()
    {
        return view("Article/index");
    }

    //ajax获取课程数据
    public function ajaxGetIndex()
    {
        $start = input('post.start', '0', 'trim');
        $limit = input('post.length', '20', 'trim');
        $title = input('get.title/s');
        $where['is_del'] = 0;
        if ($title != '') {
            $where['title'] = ['like', '%' . $title . '%'];
        }
        $total = model('article')->where($where)->count();
        $list = model('article')->where($where)->limit($start, $limit)->order('order desc')->order('create_time desc')->select();

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
            unset($data['file']);
            $res = model('Article')->save($data);
            if ($res) {
                return $this->ajaxSuccess('添加成功');
            } else {
                return $this->ajaxError('添加失败');
            }
        }
        return view("Article/add");
    }

    //修改
    public function edit()
    {
        if (request()->isGet()) {
            $id = input('get.id/d');
            $detail = model('Article')->where('id', $id)->find();
            return view("Article/add", ['detail' => $detail]);
        } elseif (request()->isPost()) {
            $postData = input('post.');
            unset($postData['file']);

            $id = $postData['id'];
            unset($postData['id']);

            $res = model('Article')->save($postData, ['id' => $id]);

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

        $res = model('Article')->save(['is_del' => 1], ['id' => $id]);

        if ($res === false) {
            return $this->ajaxError('删除失败');
        } else {
            return $this->ajaxSuccess('删除成功');
        }
    }
}