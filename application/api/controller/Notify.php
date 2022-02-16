<?php


namespace app\api\controller;


use app\common\WechatAppPay;
use think\Controller;
use think\Log;
use app\api\model\Payment;

class Notify extends Controller
{
    public function notify_url_for_routine()
    {
        Log::info('小程序支付异步回调');
        // 实例化微信支付类
        $appid = config('wechat_routine_pay.appid'); // 应用id
        $mch_id = config('wechat_routine_pay.mch_id'); // 商户号
        $key = config('wechat_routine_pay.key'); // 商户key
        $notify_url = config('wechat_routine_pay.notify_url'); // 回调地址
        $wechatApp = new WechatAppPay($appid, $mch_id,  $key,$notify_url);
        // 接收参数
        $res = $wechatApp->getNotifyData();
        if ($res['return_code'] == 'SUCCESS') {
            // 验证签名
            $return_sign = $res['sign'];
            unset($res['sign']);
            // 生成签名ex
            $sign = $wechatApp->getSign($res);
            // 比对签名
            if ($return_sign == $sign) {
                // 支付成功
                if ($res['result_code'] == 'SUCCESS') {
                    $orderId = $res['out_trade_no'];
                    db('get_code')->insertGetId(['code'=>'2'.$orderId,'time'=>time()]);
                    Payment::paySuccess($orderId);

                    $wechatApp->replyNotify();
                }
            } else {
                Log::write('【app】【订单】【异常】签名不正确');
                return '签名不正确';
            }
        }
    }

    public function notify_url_for_app()
    {
        Log::info('app支付异步回调');
        // 实例化微信支付类
        $appid = config('wechat_app_pay.appid'); // 应用id
        $mch_id = config('wechat_app_pay.mch_id'); // 商户号
        $key = config('wechat_app_pay.key'); // 商户key
        $notify_url = config('wechat_app_pay.notify_url'); // 回调地址
        $wechatApp = new WechatAppPay($appid, $mch_id, $key,$notify_url);
        // 接收参数
        $res = $wechatApp->getNotifyData();
        if ($res['return_code'] == 'SUCCESS') {
            // 验证签名
            $return_sign = $res['sign'];
            unset($res['sign']);
            // 生成签名ex
            $sign = $wechatApp->getSign($res);
            // 比对签名
            if ($return_sign == $sign) {
                // 支付成功
                if ($res['result_code'] == 'SUCCESS') {
                    $orderId = $res['out_trade_no'];
                    db('get_code')->insertGetId(['code'=>'1'.$orderId,'time'=>time()]);
                    Payment::paySuccess($orderId);

                    $wechatApp->replyNotify();
                }
            } else {
                Log::write('【app】【订单】【异常】签名不正确');
                return '签名不正确';
            }
        }
    }

}