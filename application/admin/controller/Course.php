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
use app\common\HttpCurl;
use app\common\Live;
use think\Db;

class Course extends Base
{
    //首页
    public function index()
    {
        $column = model('column')->where(['is_del'=>0])->select();
        return view("Course/index",['column'=>$column]);
    }
    //ajax获取课程数据
    public function ajaxGetIndex() {
        $start = input('post.start', '0', 'trim');
        $limit = input('post.length', '20', 'trim');
        $title = input('get.title/s');
        $column_id = input('get.column_id', '', 'trim');
        $where['is_del'] = 0;
        if($column_id != '' && $column_id != ' ')
        {
            $where['column_id'] = ['=', $column_id];
        }
        if($title != '')
        {
            $where['title'] = ['like', '%'.$title.'%'];
        }
        $total = model('course')->where($where)->count();
        $list = model('course')->where($where)->limit($start, $limit)->order('order desc')->order('create_time desc')->select();

        foreach ($list as &$item){
            $item['column'] = model('column')->where(['id'=>$item['column_id'],'is_del'=>0])->value('title');
            $item['broadcast_time'] = date('Y-m-d H:i:s',$item['broadcast_time']);
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
        $column = model('column')->where(['is_del'=>0])->select();
        if (request()->isAjax()){
            $data = input('post.');
            if ($data['after_sales']==1){
                if(!$data['assistant']) return $this->ajaxError('请填写助教id');
                $data['assistant'] = str_replace('，',',',$data['assistant']);
                foreach (explode(',',$data['assistant']) as $v){
                    if (!is_numeric($v)){
                        return $this->ajaxError('助教id'.$v.'不是一个整数值');
                    }
                    $res = db('teacher_assistant')->where('id',$v)->find();
                    if (!$res){
                        return $this->ajaxError('助教id'.$v.'不存在');
                    }
                }
            }else{
                $qr_code = db('config')->where('type','wechat')->value('content');
                if (!$qr_code){
                    return $this->ajaxError('请先在系统配置上传公众号二维码');
                }
            }
            unset($data['file']);
            $data['broadcast_time'] = strtotime($data['broadcast_time']);

            $CourseModel = new CourseModel();
            $CourseModel->save($data);

            if ($CourseModel->id) {
                return $this->ajaxSuccess('添加成功');
            } else {
                return $this->ajaxError('添加失败');
            }
        }
        return view("Course/add",['column'=>$column]);
    }
    //修改
    public function edit()
    {
        if (request()->isGet()) {
            $id = input('get.id/d');
            $detail = model('course')->where('id', $id)->find();
            $detail['broadcast_time'] = date("Y-m-d H:i:s",$detail['broadcast_time']);
            $column = model('column')->where(['is_del'=>0])->select();
            return view("Course/add", ['detail'=>$detail,'column'=>$column]);
        } elseif (request()->isPost()) {
            $postData = input('post.');
            unset($postData['file']);
            if ($postData['after_sales']==1){
                if(!$postData['assistant']) return $this->ajaxError('请填写助教id');
                $postData['assistant'] = str_replace('，',',',$postData['assistant']);
                foreach (explode(',',$postData['assistant']) as $v){
                    if (!is_numeric($v)){
                        return $this->ajaxError('助教id'.$v.'不是一个整数值');
                    }
                    $res = db('teacher_assistant')->where('id',$v)->find();
                    if (!$res){
                        return $this->ajaxError('助教id'.$v.'不存在');
                    }
                }
            }else{
                $qr_code = db('config')->where('type','wechat')->value('content');
                if (!$qr_code){
                    return $this->ajaxError('请先在系统配置上传公众号二维码');
                }
            }
            $id = $postData['id'];
            unset($postData['id']);
            $postData['broadcast_time'] = strtotime($postData['broadcast_time']);
            $res = model('course')->save($postData, ['id'=>$id]);

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

        $res = model('course')->save(['is_del'=>1], ['id'=>$id]);

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
        $course = db('course')->where('is_del',0)->column('id');
        $total = model('course_node')->whereIn('course_id',$course)->where($where)->count();
        $list = model('course_node')->whereIn('course_id',$course)->where($where)->limit($start, $limit)->order('order desc')->order('create_time desc')->select();

        foreach ($list as &$item){
            $item['course_title'] = model('course')->where(['id'=>$item['course_id'],'is_del'=>0])->value('title');
            $item['broadcast_time'] = date('Y-m-d H:i:s',$item['broadcast_time']);
            $item['client'] = $item['web'] = $item['url'] = $item['channelPasswd'] = '';
            if ($item['type'] == 1){
                $res = db('live')->where('c_id',$item['id'])->find();
                if ($res){
                    $item['client'] = $res['client'];
                    $item['web'] = $res['web'];
                    $item['url'] = $res['url'];
                    $item['channelPasswd'] = $res['channelPasswd'];
                }elseif($item['parameter']){
                    $item['client'] = 'https://live.polyv.net/start-client.html?channelId='.$item['parameter'];
                    $item['web'] = 'https://live.polyv.net/web-start/?channelId='.$item['parameter'];
                    $item['channelPasswd'] = '123456';
                }
            }
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
            if ($data['type']==1){
                $params = [
                    'name' => $data['title'],
                    'channelPasswd' => 123456
                ];
                $res = Live::create_Live($params);
                if (!empty($res['code'])){
                    return $this->ajaxError($res['message']);
                }
                $data['parameter'] = $res['channelId'];
                $params['channelId'] = $res['channelId'];
                $params['client'] = 'https://live.polyv.net/start-client.html?channelId='.$res['channelId'];
                $params['web'] = 'https://live.polyv.net/web-start/?channelId='.$res['channelId'];
                $params['url'] = $res['url'];
            }elseif ($data['type'] == 3){
                if (!$data['video']){
                    return $this->ajaxError('请上传视频文件');
                }
            }elseif ($data['type'] == 4){
                if (!$data['music']){
                    return $this->ajaxError('请上传音频文件');
                }
            }
            unset($data['file']);

            $data['broadcast_time'] = strtotime($data['broadcast_time']);

            Db::startTrans();
            $CourseNode = new CourseNode();
            $CourseNode->save($data);

            if ($CourseNode->id) {
                if ($data['type'] == 1){
                    $params['c_id'] = $CourseNode->id;
                    $params['time'] = time();
                    $res = db('live')->where('channelId',$params['channelId'])->find();
                    if ($res){
                        Db::rollback();
                        return $this->ajaxError('该直播已经存在！');
                    }else{
                        db('live')->insert($params);
                    }
                }
                Db::commit();
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
            if (!db('live')->where('c_id',$postData['id'])->value('id')){
                if ($postData['type']==1){
                    $params = [
                        'name' => $postData['title'],
                        'channelPasswd' => 123456
                    ];
                    $res = Live::create_Live($params);
                    if (!empty($res['code'])){
                        return $this->ajaxError($res['message']);
                    }
                    $postData['parameter'] = $res['channelId'];
                    $params['channelId'] = $res['channelId'];
                    $params['client'] = 'https://live.polyv.net/start-client.html?channelId='.$res['channelId'];
                    $params['web'] = 'https://live.polyv.net/web-start/?channelId='.$res['channelId'];
                    $params['url'] = $res['url'];
                    $params['c_id'] = $postData['id'];
                    $params['time'] = time();
                    db('live')->insert($params);
                }
            }
            if ($postData['type'] == 3){
                if (!$postData['video']){
                    return $this->ajaxError('请上传视频文件');
                }
            }elseif ($postData['type'] == 4){
                if (!$postData['music']){
                    return $this->ajaxError('请上传音频文件');
                }
            }
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
        $channelId = db('live')->where('c_id',$id)->value('channelId');
        if ($channelId){
            live::del_live($channelId);
        }
        $res = model('course_node')->save(['is_del'=>1], ['id'=>$id]);
        if ($res === false) {
            return $this->ajaxError('删除失败');
        } else {
            return $this->ajaxSuccess('删除成功');
        }
    }

    //通知
    public function notice()
    {
        set_time_limit(0);
        $id = input('post.id/d');
        $res = model('course_node')->where('id',$id)->find();
        if ($res['type'] != 1){
            return $this->ajaxError('不是直播课不能提醒');
        }
        if ($res['broadcast_time']>time()){
            $first = '你报名的课程'.date('Y-m-d H:i',$res['broadcast_time']).'开始上课，请准时参加';
            $uirl = "https://pgt.cooov.com/pages/detail/detail?id=".$res['course_id'];
        }else{
            $first = '你报名的课程已开始'.(int)((time()-$res['broadcast_time'])/60).'分钟';
            $uirl = "https://pgt.cooov.com/pages/live/live?id=".$res['id'];
        }
        $remark = '点击查看课程详情';
        $keyword = db('course')->where('id',$res['course_id'])->value('title').'·'.$res['title'];
        $keyword1 = date('Y-m-d H:i',$res['broadcast_time']);
        $week = date("w",$res['broadcast_time']);
        $array = ["星期天","星期一","星期二","星期三","星期四","星期五","星期六"];
        $keyword2 = $keyword1 .' '.$array[$week];
        $appid = config('Wechat.appid');
        $appsecret = config('Wechat.appsecret');
        $model_id = config('Wechat.model');
        $wechat = new \app\common\Wechat($appid,$appsecret);
        //dump($AccessToken);
        $AccessToken = $wechat->getAccessToken();
        $user_id = db('user_course')->where('type',1)->where('course_id',$res['course_id'])->column('user_id');
        $user = db('user')->whereIn('id',$user_id)->where('status',0)->select();
        $sms_template_code = config('ALICLOUD.TemplateNotice');
        foreach ($user as $v){
            $state = 0;
            $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$AccessToken&openid=".$v['openid']."&lang=zh_CN";
            $res = json_decode(HttpCurl::curlRequest($url));
            if ($res->subscribe){//已关注
                //通过微信公众号发送上课提醒
                $res = $this->Send_notice($model_id,$v['openid'],$uirl,$first,$keyword,$keyword2,$remark);
                if ($res['errcode']==0){
                    $state = 1;
                }
                $type = 1;
            }else{//未关注
                if ($v['mobile']){
                    //通过短信提醒用户上课
                    $send = send_sms_notice($v['mobile'], json_encode(['title'=>$keyword,'time'=>$keyword1]),$sms_template_code);
                    list($status,$return_code,$sub_code) = $send;

                    if($status){
                        $state = 1;
                    }
                }
                $type = 2;
            }
            $notice = [
                'title'   => $keyword,
                'type'   => $type,
                'c_id'    => $id,
                'notice'    => 2,
                'uid'     => $v['id'],
                'mobile'  => $v['mobile'],
                'state'   => $state,
                'time'    => time()
            ];
            db('send_notice')->insert($notice);
        }
        return $this->ajaxSuccess('发送成功！');
        //$this->Send_notice($model_id,)
    }
    //发送通知
    public function Send_notice($model_id,$openid,$url,$first,$keyword1,$keyword2,$remark)
    {
        $appid = config('wechat_app_pay.appid');
        $appsecret = config('wechat_app_pay.appsecret');
        $wechat = new \app\common\Wechat($appid,$appsecret);
        $AccessToken = $wechat->getAccessToken();
        $data = [
            'touser'=>$openid,
            'template_id'=>$model_id,
            'url'=>$url,
            'data'=>[
                'first'=>[
                    "value"=>$first,
                    "color"=>"#173177"
                ],
                'keyword1'=>[
                    "value"=>"$keyword1",
                    "color"=>"#173177"
                ],
                'keyword2'=>[
                    "value"=>$keyword2,
                    "color"=>"#173177"
                ],
                'remark'=>[
                    "value"=>"$remark",
                    "color"=>"#173177"
                ],

            ]
        ];
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$AccessToken";
        $res =  HttpCurl::curlRequest($url,$data,'POST','json');
        return json_decode($res,true);
    }
}