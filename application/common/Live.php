<?php
/**
 * Created by PhpStorm.
 * User: hutuo
 * Date: 2017/7/13
 * Time: 11:06
 */

namespace app\common;

class Live
{

    private static $APP_ID = "fu8k6enpot";
    private static $APP_SECRET = "6e15152c12ad4ad789773524e2528399";
    private static $USER_ID = "e8369dc6df";

    public function __construct($appId = '', $AppSecret = '', $userId)
    {
        if ($appId != '') {
            self::$APP_ID = $appId;
        }
        if ($AppSecret != '') {
            self::$APP_SECRET = $AppSecret;
        }
        if ($userId != '') {
            self::$USER_ID = $userId;
        }
    }

    /**
     * 创建直播
     */
    public static function create_Live($data)
    {
        $params = [
            'appId' => self::$APP_ID,
            'timestamp' => time() * 1000,
            'userId' => self::$USER_ID,
            'name' => $data['name'],
            'channelPasswd' => $data['channelPasswd'],
        ];
        $params['sign'] = static::getSign($params, self::$APP_SECRET);

        $url = 'http://api.polyv.net/live/v2/channels/';
        $res = json_decode(HttpCurl::curlRequest($url, $params, 'POST'), true);
        if ($res['code'] == 200) {
            //$res = '{"code":200,"status":"success","message":"","data":{"channelId":2113357,"userId":"e8369dc6df","name":"测试标题","publisher":"主持人","description":"","url":"rtmp://push-d1.videocc.net/recordf/e8369dc6df1611301411613b094?auth_key=1611303211-0-0-79bf06754bc2ffae2070646e8d295d3c","stream":"e8369dc6df1611301411613b094","logoImage":"","logoOpacity":1.0,"logoPosition":"tr","logoHref":"","coverImage":"","coverHref":"","waitImage":"","waitHref":"","cutoffImage":"","cutoffHref":"","advertType":"NONE","advertDuration":0,"advertWidth":0,"advertHeight":0,"advertImage":"","advertHref":"","advertFlvVid":"","advertFlvUrl":"","playerColor":"#666666","autoPlay":true,"warmUpFlv":"","passwdRestrict":false,"passwdEncrypted":"","isOnlyAudio":"N","isLowLatency":"N","m3u8Url":"http://pull-d1.videocc.net/recordf/e8369dc6df1611301411613b094.m3u8?auth_key=1611301411-0-0-12556718946c46a6e9134d6c9f2ce508","m3u8Url1":"","m3u8Url2":"","m3u8Url3":"","channelLogoImage":"http://liveimages.videocc.net/assets/wimages/pc_images/logo.png","scene":"alone","channelViewerPasswd":null,"channelPasswd":"123456","linkMicLimit":0,"streamType":"client","pureRtcEnabled":"N","type":"transmit","cnAndEnLiveEnabled":"N","pushEnUrl":null,"currentTimeMillis":1611301411928}}';
            return $res['data'];
        } else {
            return ['code' => $res['code'], 'message' => $res['message']];
        }
    }
    /**
    删除直播
     */
    public static function del_live($channelId)
    {
        $url = "http://api.polyv.net/live/v2/channels/$channelId/delete";
        $params = [
            'appId' => self::$APP_ID,
            'timestamp' => time() * 1000,
            'userId' => self::$USER_ID,
        ];
        $params['sign'] = static::getSign($params, self::$APP_SECRET);
        $res = json_decode(HttpCurl::curlRequest($url, $params, 'POST'), true);
        if ($res['code'] == 200) {
            return $res;
        } else {
            return ['code' => $res['code'], 'message' => $res['message']];
        }
    }
    /**
     * 修改频道信息
     */

    public static function editChannels($channelId, $data)
    {
        $params = [
            'appId' => self::$APP_ID,
            'timestamp' => time() * 1000,
            'channelId' => $channelId,
        ];
        $params['sign'] = static::getSign($params, self::$APP_SECRET);
        $url = "http://api.polyv.net/live/v3/channel/basic/update?appId=" . self::$APP_ID . "&timestamp=" . (time() * 1000) . "&channelId=$channelId&sign=" . $params['sign'];
        $res = json_decode(HttpCurl::curlRequest($url, $data, 'POST'), true);
        if ($res['code'] == 200) {
            return $res['data'];
        } else {
            return ['code' => $res['code'], 'message' => $res['message']];
        }
    }

    /**
     * 获取频道信息
     */

    public static function getChannels($channelId)
    {
        $params = [
            'appId' => self::$APP_ID,
            'timestamp' => time() * 1000,
        ];
        $params['sign'] = static::getSign($params, self::$APP_SECRET);
        $url = "http://api.polyv.net/live/v2/channels/$channelId/get?appId=" . self::$APP_ID . '&timestamp=' . time() * 1000;
        $res = json_decode(HttpCurl::curlRequest($url), true);
        if ($res['code'] == 200) {
            return json_decode($res, true)['data'];
        } else {
            return ['code' => $res['code'], 'message' => $res['message']];
        }
    }

    //获取sign函数
    public static function getSign($params, $appSecret)
    {
        // 1. 对加密数组进行字典排序
        foreach ($params as $key => $value) {
            $arr[$key] = $key;
        }
        sort($arr);
        $str = $appSecret;
        foreach ($arr as $k => $v) {
            $str = $str . $arr[$k] . $params[$v];
        }
        $restr = $str . $appSecret;
        $sign = strtoupper(md5($restr));
        return $sign;
    }
}