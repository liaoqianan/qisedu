<?php
/**
 * Created by PhpStorm.
 * User: design_02
 * Date: 2020/12/16
 * Time: 9:58
 */

namespace app\admin\controller;


use app\common\HttpCurl;

class Training extends Base
{
    //小课堂直播列表
    public function index()
    {
        return view("Training/index");
    }
    //获取直播列表
    public function ajaxgetindex()
    {
        $start = input('post.start', '0', 'trim');
        $limit = input('post.length', '20', 'trim');
        $title = input('get.subject/s');
        if($title != '')
        {
            $where['subject'] = ['like', '%'.$title.'%'];
        }
        $where['is_del'] = 0;
        $total = db('config_training')->where($where)->count();
        $list = db('config_training')->where($where)->limit($start, $limit)->order('id desc')->select();
        foreach ($list as &$item){
            $json = json_decode($item['text'],true);
            $item['teacherJoinUrl'] = $json['teacherJoinUrl'];
            $item['studentJoinUrl'] = $json['studentJoinUrl'];
        }
        $data = array(
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $list
        );
        return json($data);
    }
    //发布直播课堂
    public function add()
    {
        if (request()->isGet()){
            return view("Training/add");
        }
        if (request()->isPost()){
            $http = new HttpCurl();
            //请求链接
            $url = config('training.url').'/integration/site/training/room/created';
            $data = input('post.');
            $data['loginName'] = config('training.loginName');
            $data['password'] = config('training.password');
            $res = $http->curlRequest($url,$data,'POST');
            $res = json_decode($res,true);
            if ($res['code'] == 0){
                unset($data['loginName']);
                unset($data['password']);
                $data['sdk_id'] = $res['id'];
                $data['text'] = json_encode($res);
                $res = db('config_training')->insert($data);
                if ($res){
                    return $this->ajaxSuccess('发布成功');
                }else{
                    return $this->ajaxError('发布失败');
                }
            }
        }
    }

    //编辑
    public function edit()
    {
        if (request()->isGet()){
            $id = input('get.id/d');
            $detail = db('config_training')->where('id', $id)->find();
            $detail['sdk_id'] = json_decode($detail['text'],true)['id'];
            return view("Training/add", ['detail'=>$detail]);
        }
        if (request()->isPost()){
            $http = new HttpCurl();
            //请求链接
            $url = config('training.url').'/integration/site/training/room/modify';
            $data = input('post.');
            $data['loginName'] = config('training.loginName');
            $data['password'] = config('training.password');
            $res = $http->curlRequest($url,$data,'POST');
            $res = json_decode($res,true);
            if ($res['code'] == 0){
                unset($data['loginName']);
                unset($data['password']);
                $url = config('training.url').'/integration/site/training/room/info';
                //重新请求获取信息
                $data['text'] = $http->curlRequest($url,['loginName'=>config('training.loginName'),'password'=>config('training.password'),'roomId'=>$data['id']],'POST');
                $id = $data['id'];
                unset($data['id']);
                $res = db('config_training')->where('sdk_id',$id)->update($data);
                if ($res){
                    return $this->ajaxSuccess('修改成功');
                }else{
                    return $this->ajaxError('修改失败');
                }
            }
        }
    }
    //删除
    public function del()
    {
        $http = new HttpCurl();
        //请求链接
        $url = config('training.url').'/integration/site/training/room/deleted';
        $data['roomId'] = input('post.id');
        $data['loginName'] = config('training.loginName');
        $data['password'] = config('training.password');
        $res = $http->curlRequest($url,$data,'POST');
        $res = json_decode($res,true);
        if ($res['code'] == 0){
            $res = db('config_training')->where('sdk_id',$data['roomId'])->update(['is_del'=>1]);
            if ($res){
                return $this->ajaxSuccess('删除成功');
            }else{
                return $this->ajaxError('删除失败');
            }
        }else{
            return $this->ajaxError('数据不存在');
        }
    }

    //获取课堂下所录制的课件
    public function get_courseware()
    {
        $http = new HttpCurl();
        //获取录播请求链接
        $url = config('training.url').'/integration/site/training/courseware/info';
        $data['roomId'] = 'coursewareId';
        //获取观看人数
        //$url = config('training.url').'/integration/site/training/vod/vodview';
        //$data['id'] = '43NfHvi1ix';
        $data['loginName'] = config('training.loginName');
        $data['password'] = config('training.password');
        $res = $http->curlRequest($url,$data,'POST');
        dump($res);
        $res = json_decode($res,true);
    }
}