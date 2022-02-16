<?php


namespace app\api\controller;


use think\Controller;
use app\common\HttpCurl;

class Notice extends Controller
{

    //直播状态回调
    public function Live_call_back()
    {
        /*$Wechat = new \app\common\Wechat(config('wechat_app_pay.appid'), config('wechat_app_pay.appsecret'));
        dump($Wechat->getAccessToken());exit;*/
        try {
            if (input('status') == 'live') {
                db('course_node')->where('parameter', input('channelId'))->update(['Live_state'=>1,'type' => 1]);
            } else {
                db('course_node')->where('parameter', input('channelId'))->update(['type' => 2,'Live_state'=>0]);
            }
            //db('get_code')->insert(['code'=>json_encode(input()),'time'=>date('Y-m-d H:i:s',time())]);
        } catch (\Exception $e) {
            db('get_code')->insert(['code' => $e->getMessage(), 'time' => date('Y-m-d H:i:s', time())]);
        }
    }

    //录制视频回调
    public function playback()
    {
        try {
            db('get_code')->insert(['code' => json_encode(input()), 'time' => date('Y-m-d H:i:s', time())]);
            $fileId = db('course_node')->where('parameter', input('channelId'))->value('fileId');
            if ($fileId) {
                if (input('fileId')) {
                    $fileId = $fileId . ',' . input('fileId');
                }
            } else {
                $fileId = input('fileId');
            }
            db('course_node')->where('parameter', input('channelId'))->update(['fileId' => $fileId]);
            if (count(explode(',',$fileId)) == 1){
                db('get_code')->insert(['code' => '第一次获取成功'.$fileId, 'time' => date('Y-m-d H:i:s', time())]);
                $id = db('course_node')->where('parameter', input('channelId'))->where('is_del',0)->find();
                $model_id = config('Wechat.Recordmodel');

                $user_id = db('user_course')->where('type',1)->where('course_id',$id['course_id'])->column('user_id');
                $user = db('user')->whereIn('id',$user_id)->where('status',0)->select();
                $uirl = "https://pgt.cooov.com/pages/liveplayback/liveplayback?id=".$id['id'];
                $first = '您好，您购买的直播课程已生成回放';
                $keyword1 = config('APP_NAME');
                $keyword2 = db('course')->where('id',$id['course_id'])->value('title').'·'.$id['title'];
                $keyword3 = date('Y-m-d H:i',time());
                $sms_template_code = config('ALICLOUD.RecordTemplateCode');
                $remark = '详情点击';
                $this->Send_user(3,$id['id'],$user,$model_id,$uirl,$first,$keyword1,$keyword1,$keyword2,$remark,$sms_template_code,$keyword3);

                db('course_node')->where('parameter', input('channelId'))->update(['live_url' => input('fileUrl'),'Live_state'=>1]);
                return;
            }
            $params = array(
                'appId' => config('Live.AppID'),
                'timestamp' => time() * 1000,
                'channelId' => input('channelId'),
                'fileIds' => $fileId,
                'callbackUrl' => 'https://pgtapi.cooov.com/notice/callbackUrls?channelId=' . input('channelId'),
                'mergeMp4' => 'Y',
            );
            $params['sign'] = $this->getSign($params, config('Live.AppSecret'));
            $url = 'http://api.polyv.net/live/v3/channel/record/merge';
            $res = json_decode(HttpCurl::curlRequest($url, $params, 'POST'), true);

            db('get_code')->insert(['code' => $res['data'], 'time' => date('Y-m-d H:i:s', time())]);
        } catch (\Exception $e) {
            db('get_code')->insert(['code' => $e->getMessage(), 'time' => date('Y-m-d H:i:s', time())]);
        }
        //db('get_code')->insert(['code'=>json_encode(input()),'time'=>date('Y-m-d H:i:s',time())]);
    }

    //异步生成直播成功通知
    public function callbackUrls()
    {
        try {
            if (input('status') == 'success') {
               // db('course_node')->where('parameter', input('channelId'))->update(['live_url' => input('fileUrl'),'Live_state'=>1]);
                $id = db('course_node')->where('parameter', input('channelId'))->find();
                //dump($id);exit;
                $model_id = config('Wechat.Recordmodel');

                $user_id = db('user_course')->where('type',1)->where('course_id',$id['course_id'])->column('user_id');
                $user = db('user')->whereIn('id',$user_id)->where('status',0)->select();
                $uirl = "https://pgt.cooov.com/pages/liveplayback/liveplayback?id=".$id['id'];
                $first = '您好，您购买的直播课程已生成回放';
                $keyword1 = config('APP_NAME');
                $keyword2 = db('course')->where('id',$id['course_id'])->value('title').'·'.$id['title'];
                $keyword3 = date('Y-m-d H:i',time());
                $sms_template_code = config('ALICLOUD.RecordTemplateCode');
                $remark = '详情点击';
                $this->Send_user(3,$id['id'],$user,$model_id,$uirl,$first,$keyword1,$keyword1,$keyword2,$remark,$sms_template_code,$keyword3);
                db('get_code')->insert(['code' => '合并成功', 'time' => date('Y-m-d H:i:s', time())]);
            }else{
                db('get_code')->insert(['code' => '合并失败', 'time' => date('Y-m-d H:i:s', time())]);
            }
        } catch (\Exception $e) {
            db('get_code')->insert(['code' => $e->getMessage(), 'time' => date('Y-m-d H:i:s', time())]);
        }
    }

    //获取sign函数
    public function getSign($params, $appSecret)
    {
        // 1. 对加密数组进行字典排序
        foreach ($params as $key => $value) {
            $arr[$key] = $key;
        }
        sort($arr);
        $str = $appSecret;
        foreach ($arr as $k => $v) {
            $str = $str . $arr[$k] . $params[$v];
        }
        $restr = $str . $appSecret;
        $sign = strtoupper(md5($restr));
        return $sign;
    }
    //定时发送通知每十分钟访问一次
    public function crontab()
    {
        set_time_limit(0);
        //未开播的课节
        $course_node = db('course_node')->where('is_del',0)->where('type',1)->where('Live_state',0)->select();
        foreach($course_node as $value){
            //半小时内发送
            if ($value['broadcast_time']>time() && $value['broadcast_time']<time()+60*30){
                $send_notice = db('send_notice')->where('c_id',$value['id'])->where('notice',2)->find();
                if (!$send_notice){//发送过就不发了

                    $first = '你报名的课程'.date('Y-m-d H:i',$value['broadcast_time']).'开始';

                    $uirl = "https://pgt.cooov.com/pages/detail/detail?id=".$value['course_id'];
                    $remark = '点击进入直播间';
                    $keyword = db('course')->where('id',$value['course_id'])->value('title').'·'.$value['title'];
                    $keyword1 = date('Y-m-d H:i',$value['broadcast_time']);
                    $week = date("w",$value['broadcast_time']);
                    $array = ["星期天","星期一","星期二","星期三","星期四","星期五","星期六"];
                    $keyword2 = $keyword1 .' '.$array[$week];
                    $model_id = config('wechat_app_pay.model');
                    $user_id = db('user_course')->where('type',1)->where('course_id',$value['course_id'])->column('user_id');
                    $user = db('user')->whereIn('id',$user_id)->where('status',0)->select();

                    $sms_template_code = config('ALICLOUD.TemplateNotice');

                    $this->Send_user(2,$value['id'],$user,$model_id,$uirl,$first,$keyword,$keyword1,$keyword2,$remark,$sms_template_code);
                }
            }
        }
    }
    /*
     * $notic 1：支付；2：预约；3：录制
     *$c_id 课节id
     * $user array 购买课程用户记录
     * $model_id 模板信息id
     * $uirl 跳转链接
     * $first 模板标题
     * $keyword 模板一名称
     * $keyword1 模板二发送时间
     * $keyword2 模板二加周发送时间
     * $remark 结束标语
     * $sms_template_code 短信模板id
     * $keyword3 模板三文字
     * */
    public function Send_user($notic,$c_id,$user,$model_id,$uirl,$first,$keyword,$keyword1,$keyword2,$remark,$sms_template_code,$keyword3=null){
        $appid = config('Wechat.appid');
        $appsecret = config('Wechat.appsecret');
        $wechat = new \app\common\Wechat($appid,$appsecret);
        $AccessToken = $wechat->getAccessToken();
        foreach ($user as $v){
            $state = 0;
            $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$AccessToken&openid=".$v['openid']."&lang=zh_CN";
            $res = json_decode(HttpCurl::curlRequest($url));
            if ($res->subscribe){//已关注
                //通过微信公众号发送上课提醒
                $res = $this->Send_notices($AccessToken,$model_id,$v['openid'],$uirl,$first,$keyword,$keyword2,$remark,$keyword3);
                if ($res['errcode']==0){
                    $state = 1;
                }
                $type = 1;
            }else{//未关注
                if ($v['mobile']){
                    //通过短信提醒用户上课
                    if ($keyword3){
                        $send = send_sms_notice($v['mobile'], json_encode(['title'=>$keyword2]),$sms_template_code);
                    }else{
                        $send = send_sms_notice($v['mobile'], json_encode(['title'=>$keyword,'time'=>$keyword1]),$sms_template_code);
                    }

                    db('get_code')->insert(['code' => json_encode($send), 'time' => date('Y-m-d H:i:s', time())]);
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
                'c_id'    => $c_id,
                'notice'    => $notic,
                'uid'     => $v['id'],
                'mobile'  => $v['mobile'],
                'state'   => $state,
                'time'    => time()
            ];
            db('send_notice')->insert($notice);
        }
    }
    //发送通知
    public function Send_notices($AccessToken,$model_id,$openid,$url,$first,$keyword1,$keyword2,$remark,$keyword3=null)
    {
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
        if ($keyword3){
            $data['data']['keyword3']=["value"=>$keyword3,"color"=>"#173177"];
        }
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$AccessToken";
        $res =  HttpCurl::curlRequest($url,$data,'POST','json');
        return json_decode($res,true);
    }
}