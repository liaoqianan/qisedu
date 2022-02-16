<?php
namespace app\api\controller;

use app\api\model\Debug;
use app\common\HttpCurl;
use app\common\Wechat;
use think\Cache;
use think\Db;

class Course extends UserBase
{
    //课节详情
    public function course_nodeDetails()
    {
        try{
            $id = input('post.id/d');
            if (empty($id)){
                return ajaxError('参数错误');
            }
            $where['is_del'] = 0;
            //详情
            $course = db('course_node')->where($where)->where('id',$id)->find();
            if (!$course){
                return ajaxError(-2,'课节不存在');
            }
            if ($course['is_gratis']){//收费
                $res = db('user_course')->where($where)->where('user_id',$this->uid)->where('type',1)->where('course_id',$course['course_id'])->find();
                if (!$res){
                    return ajaxError('您未购买该课程');
                }
            }
            switch ($course['type']) {
                /*直播*/
                case 1://用户加入直播口令
                    //$course['code'] = db('config_webcast')->where('sdk_id',$course['parameter'])->value('attendeeToken');
                    $course['code'] = $course['parameter'];
                    $course['timestamp'] = time()*1000;
                    $course['AppID'] = 'fu8k6enpot';
                    $data = [
                        'appId' => $course['AppID'],
                        'timestamp' => $course['timestamp'],
                        'channelId' => $course['code'],
                    ];
                    $course['sign'] = $this->getSign($data,config('Live.AppSecret'));
                    //$url ="http://api.polyv.net/live/v3/channel/basic/get?appId=".$course['AppID']."&timestamp=".$course['timestamp']."&channelId=".$course['code']."&sign=".$course['sign'];

                    break;
                /* 回放 */
                case 2:
                    /*$page = 1;
                    $pageSize = 20;
                    $params = array(
                        'appId' => config('Live.AppID'),
                        'timestamp' => time()*1000,
                        'page' => $page,
                        'pageSize' => $pageSize
                    );
                    $params['sign'] = $this->getSign($params,config('Live.AppSecret'));
                    $channelId = $course['parameter'];*/
                    //$url = "http://api.polyv.net/live/v2/channel/recordFile/$channelId/playback/list?appId=".$params['appId']."&page=$page&pageSize=$pageSize&timestamp=".$params['timestamp']."&sign=".$params['sign'];
                    //dump(json_decode(HttpCurl::curlRequest($url),true));
                    break;
                /* 视频 */
                case 3:
                    $Video = new Video();
                    $value = $Video->index($course['parameter']);
                    foreach ($value->PlayInfoList->PlayInfo as &$v){
                        if ($v->Definition == 'OD'){
                            $course['parameter'] = explode('?',$v->PlayURL)[0];
                        }
                    }
                    break;
                /* 音频 */
                case 4:
                    $Video = new Video();
                    $value = $Video->index($course['parameter']);
                    foreach ($value->PlayInfoList->PlayInfo as &$v){
                        if ($v->Definition == 'OD'){
                            $course['parameter'] = explode('?',$v->PlayURL)[0];
                        }
                    }
                    break;
                default:
                    break;
            }
            $course['broadcast_time'] = date('Y-m-d H:i:s',$course['broadcast_time']);
            $course['create_time'] = date('Y-m-d H:i:s',$course['create_time']);
            //添加访问次数
            db('Course_node')->where('id',$id)->setInc('hits');
            $user_course_log = db('user_course_log')->where('user_id',$this->uid)->where('course_id',$course['course_id'])->where('course_node_id',$id)->find();
            if ($user_course_log){
                db('user_course_log')->where('id',$user_course_log['id'])->update(['time'=>time()]);
            }else{
                db('user_course_log')->insert([
                    'user_id'        => $this->uid,
                    'course_id'      => $course['course_id'],
                    'course_node_id' => $id,
                    'time'           => time(),
                    'course_time'    => 1
                ]);
            }
            $course_node = db('Course_node')->where($where)->where('course_id',$course['course_id'])->order('order')->field('id,Live_state,live_url,title,broadcast_time,create_time,hits,type')->select();
            foreach ($course_node as &$value) {
                if ($value['type'] == 1) {
                    /*if ($value['broadcast_time']<time()){
                        $value['type_name'] = '正在直播';
                    }else{
                        $value['type'] = 0;
                        $value['type_name'] = '等待直播';
                    }*/
                    if ($value['Live_state'] == 1){
                        $value['type_name'] = '正在直播';
                    }else{
                        $value['type_name'] = '等待直播';
                    }
                }
                if ($value['type'] == 2) {
                    if ($value['Live_state'] == 1){
                        $value['type_name'] = '直播回放';
                    }else{
                        $value['type_name'] = '回放录制中';
                    }
                }
                if ($value['type'] == 3) {
                    $value['type_name'] = '视频';
                }
                if ($value['type'] == 4) {
                    $value['type_name'] = '音频';
                }
                if ($value['type'] == 5) {
                    $value['type_name'] = '图文';
                }
                $value['broadcast_time'] = date('Y-m-d H:i:s',$value['broadcast_time']);
                $value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
            }
            $data = [
                'course'       => $course,
                'course_node'  => $course_node,
            ];
            return ajaxSuccess('',1,$data);
        }catch (\Exception $e){
            return ajaxError($e->getMessage());
        }
    }

    //用户播放历史
    public function user_course_log()
    {
        if(!input('post.course_id') && !input('post.course_node_id') && !input('post.percentage') && !input('post.course_time')){
            return ajaxError('参数有误！');
        }
        $res = db('user_course_log')
            ->where('user_id',$this->uid)
            ->where('course_id',input('post.course_id'))
            ->where('course_node_id',input('post.course_node_id'))
            ->find();
        if ($res){
            db('user_course_log')->where('id',$res['id'])->update([
                'percentage'   => input('post.percentage'),
                'course_time'  => $res['course_time']+input('post.course_time')
            ]);
        }else{
            db('user_course_log')->insert([
                'user_id'        => $this->uid,
                'course_id'      => input('post.course_id'),
                'course_node_id' => input('post.course_node_id'),
                'time'           => time(),
                'percentage'     => input('post.percentage'),
                'course_time'    => input('post.course_time')
            ]);
        }
    }
}
