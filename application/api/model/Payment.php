<?php


namespace app\api\model;


use app\common\HttpCurl;
use think\Db;
use think\Log;
use think\Model;

class Payment extends Model
{
    public static function paySuccess($payment_id){
        $order_info = self::where('payment_id', $payment_id)->find();
        if (!$order_info) {
            Log::write($payment_id . '【订单】【异常】订单不存在');
            return false;
        } elseif ($order_info['status'] != 0) {
            Log::write($payment_id . '【订单】【异常】订单状态错误');
            return false;
        }

        Db::startTrans();
        try{
            self::update(['status'=>1,'pay_time'=>time()],['id'=>$order_info['id']]);

            $userModel = new User();
            $userInfo = $userModel -> get(['id'=>$order_info['user_id'],'status'=>0]);
            if (empty($userInfo)){
                Log::write($payment_id . '【订单】【异常】用户状态错误');
                return false;
            }

            $data = ['user_lv'=>3];
            db('user_course')->insert(['user_id'=>$order_info['user_id'],'type'=>1,'course_id'=>$order_info['type_id'],'pay_time'=>time()]);
            db('course')->where('id',$order_info['type_id'])->setInc('purchase_num');
            $userModel->update($data,['id'=>$order_info['user_id']]);
            static::pay($payment_id);
            Db::commit();
            return true;
        }catch (\Exception $e){
            db('get_code')->insert(['code'=>$e->getMessage(),'time'=>date('Y-m-d H:i')]);
            Db::rollback();
            Log::write('【订单】【失败】异步回调发生事务回滚，更改状态失败');
            return false;
        }
    }
    public static function pay($payment_id){
        try {
            $order_info = self::where('payment_id', $payment_id)->find();
            $userModel = new User();
            $userInfo = $userModel->get(['id' => $order_info['user_id'], 'status' => 0]);
            $appid = config('Wechat.appid');
            $appsecret = config('Wechat.appsecret');
            $model_id = config('Wechat.Paymodel');
            $uirl = "https://pgt.cooov.com/pages/detail/detail?id=" . $order_info['type_id'];
            $first = '购买成功';
            $keyword1 = db('course')->where('id', $order_info['type_id'])->value('title');
            $keyword2 = date('Y-m-d', time());
            $remark = '祝您上课愉快！';
            $wechat = new \app\common\Wechat($appid, $appsecret);
            $AccessToken = $wechat->getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$AccessToken&openid=" . $userInfo['openid'] . "&lang=zh_CN";
            $res = json_decode(HttpCurl::curlRequest($url));
            $sms_template_code = config('ALICLOUD.PayTemplateNotice');

            $state = 0;
            if ($res->subscribe) {//已关注
                //通过微信公众号发送上课提醒
                $res = static::Send_notice($model_id, $userInfo['openid'], $uirl, $first, $keyword1, $keyword2, $remark,$AccessToken);
                if ($res['errcode'] == 0) {
                    $state = 1;
                }
                $type = 1;
            } else {//未关注
                if ($userInfo['mobile']) {
                    //通过短信提醒用户上课
                    $send = send_sms_notice($userInfo['mobile'], json_encode(['title' => $keyword1, 'time' => $keyword2]), $sms_template_code);
                    db('get_code')->insert(['code'=>json_encode($send),'time'=>date('Y-m-d H:i')]);
                    list($status, $return_code, $sub_code) = $send;
                    if ($status) {
                        $state = 1;
                    }
                }
                $type = 2;
            }
            $notice = [
                'title' => $keyword1,
                'type' => $type,
                'c_id' => $order_info['type_id'],
                'uid' => $userInfo['id'],
                'mobile' => $userInfo['mobile'],
                'state' => $state,
                'time' => time()
            ];
            db('send_notice')->insert($notice);
        } catch (\Exception $e){
            db('get_code')->insert(['code'=>$e->getMessage(),'time'=>date('Y-m-d H:i')]);
        }
    }
    //发送通知
    public static function Send_notice($model_id,$openid,$url,$first,$keyword1,$keyword2,$remark,$AccessToken)
    {
        try {
            $data = [
                'touser' => $openid,
                'template_id' => $model_id,
                'url' => $url,
                'data' => [
                    'first' => [
                        "value" => $first,
                        "color" => "#173177"
                    ],
                    'keyword1' => [
                        "value" => "$keyword1",
                        "color" => "#173177"
                    ],
                    'keyword2' => [
                        "value" => $keyword2,
                        "color" => "#173177"
                    ],
                    'remark' => [
                        "value" => "$remark",
                        "color" => "#173177"
                    ],

                ]
            ];
            $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$AccessToken";
            $res = HttpCurl::curlRequest($url, $data, 'POST', 'json');
            return json_decode($res, true);
        }catch (\Exception $e){
            db('get_code')->insert(['code'=>$e->getMessage(),'time'=>date('Y-m-d H:i')]);
        }
    }
}