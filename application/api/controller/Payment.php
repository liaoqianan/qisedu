<?php


namespace app\api\controller;


use app\common\AliAPPPay;
use app\common\WechatAppPay;

class Payment extends UserBase
{
    var $typeList = ['routine','weixin_app','ali_app','ali_ios','mobile'];

    public function createPayment(){
        if (empty($this->userInfo['mobile'])){
            return ajaxError('请绑定手机号',-902);
        }

        $user_id = $this->uid;

        $Payment = model('Payment')->where('user_id',$user_id)->where('type',input('type'))->where('status',0)->where('type_id',input('type_id'))->find();
        if($Payment){
            model('Payment')->where(['id'=>$Payment['id']])->delete();
        }
        $money = input('money');
        $type = input('type');
        $type_id = input('type_id');
        $payment_id = $this->getPaymentId();
        if (empty($money) || empty($type) || empty($type_id)){
            return ajaxError('参数错误');
        }
        $data = [];
        $data['user_id'] = $user_id;
        $data['money'] = $money;
        $data['type'] = $type;
        $data['type_id'] = $type_id;
        $data['payment_id'] = $payment_id;
        $data['add_time'] = time();

        $res = model('Payment')->insert($data);
        if ($res){
            return ajaxSuccess('下单成功',1,['payment_id'=>$payment_id]);
        }
        return ajaxError('下单失败');
    }

    /**
     * @desc 订单支付
     * @access public
     * @return json
     */
    public function toPay()
    {
        $payment_id = input("post.payment_id/s");
        $type     = input("post.type/s",'routine');

        if(empty($payment_id))
        {
            return ajaxError('支付信息错误');
        }

        if(!in_array($type, $this->typeList))
        {
            return ajaxError('支付类型不正确');
        }

        $order = model('Payment')->get(['payment_id'=>$payment_id]);

        if (empty($order)) {
            return ajaxError('支付信息不存在');
        }

        if ($order['pay_time'] > 0 || $order['status'] != 0) {
            return ajaxError('该订单已支付，无需重复支付');
        }

        $title = "订单".$order['payment_id']."支付";
        $money = $order['money'];

        $money = 0.01;

        //修改支付方式
        if($type == 'ali_app' || $type == 'ali_ios')
        {
            $pay_type = 2;
        }elseif($type == 'weixin_app'){
            $pay_type = 3;
        }elseif($type == 'mobile'){
            $pay_type = 4;
        }else{
            $pay_type = 1;
        }
        model('Payment')->save(['pay_type'=>$pay_type], ['payment_id'=>$payment_id]);

        //小程序
        if ($type == 'routine') {      //小程序
            $money = $money * 100;
            $openid = $this->userInfo['mnp_openid'];

            // 实例化微信支付类
            $appid = config('wechat_routine_pay.appid'); // 应用id
            $mch_id = config('wechat_routine_pay.mch_id'); // 商户号
            $key = config('wechat_routine_pay.key'); // 商户key
            $notify_url = config('wechat_routine_pay.notify_url'); // 回调地址

            $unifiedOrder = new WechatAppPay($appid,$mch_id,$key,$notify_url);

            $unifiedOrder->setParameter("body", $title);                //商品描述
            $unifiedOrder->setParameter("out_trade_no", $payment_id);        //商户订单号
            $unifiedOrder->setParameter("total_fee", $money);        //总金额
            $unifiedOrder->setParameter("notify_url", $notify_url);        //通知地址
            $unifiedOrder->setParameter("trade_type", "JSAPI");                //交易类型
            $unifiedOrder->setParameter("openid", "$openid");        //用户标识

            $prepay_id = $unifiedOrder->getPrepayId();

            $result = [];
            $timeStamp = time();
            $result['appId'] = $appid;
            $result['timeStamp'] = "$timeStamp";
            $result['nonceStr'] = $unifiedOrder->createNoncestr();
            $result['package'] = "prepay_id=$prepay_id";
            $result['signType'] = "MD5";
            $result['paySign'] = $unifiedOrder->getSign($result);

            return ajaxSuccess('', 1, $result);

        }elseif ($type == 'mobile'){        //H5支付
            $money = $money * 100;
            $openid = $this->userInfo['openid'];
            // 实例化微信支付类
            $appid = config('wechat_app_pay.appid'); // 应用id
            $mch_id = config('wechat_app_pay.mch_id'); // 商户号
            $key = config('wechat_app_pay.key'); // 商户key
            $notify_url = config('wechat_app_pay.notify_url'); // 回调地址
            $unifiedOrder = new WechatAppPay($appid,$mch_id,$key,$notify_url);

            $unifiedOrder->setParameter("body", $title);                //商品描述
            $unifiedOrder->setParameter("out_trade_no", $payment_id);        //商户订单号
            $unifiedOrder->setParameter("total_fee", $money);        //总金额
            $unifiedOrder->setParameter("notify_url", $notify_url);        //通知地址
            $unifiedOrder->setParameter("trade_type", "JSAPI");                //交易类型
            $unifiedOrder->setParameter("openid", "$openid");        //用户标识

            $prepay_id = $unifiedOrder->getPrepayId();

            $result = [];
            $timeStamp = time();
            $result['appId'] = $appid;
            $result['timeStamp'] = "$timeStamp";
            $result['nonceStr'] = $unifiedOrder->createNoncestr();
            $result['package'] = "prepay_id=$prepay_id";
            $result['signType'] = "MD5";
            $result['paySign'] = $unifiedOrder->getSign($result);
            return ajaxSuccess('', 1, $result);
        }elseif ($type == 'weixin_app'){        //微信app
            $money = $money * 100;

            // 实例化微信支付类
            $appid = config('wechat_pay.appid'); // 应用id
            $mch_id = config('wechat_pay.mch_id'); // 商户号
            $key = config('wechat_pay.key'); // 商户key
            $notify_url = config('wechat_pay.notify_url'); // 回调地址
            $wxPay = new WechatAppPay($appid,$mch_id,$key,$notify_url);

            $request_data = array(
                'appid' => $appid,                         #应用APPID
                'mch_id' => $mch_id,                        #商户号
                'trade_type' => 'APP',                            #支付类型
                'nonce_str' => $wxPay->createNoncestr(),  #随机字符串 不长于32位
                'body' => $title,                              #商品名称
                'out_trade_no' => $payment_id,               #商户后台订单号
                'total_fee' => $money,                               #商品价格
                'spbill_create_ip' => get_client_ip(),            #用户端实际ip
                'notify_url' => config('API_URL')."/notify/order", #异步通知回调地址
            );

            $request_data['sign'] = $wxPay->getSign($request_data, 'app');

            // 拼装数据
            $xml_data = $wxPay->arrayToXml($request_data);

            // 发送请求
            $data = $wxPay->postXmlCurl($xml_data, $wxPay->url, $wxPay->curl_timeout);
            $res = $wxPay->xmlToArray($data);
            if($res['return_code'] == 'SUCCESS' && $res['result_code'] == 'SUCCESS')
            {
                $result['appid'] = $appid;  #APPID
                $result['partnerid'] = $mch_id;  #商户号
                $result['prepayid'] = $res['prepay_id'];  //预支付交易会话标识
                $result['noncestr'] = $wxPay->createNoncestr();
                $result['timestamp'] = time();
                $result['package'] = "Sign=WXPay";
                $result['sign'] = $wxPay->getSign($result, 'app');

                return ajaxSuccess('', 1, $result);
            }else{
                return ajaxError($res['err_code_des']);
            }

        }elseif($type == 'ali_app'){  //支付宝APP
            vendor('alipay.aop.AopClient');
            vendor('alipay.aop.request.AlipayTradeAppPayRequest');

            //实例化支付接口
            $aop = new \AopClient();
            $aop->gatewayUrl = AliAPPPay::GATEWAY_URL; //支付宝网关
            $aop->appId = AliAPPPay::APP_ID;
            $aop->rsaPrivateKey = AliAPPPay::RSA_PRIVATE_KEY;
            $aop->alipayrsaPublicKey = AliAPPPay::RSA_PUBLIC_KEY;
            $aop->signType = "RSA2";
            $aop->postCharset = 'UTF-8';
            $aop->format = "json";

            //实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
            $appRequest = new \AlipayTradeAppPayRequest();

            //SDK已经封装掉了公共参数，这里只需要传入业务参数
            $bizcontent = json_encode([
                'body' => $title,  //订单描述
                'subject' => $title,  //订单标题
                'timeout_express' => '30m',
                'out_trade_no' => $payment_id, //商户网站唯一订单号
                'total_amount' => $money, //订单总金额
                'product_code' => 'QUICK_MSECURITY_PAY', //固定值
            ]);

            $notify_url = config('API_URL')."alipaynotify/order";;
            $appRequest->setNotifyUrl($notify_url);  //设置异步通知地址

            $appRequest->setBizContent($bizcontent);
            //这里和普通的接口调用不同，使用的是sdkExecute

            $response = $aop->sdkExecute($appRequest);

            return ajaxSuccess('', 1, $response);

        }elseif($type == 'ali_ios'){  //支付宝ios
            vendor('alipay.aop.AopClient');
            vendor('alipay.aop.request.AlipayTradeAppPayRequest');

            //实例化支付接口
            $aop = new \AopClient();
            $aop->gatewayUrl = AliAPPPay::GATEWAY_URL; //支付宝网关
            $aop->appId = AliAPPPay::APP_ID;
            $aop->rsaPrivateKey = AliAPPPay::RSA_PRIVATE_KEY;
            $aop->alipayrsaPublicKey = AliAPPPay::RSA_PUBLIC_KEY;
            $aop->signType = "RSA2";
            $aop->postCharset = 'UTF-8';
            $aop->format = "json";

            //实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
            $appRequest = new \AlipayTradeAppPayRequest();

            //SDK已经封装掉了公共参数，这里只需要传入业务参数
            $bizcontent = json_encode([
                'body' => $title,  //订单描述
                'subject' => $title,  //订单标题
                'timeout_express' => '30m',
                'out_trade_no' => $payment_id, //商户网站唯一订单号
                'total_amount' => $money, //订单总金额
                'product_code' => 'QUICK_MSECURITY_PAY', //固定值
            ]);

            $notify_url = config('API_URL')."alipaynotify/order";;
            $appRequest->setNotifyUrl($notify_url);  //设置异步通知地址

            $appRequest->setBizContent($bizcontent);
            //这里和普通的接口调用不同，使用的是sdkExecute

            $response = $aop->sdkExecute($appRequest);
            //htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题

//            $response =  htmlspecialchars($response);//就是orderString 可以直接给客户端请求，无需再做处理。
            // 如果最后有问题可以尝试把htmlspecialchars方法去掉，直接返回$response
            return ajaxSuccess('', 1, $response);
        }
    }

    /**
     *
     * 获取订单编号
     *
     */
    private function getPaymentId(){
        $payment_id = date('YmdHis').rand(1000,9999);

        $order = model('Payment')->where('payment_id',$payment_id)->find();
        if (!empty($order)){
            return $this -> getPaymentId();
        }

        return $payment_id;
    }
}