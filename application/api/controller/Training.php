<?php


namespace app\api\controller;


use app\common\Common;
use app\common\Wechat;
use think\Config;
use Wechat\WXBizDataCrypt;

class Training extends UserBase
{
    public function details(){
        $id = input('post.id');
        if (!$id){
            return ajaxError('参数错误');
        }

        $user_id = $this->uid;
        db('course_node')->where('id',$id)->find();
    }

    public function playMusic(){
        $user_id = $this->uid;
        $music_id = input('post.music_id/d');
        $album_id = input('post.album_id/d');
        if (empty($music_id) || empty($album_id)){
            return ajaxError('参数错误');
        }

        $data = [];
        $data['album_id'] = $album_id;
        $data['music_id'] = $music_id;
        $data['user_id'] = $user_id;
        $data['add_time'] = time();
        $res = model('PlayRecord')->insert($data);

        if ($res){
            return ajaxSuccess('操作成功');
        }
        return ajaxError('操作失败');
    }

    /*
     * 通过授权信息判断是否登录
     */
    public function getUserByAuth()
    {
        $iv = input('post.iv', '', 'trim');
        $rawData = input('post.rawData', '', 'trim');
        $encryptedData = input('post.encryptedData', '', 'trim');

        $user = model('User')->where(['id'=>$this->uid])->find();
        if(!empty($user['mobile'])){
            return ajaxError('用户已绑定手机号');
        }

        if(!empty($user['unionid']))
        {
            //return ajaxError('',-902);
        }

        if (!$iv|| !$rawData || !$encryptedData) {
            return ajaxError("参数错误");
        }
        $session_key = cache($user['mnp_openid']);
        if (!$session_key) {
            return ajaxError("session_key不能为空");
        }
        $signature2 = sha1($rawData . $session_key);

        if ($signature2 != input('post.signature/s')) {
            return ajaxError("签名非法");
        }

        Vendor("Wechat.wxBizDataCrypt");

        $pc = new WXBizDataCrypt(config('wechat_routine_pay.appid'), $session_key);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);
        //其中$data包含用户的所有数据
        if ($errCode != 0) {
            return ajaxError("错误代码:".$errCode);
        }
        $data = json_decode($data, true);
        $unionid = $data['unionId'];
        $nickname = $data['nickName'];
        $headimg = $data['avatarUrl'];

        //通过unionid查询是否存在用户
        $wxuser = model('User')->get(['unionid'=>$unionid]);
        if(empty($wxuser))
        {
            //不存在，则将用户信息更新至用户表
            model('User')->update(['nickname'=>$nickname, 'headimg'=>$headimg, 'unionid'=>$unionid],['id'=>$this->uid]);
            return ajaxSuccess('登录成功',1,['uid'=>$this->uid]);
        }else{
            if ($this->uid != $wxuser['id']){
                //存在
                //取消当前用户
                model('User')->update(['mnp_openid'=>'','status'=>-1],['id'=>$this->uid]);

                //合并到老用户
                model('User')->update(['mnp_openid'=>$user['mnp_openid']],['id'=>$wxuser['id']]);
            }
            $user_id =  $wxuser['id'];

            $login = new Login();
            $login->updateLogin($user_id);

            return ajaxSuccess('登录成功',1,['uid'=>$user_id]);
        }
    }

    /**
     *绑定手机号
     */
    public function bindMobile()
    {
        $mobile = input('post.mobile/s');
        $vercode = input('post.vercode/s');

        if(!is_mobile($mobile)){
            return ajaxError('请正确填写手机号码');
        }
        if(!is_vercode($vercode,4)){
            return ajaxError('验证码必须为4位');
        }

        $login = new Login();
        //判断是否存在验证码
        if(!$login->checkVf($mobile,$vercode)){
            return ajaxError('验证码错误');
        }

        $user = model('User')->where(['id'=>$this->uid])->find();
        if(!empty($user['mobile'])){
            return ajaxError('用户已绑定手机号');
        }

        $mobileUser = model('User')->where(['mobile'=>$mobile])->find();
        if (empty($mobileUser)){
            $user_info = [];
            $user_info['mobile'] = $mobile;
            $user_info['user_lv'] = 1;

            //注册赠送会员体验
            $user_info['user_lv'] = 2;
            $user_info['vip_begtime'] = time();
            $user_info['vip_endtime'] = time() + 7*24*3600;

            model('User')->update($user_info,['id'=>$user['id']]);
            $user_id = $user['id'];
        }else{
            if (!empty($mobileUser['mnp_openid'])){
                return ajaxError('该手机已绑定微信用户');
            }
            if (!empty($mobileUser['unionid']) && $mobileUser['unionid'] != $user['unionid']){
                return ajaxError('该手机已绑定其他微信用户');
            }

            //取消当前用户
            model('User')->update(['mnp_openid'=>'','status'=>-1],['id'=>$mobileUser['id']]);

            //合并到老用户
            model('User')->update(['mnp_openid'=>$user['mnp_openid'],'unionid'=>$user['unionid']],['id'=>$mobileUser['id']]);
            $user_id =  $mobileUser['id'];
        }

        $login->updateLogin($user_id);

        return ajaxSuccess('登录成功',1,['uid'=>$user_id]);
    }

    public function payLog(){
        $user_id = $this->uid;
        $list = model('Payment')->where(['user_id'=>$user_id,'status'=>1])->order('pay_time desc')->field('id,money,type,payment_id,pay_time')->select();
        foreach ($list as &$item){
            $item['pay_time'] = date('Y-m-d H:i:s',$item['pay_time']);
        }

        return ajaxSuccess('',1,$list);
    }
}