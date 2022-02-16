<?php
namespace app\api\controller;


use app\api\model\Debug;
use app\common\Wechat;
use think\Cache;
use think\Db;
use app\common\WechatAppPay;

class Index extends Base {
    public function index(){
        $where['is_del'] = 0;
        //banner图
        /*$banner = model('banner')->where('is_del',0)->order('order desc')->field('id,pic,url')->select();
        echo "<pre>";
        //栏目
        $column = model('column')->where($where)->field('id,title,brief')->select();
        //栏目下课程
        foreach ($column as &$value){
            $course = db('course')->where($where)->where('column_id',$value['id'])->order('order desc')->field('id,title,pic,brief,create_time,original_price,present_price,type,broadcast_time')->select();
            foreach ($course as &$v){
                $v['broadcast_time'] =  date('Y-m-d H:i',$v['broadcast_time']);
            }
            $value['course'] = $course;
        }
        //文章
        $article = model('article')->where($where)->order('order desc')->field('id,title,pic,brief,create_time,comment,like')->limit(0,2)->select();
        //简介
        $about = db('config')->where('type','about_us')->value('content');
        $about = $this->get_content_img($about);
        $data = [
            'banner'  => $banner,
            'column'  => $column,
            'article' => $article,
            'about'   => $about
        ];*/
        $column = model('column')->where($where)->field('id,title,brief,pic')->select();
        $list = model('article')->where($where)->order('order desc')->field('id,title,pic,brief,create_time,comment,like')->limit(0,6)->select();
        $banner =model('banner')->where($where)->order('order desc')->field('id,pic,url')->select();
        $teacher = db('teacher_assistant')->where($where)->field('id,name,qr_code,hp_pic,column_id')->limit(0,5)->select();
        $data = [
            'column' =>$column,
            'list'   =>$list,
            'banner'=>$banner,
            'teacher'=>$teacher,

        ];
        return ajaxSuccess('',1,['data'=>$data]);
    }

    public function courseDetails(){
        $id = input('post.id/d');
        if (empty($id)){
            return ajaxError('参数错误');
        }
        $where['is_del'] = 0;
        //详情
        $course = model('course')->where($where)->where('id',$id)->find();
        if (!$course){
            return ajaxError(-2,'课程不存在');
        }
        db('course')->where('id',$id)->setInc('hits');
        $user = new UserBase();
        $course_node = db('Course_node')->where($where)->where('course_id',$course['id'])->order('order')->field('id,Live_state,title,broadcast_time,create_time,hits,type')->select();
        foreach ($course_node as &$value){
           if ($value['type'] == 1){
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
           if ($value['type'] == 2){
               //$value['type_name'] = '直播回放';
               if ($value['Live_state'] == 1){
                   $value['type_name'] = '直播回放';
               }else{
                   $value['type_name'] = '回放录制中';
               }
           }
           if ($value['type'] == 3){
               $value['type_name'] = '视频';
           }
           if ($value['type'] == 4){
               $value['type_name'] = '音频';
           }
           if ($value['type'] == 5){
               $value['type_name'] = '图文';
           }
           $value['broadcast_time'] = date('Y-m-d H:i',$value['broadcast_time']);
           $value['create_time'] = date('Y-m-d H:i',$value['create_time']);
           if ($user->uid){
               $value['percentage'] = db('user_course_log')->where('user_id',$user->uid)->where('course_node_id',$value['id'])->value('percentage');
               if(!$value['percentage']) $value['percentage'] = '0%';
           }
        }
        $state = 0;
        if ($user->uid){
            $res = db('user_course')->where('user_id',$user->uid)->where('course_id',$id)->where('type',1)->where($where)->find();
            if($res){
                $state = 1;
            }
        }
        $course['share_pic'] = share_pic($course['pic']);
        $data = [
            'course' => $course,
            'state' => $state,
            'course_node' => $course_node
        ];
        return ajaxSuccess('',1,['data'=>$data]);
    }
    public function activityDetails(){
        $id = input('post.id/d');
        if (empty($id)){
            return ajaxError('参数错误');
        }
        $where['is_del'] = 0;
        //详情
        $activity = model('activity')->where($where)->where('id',$id)->find();
        if (!$activity){
            return ajaxError(-2,'课程不存在');
        }
        $activity['time'] = date('Y-m-d H:i',$activity['time']);
        db('activity')->where('id',$id)->setInc('hits');
        return ajaxSuccess('',1,['data'=>$activity]);
    }
    //获取富文本第一张图片
    public function get_content_img($content){
        $pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/";
        preg_match_all($pattern,$content,$matchContent);
        if(isset($matchContent[1][0])){
            return $matchContent[1][0];
        }
    }
    //关于我们
    public function about_us()
    {
        $about = db('config')->where('type','about_us')->value('content');
        if (!$about){
            return ajaxError('暂未上传');
        }
        return ajaxSuccess('',1,['data'=>$about]);
    }
    //获取分享参数
    public function share()
    {
        $url = html_entity_decode(input('post.url'));
        if (!$url){
            return ajaxError('参数有误');
        }
        $appid = config('wechat_app_pay.appid'); // 应用id
        $appsecret = config('wechat_app_pay.appsecret'); // 应用id
        $Wechat = new Wechat($appid,$appsecret);
        $result = $Wechat->getSignPackage($url);

        return ajaxSuccess('',1,$result);
    }

    //获取题目
    public function question()
    {
        $data['Mandatory'] = db('question')->where('q_type',1)->where('is_del',0)->order('number')->select();
        $data['Multiple'] = db('question')->where('q_type',2)->where('is_del',0)->order('number')->select();
        $data['Challenge'] = db('question')->where('q_type',3)->where('is_del',0)->order('number')->select();
        return ajaxSuccess('',1,$data);
    }
}