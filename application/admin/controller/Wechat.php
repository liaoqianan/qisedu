<?php
/**
 * Created by PhpStorm.
 * User: design_02
 * Date: 2020/12/16
 * Time: 9:58
 */

namespace app\admin\controller;


use app\common\HttpCurl;
use app\common\Wechat\Menu;

class Wechat extends Base
{
    //小课堂直播列表
    public function index()
    {
        return view("Wechat/index");
    }
    //获取直播列表
    public function ajaxgetindex()
    {

        $list = collection(model('WechatMenu')->getTree())->toArray();
        $total = count($list);
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
            //分类
            $type = model('WechatMenu')->getTree();
            return view("Wechat/add",['type'=>$type]);
        }
        if (request()->isPost()){
            $data = input('post.');
            if ($data['type'] == 'view'){
                if (!$data['url']){
                    return $this->ajaxError('链接不能为空！');
                }
            }elseif($data['type'] == 'click'){
                if (!$data['key']){
                    return $this->ajaxError('关键字不能为空！');
                }
            }else{
                if (!$data['url']){
                    return $this->ajaxError('链接不能为空！');
                }
                if (!$data['appid']){
                    return $this->ajaxError('小程序appid不能为空！');
                }
                if (!$data['pagepath']){
                    return $this->ajaxError('小程序路径不能为空！');
                }
            }
            $res = model('WechatMenu')->insertGetId($data);
            if ($res){
                return $this->ajaxSuccess('发布成功');
            }else{
                return $this->ajaxError('发布失败');
            }
        }
    }

    //编辑
    public function edit()
    {
        if (request()->isGet()){
            $id = input('get.id/d');
            $detail = model('WechatMenu')->where('id', $id)->find();
            //分类
            $type = model('WechatMenu')->getTree();
            return view("Wechat/add", ['detail'=>$detail,'type'=>$type]);
        }
        if (request()->isPost()){
            $data = input('post.');
            if ($data['pid'] == $data['id']){
                return $this->ajaxError('不能选择本身栏目！');
            }
            if ($data['type'] == 'view'){
                if (!$data['url']){
                    return $this->ajaxError('链接不能为空！');
                }
            }elseif($data['type'] == 'click'){
                if (!$data['key']){
                    return $this->ajaxError('关键字不能为空！');
                }
            }else{
                if (!$data['url']){
                    return $this->ajaxError('链接不能为空！');
                }
                if (!$data['appid']){
                    return $this->ajaxError('小程序appid不能为空！');
                }
                if (!$data['pagepath']){
                    return $this->ajaxError('小程序路径不能为空！');
                }
            }
            $id = $data['id'];
            unset($data['id']);
            $res = model('WechatMenu')->where('id',$id)->update($data);
            if ($res){
                return $this->ajaxSuccess('修改成功');
            }else{
                return $this->ajaxError('修改失败');
            }
        }
    }
    //删除
    public function del()
    {
        $id = input('post.id');
        if (!$id){
            return $this->ajaxError('参数有误！');
        }
        $res = model('WechatMenu')->where('id',$id)->update(['is_del'=>1]);
        if ($res){
            return $this->ajaxSuccess('删除成功');
        }else{
            return $this->ajaxError('删除失败');
        }
    }
    /**
     * 生成菜单
     */
    public function setMenu()
    {
        if ($this->request->isAjax()) {
            $config = config('Wechat');
            $data['button'] = model('WechatMenu')->getMenu();
            try {

                // 实例接口
                $menu = new Menu($config);

                // 执行创建菜单
                $menu->create($data);

            } catch (Exception $e){
                // 异常处理
                echo  $e->getMessage();
            }
            $this->success('设置菜单成功');
        }
    }
    //获取课堂下所录制的课件
    public function get_courseware()
    {
        $http = new HttpCurl();
        //获取录播请求链接
        $url = config('webcast.url').'/integration/site/training/courseware/info';
        $data['roomId'] = 'coursewareId';
        //获取观看人数
        //$url = config('webcast.url').'/integration/site/Webcast/vod/vodview';
        //$data['id'] = '43NfHvi1ix';
        $data['loginName'] = config('webcast.loginName');
        $data['password'] = config('webcast.password');
        $res = $http->curlRequest($url,$data,'POST');
        dump($res);
        $res = json_decode($res,true);
    }
}