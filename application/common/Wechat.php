<?php
/**
 * Created by PhpStorm.
 * User: hutuo
 * Date: 2017/7/13
 * Time: 11:06
 */
namespace app\common;

class Wechat{
    private static $_WX_APP_ID      = "wxd01c53b5b22439cc";
    private static $_WX_APP_SECRET = "aa3b787a377051bf63ffb9d24b5ba790";

    public function __construct($appid='',$appsecret='')
    {
        if ($appid != ''){
            self::$_WX_APP_ID = $appid;
        }

        if ($appsecret != ''){
            self::$_WX_APP_SECRET = $appsecret;
        }
    }
    //小程序
    function getOpendId($code){
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=".self::$_WX_APP_ID."&secret=".self::$_WX_APP_SECRET."&js_code=".$code."&grant_type=authorization_code";
        $res = json_decode(HttpCurl::curlRequest($url), true);

        if(empty($res['errcode']))
        {
            return $res;
        }else{
            return ['errcode'=>$res['errcode'], 'errmsg'=>$res['errmsg']];
        }
    }

    /**
     * 通过微信code获取用户授权数据包
     * @param $code
     * @return mixed|string
     */
    public function getAccessTokenByCode ( $code)
    {
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".self::$_WX_APP_ID."&secret=".self::$_WX_APP_SECRET."&code={$code}&grant_type=authorization_code ";
        $data = HttpCurl::curlRequest($url);
        return $data;
    }
    // 过滤掉emoji表情
    private function filterEmoji($str)
    {
        $str = preg_replace_callback(
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $str);

        return $str;
    }
    /**
     * 通过OPENID获取用户信息
     * @param $openid
     * @return mixed|string
     */
    public function getUserInfo ($token, $openid)
    {
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$token}&openid={$openid}&lang=zh_CN";
        //$res = filterEmoji(file_get_contents($url));
        $res = json_decode(mb_convert_encoding(HttpCurl::curlRequest($url), 'UTF-8', 'UTF-8'), true);
        if(empty($res['errcode']))
        {
            return $res;
        }else{
            return ['errcode'=>$res['errcode'], 'errmsg'=>$res['errmsg']];
        }

    }
    /**
     *微信公众号通过access_token和openid获取用户信息
     *
     */
    public function getBinUserInfo($access_token,$openid)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid={$openid}";
        $res = json_decode(mb_convert_encoding(HttpCurl::curlRequest($url), 'UTF-8', 'UTF-8'), true);
        if(empty($res['errcode']))
        {
            return $res;
        }else{
            return ['errcode'=>$res['errcode'], 'errmsg'=>$res['errmsg']];
        }
    }
    /**
     * 获取app信息数组
     * @return array
     */
    public function getSignPackage($url) {
        $jsapiTicket = $this->getJsApiTicket();

        // 注意 URL 一定要动态获取，不能 hardcode.
        //$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        //$url = "$protocol"."$_SERVER[HTTP_HOST]/";

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $param = [];
        $param['jsapi_ticket'] = $jsapiTicket;
        $param['noncestr']     = $nonceStr;
        $param['timestamp']    = $timestamp;
        $param['url']          = $url;//str_replace('#/','',$url);

        $string = $this -> formatBizQueryParaMap($param);

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => self::$_WX_APP_ID,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    /**
     * 获取token值
     * @return mixed|string
     */
    public function getAccessToken ()
    {
        if(!file_exists("./static/wechat/json/access_token.json")) {
            fopen("./static/wechat/json/access_token.json", "w");
        }

        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode(file_get_contents("./static/wechat/json/access_token.json"));
        if ($data->expire_time < time()) {
            // 如果是企业号用以下URL获取access_token
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::$_WX_APP_ID."&secret=".self::$_WX_APP_SECRET;
            $res = json_decode(HttpCurl::curlRequest($url));
            $access_token = $res->access_token;
            if ($access_token) {
                $data->expire_time = time() + 7000;
                $data->access_token = $access_token;
                $fp = fopen("./static/wechat/json/access_token.json", "w");
                fwrite($fp, json_encode($data));
                fclose($fp);
            }
        } else {
            $access_token = $data->access_token;
        }
        return $access_token;
    }
    //过期重新获取
    public function CgetAccessToken()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::$_WX_APP_ID."&secret=".self::$_WX_APP_SECRET;
        $res = json_decode(HttpCurl::curlRequest($url));
        $access_token = $res->access_token;
        $data = (object)[];
        if ($access_token) {
            $data->expire_time = time() + 7000;
            $data->access_token = $access_token;
            $fp = fopen("./static/wechat/json/access_token.json", "w");
            fwrite($fp, json_encode($data));
            fclose($fp);
        }
    }
    /**
     * 获取jsapi_ticket值
     * @return mixed|string
     */
    public function getJsApiTicket ()
    {
        if(!file_exists("./static/wechat/json/jsapi_ticket.json")) {
            fopen("./static/wechat/json/jsapi_ticket.json", "w");
        }

        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode(file_get_contents("./static/wechat/json/jsapi_ticket.json"));
        if (empty($data)||$data->expire_time < time()) {
            $accessToken = $this->getAccessToken();
            // 如果是企业号用以下 URL 获取 ticket
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $res = json_decode(HttpCurl::curlRequest($url));
            $ticket = $res->ticket;
            if ($ticket) {
                $ct = (object)array();
                $ct->expire_time = time() + 7000;
                $ct->jsapi_ticket = $ticket;
                $fp = fopen("./static/wechat/json/jsapi_ticket.json", "w");
                fwrite($fp, json_encode($ct));
                fclose($fp);
            }
        } else {
            $ticket = $data->jsapi_ticket;
        }
        return $ticket;
    }

    /**
     *
     * 发送模板消息
     *
     **/
    public function sendTemplateMsg($data){
        $data = json_encode($data);

        $access_token = $this->getAccessToken();
        $url  = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$access_token";
        return HttpCurl::httpRequest($url, "POST", $data);
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 	作用：格式化参数，签名过程需要使用
     */
    function formatBizQueryParaMap($paraMap)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v)
        {
            //$buff .= strtolower($k) . "=" . $v . "&";
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar = '';
        if (strlen($buff) > 0)
        {
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }
}