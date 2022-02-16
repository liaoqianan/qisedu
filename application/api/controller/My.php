<?php


namespace app\api\controller;


use app\common\Common;
use app\common\Wechat;
use think\Cache;
use think\Config;
use Wechat\WXBizDataCrypt;

class My extends UserBase
{
    public function index()
    {
        $user_id = $this->uid;
        $userInfo = model('User')->where(['id' => $user_id])->field('nickname,mobile,headimg,money,point,user_lv,vip_begtime,vip_endtime')->find();

        $userInfo['headimg'] = Common::getHeadimg($userInfo['headimg']);
        $userInfo['nickname'] = $this->emojiDecode($userInfo['nickname']);
        return ajaxSuccess('', 1, $userInfo);
    }

    public function playMusic()
    {
        $user_id = $this->uid;
        $music_id = input('post.music_id/d');
        $album_id = input('post.album_id/d');
        if (empty($music_id) || empty($album_id)) {
            return ajaxError('参数错误');
        }

        $data = [];
        $data['album_id'] = $album_id;
        $data['music_id'] = $music_id;
        $data['user_id'] = $user_id;
        $data['add_time'] = time();
        $res = model('PlayRecord')->insert($data);

        if ($res) {
            return ajaxSuccess('操作成功');
        }
        return ajaxError('操作失败');
    }

    /*
     * 通过授权信息判断是否登录
     */
    public function getUserByAuth()
    {
        //if(1){
        try {
            if ($this->platform == 'mobile') {
                $code = input('post.code');
                if (!$code) {
                    return ajaxError('参数错误');
                }
                $appid = config('wechat_app_pay.appid');
                $appsecret = config('wechat_app_pay.appsecret');
                $wechat = new Wechat($appid, $appsecret);
                $res = $wechat->getAccessTokenByCode($code);
                $res = json_decode($res, true);
                if (!empty($res['errcode'])) {
                    return ajaxError($res['errmsg']);
                }
                $access_token = $res['access_token'];
                $unionid = $res['unionid'];
                $openid = $res['openid'];
                $info = $wechat->getUserInfo($access_token, $openid);
                if (!empty($info['errcode'])) {
                    return ajaxError($info['errmsg']);
                }
                $nickname = $this->emojiEncode($info['nickname']);
                $headimg = $info['headimgurl'];
                model('User')->update(['nickname' => $nickname, 'headimg' => $headimg, 'unionid' => $unionid], ['id' => $this->uid]);
                return ajaxSuccess('登录成功', 1, ['uid' => $this->uid]);
            } else {
                $iv = input('post.iv', '', 'trim');
                $rawData = input('post.rawData', '', 'trim');
                $encryptedData = input('post.encryptedData', '', 'trim');

                $user = model('User')->where(['id' => $this->uid])->find();
                if (!empty($user['mobile'])) {
                    return ajaxError('用户已绑定手机号');
                }

                if (!empty($user['unionid'])) {
                    //return ajaxError('',-902);
                }

                if (!$iv || !$rawData || !$encryptedData) {
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
                    return ajaxError("错误代码:" . $errCode);
                }
                $data = json_decode($data, true);
                $unionid = $data['unionId'];
                $nickname = $data['nickName'];
                $headimg = $data['avatarUrl'];

                //通过unionid查询是否存在用户
                $wxuser = model('User')->get(['unionid' => $unionid]);
                if (empty($wxuser)) {
                    //不存在，则将用户信息更新至用户表
                    model('User')->update(['nickname' => $nickname, 'headimg' => $headimg, 'unionid' => $unionid], ['id' => $this->uid]);
                    return ajaxSuccess('登录成功', 1, ['uid' => $this->uid]);
                } else {
                    if ($this->uid != $wxuser['id']) {
                        //存在
                        //取消当前用户
                        model('User')->update(['mnp_openid' => '', 'status' => -1], ['id' => $this->uid]);

                        //合并到老用户
                        model('User')->update(['mnp_openid' => $user['mnp_openid']], ['id' => $wxuser['id']]);
                    }
                    $user_id = $wxuser['id'];

                    $login = new Login();
                    $login->updateLogin($user_id);

                    return ajaxSuccess('登录成功', 1, ['uid' => $user_id]);
                }
            }
        } catch (\Exception $e) {
            db('get_code')->insert(['code' => $e->getMessage(), 'time' => date("Y-m-d H:i:s", time())]);
        }


    }

    /**
     *绑定手机号
     */
    public function bindMobile()
    {
        $type = input('post.type/s', 'wx');
        $mobile = input('post.mobile/s');
        $vercode = input('post.vercode/s');

        if (!is_mobile($mobile)) {
            return ajaxError('请正确填写手机号码');
        }
        if (!is_vercode($vercode, 4)) {
            return ajaxError('验证码必须为4位');
        }

        $login = new Login();
        //判断是否存在验证码
        if (!$login->checkVf($mobile, $vercode)) {
            //return ajaxError('验证码错误');
        }

        $user = model('User')->where(['id' => $this->uid])->find();
        if (!empty($user['mobile'])) {
            return ajaxError('用户已绑定手机号');
        }

        $mobileUser = model('User')->where(['mobile' => $mobile])->find();
        if ($type == 'h5') {
            if (empty($mobileUser)) {
                $user_info = [];
                $user_info['mobile'] = $mobile;
                $user_info['user_lv'] = 2;
                model('User')->update($user_info, ['id' => $user['id']]);
                $user_id = $user['id'];
            } else {
                if ($mobileUser['openid']) {/*
                    dump($mobileUser['openid']);
                    dump($mobileUser['id']);
                    dump($mobileUser);exit;
                    //微信换绑定
                    model('User')->update(['openid'=>$user['openid']], ['id' => $mobileUser['id']]);
                    model('User')->update(['openid' =>$mobileUser['openid']], ['id' => $user['id']]);*/

                    return ajaxError('此手机号码已被占用！');
                } else {
                    model('User')->update(['openid' => $user['openid']], ['id' => $mobileUser['id']]);
                    //取消当前用户
                    model('User')->update(['openid' => '', 'status' => -1], ['id' => $user['id']]);
                }
                $user_id = $mobileUser['id'];
            }
        } else {
            if (empty($mobileUser)) {
                $user_info = [];
                $user_info['mobile'] = $mobile;
                $user_info['user_lv'] = 1;

                //注册赠送会员体验
                $user_info['user_lv'] = 2;
                $user_info['vip_begtime'] = time();
                $user_info['vip_endtime'] = time() + 7 * 24 * 3600;

                model('User')->update($user_info, ['id' => $user['id']]);
                $user_id = $user['id'];
            } else {
                if (!empty($mobileUser['mnp_openid'])) {
                    return ajaxError('该手机已绑定微信用户');
                }
                if (!empty($mobileUser['unionid']) && $mobileUser['unionid'] != $user['unionid']) {
                    return ajaxError('该手机已绑定其他微信用户');
                }

                //取消当前用户
                model('User')->update(['mnp_openid' => '', 'status' => -1], ['id' => $user['id']]);

                //合并到老用户
                model('User')->update(['mnp_openid' => $user['mnp_openid'], 'unionid' => $user['unionid']], ['id' => $mobileUser['id']]);
                $user_id = $mobileUser['id'];
            }
        }
        $login->updateLogin($user_id);

        return ajaxSuccess('绑定成功', 1, ['uid' => $user_id]);
    }

    public function payLog()
    {
        $user_id = $this->uid;
        $list = model('Payment')->where(['user_id' => $user_id, 'status' => 1])->order('pay_time desc')->field('id,money,type,payment_id,pay_time')->select();
        foreach ($list as &$item) {
            $item['pay_time'] = date('Y-m-d H:i:s', $item['pay_time']);
        }

        return ajaxSuccess('', 1, $list);
    }

    /**
     * 确认订单详情
     */
    public function Payment_details()
    {
        $id = input('id');
        if (!$id) {
            return ajaxError('参数有误！');
        }
        $course = db('course')->where('is_del', 0)->where('id', $id)->field('id,title,brief,broadcast_time,original_price,present_price,type')->find();
        $course['broadcast_time'] = date('Y-m-d', $course['broadcast_time']);
        $course['coupon_price'] = round($course['original_price'] - $course['present_price'], 2);
        return ajaxSuccess('', 1, $course);
    }

    //判断是否购买课程
    public function jurisdiction()
    {
        $id = input('id');
        $type = input('type');
        if (!$id || !$type) {
            return ajaxError('参数错误！');
        }
        if ($type == 2) {
            $id = db('course_node')->where('id', $id)->value('course_id');
        }
        $res = db('user_course')->where('user_id', $this->uid)->where('type', 1)->where('course_id', $id)->where('is_del', 0)->find();
        if ($res) {
            return ajaxSuccess('', 1, '已购买');
        } else {
            return ajaxSuccess('', 0, '未购买');
        }
    }

    //支付成功返回请求助教微信
    public function after_sales()
    {
        $id = input('id');
        if (!$id) {
            return ajaxError('参数错误！');
        }
        $res = db('course')->where('is_del', 0)->where('id', $id)->find();
        if (!$res) {
            return ajaxError('购买课程有误！');
        }
        if ($res['after_sales'] == 1) {
            $res['assistant'] = str_replace('，', ',', $res['assistant']);
            $assistant = explode(',', $res['assistant']);
            $is_assistant = $assistant[0];
            if (count($assistant) > 1) {
                $course_id = Cache::get('course_id' . $id);
                if ($course_id) {
                    $a = array_search($course_id, $assistant);
                    if ($a < count($assistant) - 1) {
                        $is_assistant = $assistant[$a + 1];
                    } else {
                        $is_assistant = $assistant[0];
                    }
                }
            }
            Cache::set('course_id' . $id, $is_assistant);
            $teacher = db('teacher_assistant')->where('id', $is_assistant)->find();
            $data = [
                'name' => '添加' . $teacher['name'],
                'qr_code' => $teacher['qr_code']
            ];
        } else {
            $data = [
                'name' => '扫码关注公众号',
                'qr_code' => db('config')->where('type', 'wechat')->value('content')
            ];
        }
        return ajaxSuccess('', 1, $data);
    }

    //学习
    public function study()
    {
        $where['is_del'] = 0;
        $course_id = db('user_course')->where($where)->where('user_id', $this->uid)->column('course_id');

        $course_node = db('course_node')
            ->alias('cn')
            ->join('course c', 'cn.course_id = c.id')
            ->where('cn.is_del', 0)
            ->where('cn.type', '1')
            ->whereIn('cn.course_id', $course_id)
            ->order('cn.broadcast_time')
            ->field('cn.id,cn.title,cn.broadcast_time,cn.teacher,cn.type,cn.Live_state,c.title as course_title')
            ->select();
        $course_node1 = db('course_node')
            ->alias('cn')
            ->join('course c', 'cn.course_id = c.id')
            ->where('cn.is_del', 0)
            ->where('cn.type', '2')
            ->whereIn('cn.course_id', $course_id)
            ->order('cn.broadcast_time')
            ->field('cn.id,cn.title,cn.broadcast_time,cn.teacher,cn.type,cn.Live_state,c.title as course_title')
            ->select();
        $course_node = array_merge($course_node,$course_node1);
        //获取本周每天时间戳
        $time = time();
        if (input('post.time')) {
            $time = input('post.time');
        }
        $time = strtotime(date('Y-m-d', $time));
        $week = ['一', '二', '三', '四', '五', '六', '日'];
        //当前周一时间戳
        $Monday = strtotime('this week Monday', $time);
        $arr = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $Monday + $i * 86400;
            if ($day == strtotime(date('Y-m-d', time()))) {
                $arr[] = ['今', date('d', $day), $day];
            } else {
                $arr[] = [$week[$i], date('d', $day), $day];
            }
        }
        //课节表
        foreach ($course_node as $item => $value) {
            if ($time <= $value['broadcast_time'] && $time + 86400 >= $value['broadcast_time']) {
                if ($value['Live_state'] == 1) {
                    $value['type_name'] = '正在直播';
                } else {
                    $value['type_name'] = '等待直播';
                }
                $course_node[$item]['broadcast_time'] = date('H:i', $value['broadcast_time']);
            } else {
                unset($course_node[$item]);
            }
        }
        $course = db('course')->where($where)->whereIn('id', $course_id)->order('order desc')->field('id,broadcast_time,title,pic,type')->select();
        foreach ($course as $item => &$value) {
            if ($value['type'] == 1) {
                /*$course_nodes = db('course_node')->where('broadcast_time','>=',$time)->where('broadcast_time','<=',$time)->order('broadcast_time')->field('id,title,broadcast_time,teacher,type,Live_state')->select();
                if ($course_nodes){
                    foreach ($course_nodes as $v){

                    }
                }*/
                if (strtotime(date('Y-m-d', time())) <= $value['broadcast_time'] && strtotime(date('Y-m-d', time())) + 86400 > $value['broadcast_time']) {
                    $value['broadcast_time'] = '开课时间：' . date("H:i", $value['broadcast_time']);
                } elseif (strtotime(date('Y-m-d', time())) < $value['broadcast_time']) {
                    $value['broadcast_time'] = $this->diffBetweenTwoDays($value['broadcast_time'],time()) . '天后开课';
                } else {
                    $value['broadcast_time'] = '';
                }
            } else {
                $value['broadcast_time'] = '';
            }
        }
        $data = [
            'arr' => $arr,
            'course_node' => $course_node,
            'course' => $course,
            'count' => count($course_node)
        ];
        return ajaxSuccess('', 1, $data);
        //
        //dump($course_node);
    }

    /**
     *  * 求两个日期之间相差的天数
     *  * (针对1970年1月1日之后，求之前可以采用泰勒公式)
     *  * @param string $day1
     *  * @param string $day2
     *  * @return number
     *  */
    public function diffBetweenTwoDays($day1, $day2)
    {
        if ($day1 < $day2) {
            $tmp = $day2;
            $day2 = $day1;
            $day1 = $tmp;
        }
        return ceil(($day1 - $day2) / 86400);
    }

    //表情转字符串
    public function emojiEncode($content)
    {
        return json_decode(preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i", function ($str) {
            return addslashes($str[0]);
        }, json_encode($content)));
    }

//字符串转表情
    public function emojiDecode($content)
    {
        return json_decode(preg_replace_callback('/\\\\\\\\/i', function () {
            return '\\';
        }, json_encode($content)));
    }
    /**
    报名活动
     */
    public function partake_activity()
    {
        if (!input('post.id') && !input('post.mobile') && !input('post.name') && !input('post.age') && !input('post.sex')){
            return ajaxError('参数有误');
        }
        $activity = db('activity')->where('id',input('post.id'))->where('is_del',0)->find();
        if (!$activity){
            return ajaxError('活动有误！');
        }
        if ($activity['surplus_num'] <= 0){
            return ajaxError('活动报名数已满！');
        }
        $res = db('partake_activity')->where('activity_id',input('post.id'))->where('mobile',input('post.mobile'))->find();
        if ($res){
            return ajaxError('请勿重复报名！');
        }
        $id = db('partake_activity')->insertGetId([
            'user_id'       => $this->uid,
            'activity_id'   => input('post.id'),
            'mobile'        => input('post.mobile'),
            'name'          => input('post.name'),
            'age'           => input('post.age'),
            'sex'           => input('post.sex'),
            'comment'       => input('post.comment'),
            'partake_time'  => time()
        ]);
        if ($id){
            db('activity')->where('id',input('post.id'))->setDec('surplus_num');
            return ajaxSuccess('',1,['id'=>$id]);
        }else{
            return ajaxError('报名失败');
        }
    }

    /*
     * 修改报名信息
     * */
    public function partake_activity_edit()
    {
        try{
            $id = db('partake_activity')->where('id',input('post.id'))->update([
                'mobile'        => input('post.mobile'),
                'name'          => input('post.name'),
                'age'           => input('post.age'),
                'sex'           => input('post.sex'),
                'comment'       => input('post.comment'),
            ]);
            if ($id){
                return ajaxSuccess('',1,'修改成功');
            }else{
                return ajaxSuccess('',2,'未做修改');
            }
        }catch (\Exception $e){
            return ajaxError('修改失败');
        }

    }

    /*
     *
     * 报名信息
     * */
    public function activity_info()
    {
        if (!input('post.activity_id')){
            return ajaxError('参数有误');
        }
        $activity = db('partake_activity')->where('activity_id',input('post.activity_id'))->where('user_id',$this->uid)->find();
        if ($activity){
            return ajaxSuccess('',1,$activity);
        }else{
            return ajaxSuccess('',1,[]);
        }
    }
}