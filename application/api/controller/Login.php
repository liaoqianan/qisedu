<?php

namespace app\api\controller;

use app\common\Apple;
use app\common\Wechat;
use think\Cache;
use think\Controller;
use think\Db;
use Wechat\WXBizDataCrypt;

class Login extends Base
{
    public function view(){
        $type = input('get.type/s');

        $data = model('Config')->where('type',$type)->find();
        if(empty($data)){
            return $this->error('参数错误');
        }

        return view('Config/index',['data'=>$data]);
    }
    /**
     * 发送验证码
     */
    public function SendSms() {
        if ($this->request->isPost()) {
            $mobile = input('post.mobile', '', 'trim');

            $code = rand(1000, 9999);

            if (empty($mobile)) {
                return ajaxError("手机号不能为空");
            }
            if(!preg_match("/^1[3-9]{1}\d{9}$/",$mobile)){
                return ajaxError("请正确填写手机号码");
            }

            //判断是否存在验证码
            $data = model("VerifySms")->where(array('mobile'=>$mobile, 'status'=>1))->order('id DESC')->find();
            //获取时间配置
            $alicloud = config('ALICLOUD');
            $sms_time_out = $alicloud['sms_time_out'];
            $sms_time_out = $sms_time_out ? $sms_time_out : 60;
            //60秒以内不可重复发送
            $times = intval(time()) - intval($data['addtime']);
            if($data && ($times < $sms_time_out)){
                return ajaxError($sms_time_out.'秒内不允许重复发送');
            }

            $sms_template_code = $alicloud['TemplateCode'];
            $send = send_sms_reg($mobile, $code,$sms_template_code);
            list($status,$return_code,$sub_code) = $send;

            if($status){
                return ajaxSuccess('发送成功');
            }else if(!$status && $sub_code=='isv.BUSINESS_LIMIT_CONTROL'){
                model("VerifySms")->insert(['mobile'=>$mobile,'code'=>$code,'status'=>2,'addtime'=>time(),'memo'=>'发送失败,'.$sub_code]);
                return ajaxError('您今天发送验证码的数量已超过限额！');
            }else{
                model("VerifySms")->insert(['mobile'=>$mobile,'code'=>$code,'status'=>2,'addtime'=>time(),'memo'=>'发送失败,'.$sub_code]);
                return ajaxError('发送失败,'.$sub_code);
            }
        }
    }
    /**
     * 发送验证码
     */
    public function binSendSms() {
        if ($this->request->isPost()) {
            $mobile = input('post.mobile', '', 'trim');

            $code = rand(1000, 9999);

            if (empty($mobile)) {
                return ajaxError("手机号不能为空");
            }
            if(!preg_match("/^1[3-9]{1}\d{9}$/",$mobile)){
                return ajaxError("请正确填写手机号码");
            }


            //判断手机号码是否注册过
            $user = model("user")->where(array('mobile'=>$mobile, 'status'=>0))->find();
            if ($user) {
                return ajaxError("该手机号码已注册");
            }
            //判断是否存在验证码
            $data = model("VerifySms")->where(array('mobile'=>$mobile, 'status'=>1))->order('id DESC')->find();
            //获取时间配置
            $alicloud = config('ALICLOUD');
            $sms_time_out = $alicloud['sms_time_out'];
            $sms_time_out = $sms_time_out ? $sms_time_out : 60;
            //60秒以内不可重复发送
            $times = intval(time()) - intval($data['addtime']);
            if($data && ($times < $sms_time_out)){
                return ajaxError($sms_time_out.'秒内不允许重复发送');
            }

            $sms_template_code = $alicloud['TemplateCode'];
            $send = send_sms_reg($mobile, $code,$sms_template_code);
            list($status,$return_code,$sub_code) = $send;

            if($status){
                return ajaxSuccess('发送成功');
            }else if(!$status && $sub_code=='isv.BUSINESS_LIMIT_CONTROL'){
                model("VerifySms")->insert(['mobile'=>$mobile,'code'=>$code,'status'=>2,'addtime'=>time(),'memo'=>'发送失败,'.$sub_code]);
                return ajaxError('您今天发送验证码的数量已超过限额！');
            }else{
                model("VerifySms")->insert(['mobile'=>$mobile,'code'=>$code,'status'=>2,'addtime'=>time(),'memo'=>'发送失败,'.$sub_code]);
                return ajaxError('发送失败,'.$sub_code);
            }
        }
    }
    /**
     * 手机号验证码登录
     */
    public function smsLogin(){
        $mobile = input('post.mobile/s');             //获取手机号
        $code = input('post.vercode/s');           //获取输入密码

        if(!is_mobile($mobile)){
            return ajaxError('请正确填写手机号码');
        }
        if(!is_vercode($code,4)){
            return ajaxError('验证码必须为4位');
        }


        Db::startTrans();
        try{
            //判断是否存在验证码
            if(!$this->checkVf($mobile,$code)){
                return ajaxError('验证码错误');
            }

            $info = model('User') ->where(array('mobile'=>$mobile))->find();

            if (empty($info)){
                $data = [];
                $data['mobile'] = $mobile;

                $user_id = $this->register($data);
            }else{
                if ($info['status'] != 0){
                    return ajaxError('账号已被禁用');
                }
                $user_id = $info['id'];
            }

            Db::commit();
            return ajaxSuccess('登录成功',1,['uid'=>$user_id]);
        }catch (\Exception $e){
            Db::rollback();
            return ajaxError("操作失败".$e->getMessage());
        }
    }

    /**
     * 获取openid
     */
    public function wxLogin(){
        $type = input('post.type/s','wx');

        if ($type == 'wx'){
            $code = input('post.code/s');
            $access_token = input('post.access_token/s');
            $refer_id = input('post.refer_id/d');

            if (empty($code)){
                return ajaxError('参数错误');
            }
            $user_id = 0;
            if ($this->platform == 'routine'){ //小程序微信登陆
                $wechat = new Wechat();
                $res = $wechat -> getOpendId($code);
                if(!empty($res['errcode'])){
                    return ajaxError($res['errmsg']);
                }
                $openid = $res['openid'];
                cache($res['openid'], $res['session_key']);

                //判断该openid是否存在，存在则返回用户，不存在则插入（游客）
                $user_info = model('User')->where(['mnp_openid'=>$openid])->find();
                if(!empty($user_info)){
                    $user_id = $user_info['id'];
                    if ($user_info['refer_id'] == 0){
                        model('User')->where(['id'=>$user_id])->update(['refer_id'=>$refer_id]);
                    }
                }else{
                    $data = [];
                    $data['mnp_openid'] = $openid;
                    $data['refer_id'] = $refer_id;
                    $data['nickname'] = '游客';
                    $data['add_time'] = time();
                    $data['add_ip'] = get_client_ip();
                    model('User')->insert($data);
                    $user_id = model('User')->getLastInsID();
                }
                //$user_id = 6;

            }elseif ($this->platform == 'mobile'){          //H5登录
                $appid = config('wechat_app_pay.appid');
                $appsecret = config('wechat_app_pay.appsecret');

                $wechat = new Wechat($appid,$appsecret);
                $res = json_decode($wechat -> getAccessTokenByCode($code),true);

                if(!empty($res['errcode'])){
                    return ajaxError($res['errmsg']);
                }
                $openid = $res['openid'];
                $wxuser = model('User')->where(['openid'=>$openid])->find();
                if (!empty($wxuser)){
                    $user_id = $wxuser['id'];
                }else{
                    $data = [];
                    $data['openid'] = $openid;
                    $data['refer_id'] = $refer_id;
                    $data['nickname'] = '游客';
                    $data['add_time'] = time();
                    $data['add_ip'] = get_client_ip();
                    model('User')->insert($data);
                    $user_id = model('User')->getLastInsID();
                }
            }
        }elseif($type == 'apple'){
            $openid = input('post.code', '');
            $verifyToken = input('post.access_token', '');

            if(empty($openid) || empty($verifyToken)){
                return ajaxError('参数错误');
            }

            //token校验
            $apple = new Apple();
            $verifyRes = $apple->apple_jwt_verify($verifyToken);
            if(isset($verifyRes['jwtStatus']) && $verifyRes['jwtStatus'] == 'failed'){
                return ajaxError( $verifyRes['jwtMsg']);
            }

            $user_id = 0;
            //判断该openid是否存在，存在则返回用户，不存在则插入（游客）
            $user_info = model('User')->where(['apple_id'=>$openid])->find();
            if(!empty($user_info)){
                $user_id = $user_info['id'];
                $this->updateLogin($user_id);
            }
        }
        $this->updateLogin($user_id);

        //db('get_code')->insert(['code'=>'rerefer_id'.input('post.refer_id/d'),'time'=>date("Y-m-d H:i:s",time())]);
        return ajaxSuccess('ok',1,['uid'=>$user_id]);
    }

    /**
     *绑定手机号
     */
    public function bindMobile()
    {
        $type = input('post.type/s','wx');
        $mobile = input('post.mobile/s');             //获取手机号
        $vercode = input('post.vercode/s');           //获取输入密码

        if(!is_mobile($mobile)){
            return ajaxError('请正确填写手机号码');
        }
        if(!is_vercode($vercode,4)){
            return ajaxError('验证码必须为4位');
        }

        if ($type == 'wx'){
            $code = input('post.code/s');
            $access_token = input('post.access_token/s');

            if (empty($code)||empty($access_token)){
                return ajaxError('参数错误');
            }

            $appid = config('wechat_app_pay.appid');
            $appsecret = config('wechat_app_pay.appsecret');
            $wechat = new Wechat($appid,$appsecret);
//            $wx_info = $wechat->getAccessTokenByCode($code);
//            $wx_info = json_decode($wx_info,true);
//            $access_token = $wx_info['access_token'];
            $openid  = $code;

            $userInfo = $wechat->getUserInfo($access_token, $openid);
            $userInfo = json_decode($userInfo,true);

            $nickname = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $userInfo['nickname']);
            $headimg  = $userInfo['headimgurl'];
            $unionid  = $userInfo['unionid'];

            $wxuser = model('User')->where(['app_openid'=>$openid])->find();
            if(empty($wxuser)){
                //判断是否存在验证码
                if(!$this->checkVf($mobile,$vercode)){
                    return ajaxError('验证码错误');
                }

                $mobileUser = model('User')->where(['mobile'=>$mobile])->find();
                if (empty($mobileUser)){    //手机号用户不存在
                    $user_info = [];
                    $user_info['mobile'] = $mobile;
                    $user_info['app_openid'] = $openid;
                    $user_info['nickname'] = $nickname;
                    $user_info['headimg'] = $headimg;

                    $wxuser = model('User')->where(['unionid'=>$unionid])->find();
                    if (empty($wxuser)){  //未绑定小程序
                        $user_info['unionid'] = $unionid;

                        $user_id = $this->register($user_info);
                    }else{                  //已绑定小程序
                        $user_id = $wxuser['id'];
                        if(!empty($wxuser['mobile']))
                        {
                            return ajaxError('该微信已被其他手机绑定');
                        }else{
                            model('User')->update(['app_openid'=>$openid, 'mobile'=>$mobile],['id'=>$user_id]);
                        }
                    }
                }else{     //手机号用户已存在
                    if (!empty($mobileUser['app_openid'])){
                        return ajaxError('该手机已绑定其他微信用户');
                    }
                    if (!empty($mobileUser['unionid']) && $mobileUser['unionid'] != $unionid){
                        return ajaxError('该手机已绑定其他微信用户');
                    }

                    model('User')->update(['app_openid'=>$openid,'unionid'=>$unionid],['id'=>$mobileUser['id']]);
                    $user_id =  $mobileUser['id'];
                }
            }else{
                $user_id =  $wxuser['id'];
            }

        }elseif($type == 'apple'){
            $openid = input('post.code', '');
            $verifyToken = input('access_token', '');

            if(empty($openid) || empty($verifyToken)){
                return ajaxError('参数错误');
            }

            //token校验
            $apple = new Apple();
            $verifyRes = $apple->apple_jwt_verify($verifyToken);
            if(isset($verifyRes['jwtStatus']) && $verifyRes['jwtStatus'] == 'failed'){
                return ajaxError( $verifyRes['jwtMsg']);
            }

            $apple_user = model('User')->where(['apple_id'=>$openid])->find();
            if(empty($apple_user)){
                //判断是否存在验证码
                if(!$this->checkVf($mobile,$vercode)){
                    return ajaxError('验证码错误');
                }

                $mobileUser = model('User')->where(['mobile'=>$mobile])->find();
                if (empty($mobileUser)){    //手机号用户不存在
                    $user_info = [];
                    $user_info['mobile'] = $mobile;
                    $user_info['apple_id'] = $openid;
                    $user_id = $this->register($user_info);

                }else{     //手机号用户已存在
                    if (!empty($mobileUser['apple_id'])){
                        return ajaxError('该手机已绑定其他apple id');
                    }

                    model('User')->update(['apple_id'=>$openid],['id'=>$mobileUser['id']]);
                    $user_id =  $mobileUser['id'];
                }
            }else{
                $user_id = $apple_user['id'];
            }
        }
        $this->updateLogin($user_id);

        return ajaxSuccess('登录成功',1,['uid'=>$user_id]);
    }

    public function appleLogin(){
        $openid = input('post.userID', '');
        $verifyToken = input('identityToken', '');

        if(empty($openid) || empty($verifyToken)){
            return ajaxError('参数错误');
        }

        //token校验
        $apple = new Apple();
        $verifyRes = $apple->apple_jwt_verify($verifyToken);
        if(isset($verifyRes['jwtStatus']) && $verifyRes['jwtStatus'] == 'failed'){
            return ajaxError( $verifyRes['jwtMsg']);
        }

        $user_id = 0;
        //判断该openid是否存在，存在则返回用户，不存在则插入（游客）
        $user_info = model('User')->where(['apple_id'=>$openid])->find();
        if(!empty($user_info)){
            $user_id = $user_info['id'];
            $this->updateLogin($user_id);
        }

        return ajaxSuccess('ok',1,['uid'=>$user_id]);
    }


    /**
     * 验证--验证码
     */
    public function checkVf($mobile,$vf){
        $option = [
            "mobile"  => $mobile,
            "status"  => 1,
            "addtime" => ['egt',time()-2*3600]
        ];
        $re = model('VerifySms')->where($option)->order('id DESC')->find();

        if ($vf == $re['code']){
            //验证成功后修改验证码状态
            model('VerifySms')->save(["status"=>3,"upttime"=>time()],["id"=>$re['id']]);
            return true;
        }
        return false;
    }

    public function register($data){
        $data['user_lv'] = 1;
        $data['add_time'] = time();
        $data['add_ip'] = get_client_ip();

        //注册赠送会员体验
        $data['user_lv'] = 2;
        $data['vip_begtime'] = time();
        $data['vip_endtime'] = time() + 7*24*3600;

        model('User')->insert($data);
        $user_id = model('User')->getLastInsID();

        return $user_id;
    }

    public function updateLogin($user_id){
        $data['last_time'] = time();
        $data['last_ip'] = get_client_ip();

        model('User')->update($data,['id'=>$user_id]);

        return true;
    }
}