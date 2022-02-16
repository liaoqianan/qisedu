<?php
/**
 * Created by PhpStorm.
 * User: hutuo
 * Date: 2018/11/02
 * Time: 09:21
 */

namespace app\api\controller;

use think\Cache;
use think\Controller;

class Buildtoken extends Controller
{
    protected $_APP_ID = "49789999";
    protected $_APP_SECERT = "dc4ec2304a397b7f29c929b98965b874";

    public function getAccessToken()
    {
    
        $postData = input('post.');

        if (empty($postData['app_id'])) {
            return ajaxError('缺少app_id', -801);
        }

        if ($postData['app_id'] != $this->_APP_ID) {
            return ajaxError('app_id非法', -802);
        }

        if (empty($postData['signature'])) {
            return ajaxError('缺少签名', -803);
        }

        if (empty($postData['user_id'])) {
            return ajaxError('缺少用户id', -804);
        }

        $signature = $postData['signature'];
        unset($postData['signature']);
        $sign = $this->getAuthToken($this->_APP_SECERT, $postData);

        if ($sign !== $signature) {
            return ajaxError('身份令牌验证失败', -805);
        }
        $expires = 86400;
        $accessToken = Cache::get("TOKEN_" . $postData['user_id']);
        if ($accessToken) {
            Cache::clear("TOKEN_" . $postData['user_id']);
        }

        $accessToken = md5($this->_APP_ID . $this->_APP_SECERT . $postData['user_id'] . rand(100000, 999999));
        Cache::set("TOKEN_" . $postData['user_id'], $accessToken, $expires);

        $return['access_token'] = $accessToken;
        $return['expires_in'] = $expires;

        //return json($return);
        return ajaxSuccess('ok',1,$return);
    }

    /**
     * 根据AppSecret和数据生成相对应的身份认证秘钥
     * @param $appSecret
     * @param $data
     * @return string
     */
    public function getAuthToken( $appSecret, $data ){
        if(empty($data)){
            return '';
        }else{
            $preArr = array_merge($data, array('app_secret' => $appSecret));
            ksort($preArr);
            $preStr = http_build_query($preArr);
            return md5($preStr);
        }
    }
}
