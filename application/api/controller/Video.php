<?php
/**
 * Created by PhpStorm.
 * User: design_02
 * Date: 2020/12/15
 * Time: 15:42
 */

namespace app\api\controller;

require_once '../vendor/aliyun-php-sdk/aliyun-php-sdk-core/Config.php';

use vod\Request\V20170321 as vod;

class Video extends Base
{
    public function index($videoId)
    {
        // 注意捕获异常
        try {
            $client = $this->initVodClient(config('aliyun_oss.AccessKey_ID'),config('aliyun_oss.AccessKey_Secret'));
            $playInfo = $this->getPlayInfo($client, $videoId);
            return $playInfo;
        } catch (Exception $e) {
            print $e->getMessage()."\n";
        }
        /**

        try {
        $client = $this->initVodClient(config('aliyun_oss.AccessKey_ID'), config('aliyun_oss.AccessKey_Secret'));

        $result = $this->addWatermark($client);
        var_dump($result);
        } catch (Exception $e) {
        print $e->getMessage()."\n";
        }
         */
    }
    //获取视频
    function getPlayInfo($client, $videoId) {
        $request = new vod\GetPlayInfoRequest();
        $request->setVideoId($videoId);
        $request->setAuthTimeout(3600*24);
        $request->setAcceptFormat('JSON');
        return $client->getAcsResponse($request);
    }
    //初始化
    function initVodClient($accessKeyId, $accessKeySecret) {

        $regionId = 'cn-shanghai';  // 点播服务接入区域
        $profile = \DefaultProfile::getProfile($regionId, $accessKeyId, $accessKeySecret);
        return new \DefaultAcsClient($profile);
    }
    /**
     * 构建图片水印的配置数据，根据具体设置需求修改对应的参数值
     * @return
     */
    function buildImageWatermarkConfig() {
        $watermarkConfig = array();
        //水印的横向偏移距离
        $watermarkConfig["Dx"] = "8";
        //水印的纵向偏移距离
        $watermarkConfig["Dy"] = "8";
        //水印显示的宽
        $watermarkConfig["Width"] = "55";
        //水印显示的高
        $watermarkConfig["Height"] = "55";
        //水印显示的相对位置(左上、右上、左下、右下)
        $watermarkConfig["ReferPos"] = "BottomRight";

        //水印显示的时间线(开始显示和结束显示时间)
        $timeline = array();
        //水印开始显示时间
        $timeline["Start"] = "2";
        //水印结束显示时间
        $timeline["Duration"] = "ToEND";
        $watermarkConfig["Timeline"] = $timeline;

        return json_encode($watermarkConfig);
    }

    /**
     * 构建文字水印的配置数据，根据具体设置需求修改对应的参数值
     * @return
     */
    function buildTextWatermarkConfig() {
        $watermarkConfig = array();
        //文字水印显示的内容
        $watermarkConfig["Content"] = "ceshi";
        //文字水印的字体名称
        $watermarkConfig["FontName"] = "SimSun";
        //文字水印的字体大小
        $watermarkConfig["FontSize"] = "25";
        //文字水印的颜色(也可为RGB颜色取值，例如:#000000)
        $watermarkConfig["FontColor"] = "Black";
        //文字水印的透明度
        $watermarkConfig["FontAlpha"] = "0.2";
        //文字水印的字体描边颜色(也可为RGB颜色取值，例如:#ffffff)
        $watermarkConfig["BorderColor"] = "White";
        //文字水印的描边宽度
        $watermarkConfig["BorderWidth"] = "1";
        //文字水印距离视频画面上边的偏移距离
        $watermarkConfig["Top"] = "20";
        //文字水印距离视频画面左边的偏移距离
        $watermarkConfig["Left"] = "15";

        return json_encode($watermarkConfig);
    }

    /**
     * 添加水印配置信息函数
     */
    function addWatermark($client) {
        $request = new vod\AddWatermarkRequest();
        //水印名称
        $request->setName("啊哈哈");

        //水印文件在oss的URL
        //图片水印必传图片文件的oss文件地址，水印文件必须和视频在同一个区域，例如:华东2视频，水印文件必须存放在华东2
        $request->setFileUrl('');

        //水印配置数据
        //图片水印的位置配置数据
        $request->setWatermarkConfig($this->buildImageWatermarkConfig());
        //文字水印的位置配置数据
        //$request->setWatermarkConfig(buildTextWatermarkConfig());
        //文字水印:Text; 图片水印:Image
        $request->setType("Text");
        return $client->getAcsResponse($request);
    }
}