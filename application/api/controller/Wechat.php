<?php
namespace app\api\controller;
use think\Controller;
use think\facade\Config;
use app\common\Wechat\Receive;

class Wechat extends Controller
{

    public function index()
    {
        /* 验证接口 */
        $config = [
            'token' => config('wechat_app_pay.token'), // 填写你设定的key
            'appid' => config('wechat_app_pay.appid'), // 填写高级调用功能的app id, 请在微信开发模式后台查询
            'appsecret' => config('wechat_app_pay.appsecret'), // 填写高级调用功能的密钥
            'encodingaeskey' => config('wechat_app_pay.encodingaeskey'), // 填写加密用的EncodingAESKey（可选，接口传输选择加密时必需）
        ];
        try {
            // 实例接口，同时实现接口配置验证与解密处理
            //$api = new Receive($config);
            $api = new Receive($config);

            // 第二方法实例接口
            // $api = \We::WeChatReceive($config);

            // 获取当前推送接口类型 ( text,image,loction,event... )
            $msgType = $api->getMsgType();

            // 获取当前推送的所有数据
            $data = $api->getReceive();
            /* 分别执行对应类型的操作 */
            switch ($msgType) {
                // 文本类型处理
                case 'text':
                    $this->news($api, $data['Content']);

                // 事件类型处理
                case 'event':
                    $this->event($api, $data);
//                    $api->text(json_encode($data))->reply();

                default:
                    $this->default();
            }
            // 回复文本消息
//            $api->text($msgType)->reply();

            // 回复图文消息（高级图文或普通图文，数组）
//            $api->news($news)->reply();


        } catch (\Exception $e) {
            // 处理异常
            echo $e->getMessage();
        }

    }
    public function default()
    {
        dump(1);
    }

    /**
     * 事件
     * @param  [string] $api [实例化sdk]
     * @param  [string] $keys [文本]
     */
    public function event($api, $data)
    {
        try {
            if ($data['Event'] == "CLICK") {
                db('get_code')->insert(['code' => $data['EventKey'], 'time' => date('Y-m-d H:i:s')]);
                $res['type'] = 'text';

                switch ($res['type']) {
                    // 文本类型
                    case 'text':
                        $api->text('你好呀')->reply();
                        break;
                    // 图片类型
                    case 'image':
                        $this->image($res['content']);
                        break;
                    // 语音类型
                    case 'voice':
                        $this->voice($res['content']);
                        break;
                    // 图文类型
                    case 'news':
                        $this->news(unserialize($res['content']));
                        break;
                }
            }
            if ($data['Event'] == "subscribe") {

                $openid = $data['FromUserName'];
                $Wechat = new \app\common\Wechat(config('wechat_app_pay.appid'), config('wechat_app_pay.appsecret'));
                $info = $Wechat->getBinUserInfo($Wechat->getAccessToken(), $openid);
                if (!empty($info['errcode'])){
                    db('get_code')->insert(['code' => '参数错误', 'time' => date('Y-m-d H:i:s')]);
                }
                $user = db('user')->where('openid',$openid)->where('status',0)->find();
                if ($user){
                    db('user')->where('id',$user['id'])->update(['is_sub'=>'sub','sub_time'=>time(),'nickname'=>$this->emojiEncode($info['nickname']),'headimg'=>$info['headimgurl']]);
                }else{
                    $data = [];
                    $data['openid'] = $openid;
                    $data['refer_id'] = 0;
                    $data['nickname'] = $this->emojiEncode($info['nickname']);
                    $data['headimg'] = $info['headimgurl'];
                    $data['sub_time'] = time();
                    $data['add_time'] = time();
                    $data['is_sub'] = 'sub';
                    $data['add_ip'] = get_client_ip();
                    model('User')->insert($data);
                }
                $api->text('你好！')->reply();
                die();
            }
            if ($data['Event'] == "unsubscribe") {
                db('user')->where('openid',$data['FromUserName'])->update(['is_sub'=>'unsub','sub_time'=>time()]);
            }
        } catch (\Exception $e) {
            db('get_code')->insert(['code' => $e->getMessage().$e->getCode(), 'time' => date('Y-m-d H:i:s')]);
        }
    }
    //表情转字符串
    public  function emojiEncode($content) {
        return json_decode(preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i", function($str) {
            return addslashes($str[0]);
        }, json_encode($content)));
    }
}