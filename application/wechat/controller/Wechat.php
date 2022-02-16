<?php
namespace app\wechat\controller;
use think\Controller;
use think\facade\Config;

class Wechat extends Controller
{

    public function index()
    {
        /* 验证接口 */
        $config = [
            'token'             =>  'cooov_wx_callback_api_token', // 填写你设定的key
            'appid'             =>  'wx80250af5330a6de2', // 填写高级调用功能的app id, 请在微信开发模式后台查询
            'appsecret'         =>  '38b30c6c823e5f3829802b7f9df1677f', // 填写高级调用功能的密钥
            'encodingaeskey'    =>  'SLgI6P8LnTlHgybVOOl7OXvKzjqAnXzLoS1GATOBxcb', // 填写加密用的EncodingAESKey（可选，接口传输选择加密时必需）
        ];
        try {

            // 实例接口，同时实现接口配置验证与解密处理
            $api = new \WeChat\Receive($config);

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
                    $this->news($api,$data['Content']);

                // 事件类型处理
                case 'event':
                    $this->event($api,$data);
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

    /**
     * 文本
     * @param  [string] $api [实例化sdk]
     * @param  [string] $keys [文本]
     */
    public function news($api, $keys)
    {
        $data[] = [
            'Title' =>'查看“'.$keys.'”相关搜索结果',
            'Description' =>'',
            'PicUrl' =>'http://www.jjcpchina.com/static/images/wechat_head.jpg',
            'Url' =>'http://www.jjcpchina.com/index/article/search.html?keyword='.$keys,
        ];
        return $api->news($data)->reply();

    }

    /**
     * 文本
     * @param  [string] $api [实例化sdk]
     * @param  [string] $keys [文本]
     */
    public function znews($api, $keys)
    {
        $data[] = [
            'Title' =>$keys,
            'Description' =>'12345',
            'PicUrl' =>'http://www.jjcpchina.com/static/images/wechat_head.jpg',
            'Url' =>'https://mp.weixin.qq.com/s/AbhRc5CSZR061nFUR4_YpA',
        ];
        return $api->news($data)->reply();

    }
    /**
     * 事件
     * @param  [string] $api [实例化sdk]
     * @param  [string] $keys [文本]
     */
    public function event($api, $data)
    {
        if($data['Event'] =="CLICK"){
            $res = db('wechat_reply')->where(['keyword' => $data['EventKey'], 'status' => 0])->find();

            switch ($res['type']) {
                // 文本类型
                case 'text':
                    $api->text($res['content'])->reply();
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
        if($data['Event'] =="subscribe"){
            //$this->znews($api,1234);
            $api->text('买家具先看测评！欢迎关注家具测评网！
回复品牌名称，马上能看到该品牌的买家秀、测评、产品图库。

有关家具的问题欢迎给我们留言哦！
我们会第一时间回复您。')->reply();
            die();
        }

    }




}