<?php
/**
 * Created by PhpStorm.
 * User: design_02
 * Date: 2020/12/15
 * Time: 15:42
 */

namespace app\admin\controller;

require_once '../vendor/aliyun-php-sdk/aliyun-php-sdk-core/Config.php';


use OSS\Core\OssException;
use OSS\OssClient;
use think\Controller;
use vod\Request\V20170321 as vod;

class Video extends Controller
{
    public function index($videoId)
    {
        // 注意捕获异常
        try {
            $client = $this->initVodClient(config('aliyun_oss.AccessKey_ID'), config('aliyun_oss.AccessKey_Secret'));
            $playInfo = $this->getPlayInfo($client, $videoId);
            return $playInfo;
        } catch (Exception $e) {
            print $e->getMessage() . "\n";
        }
    }

    //获取上传地址和凭证
    function createUploadVideo($client, $file)
    {
        $request = new vod\CreateUploadVideoRequest();
        $request->setTitle($file['name']);
        $request->setFileName($file['name']);
        //$request->setCoverURL("http://192.168.0.0/16/tps/TB1qnJ1PVXXXXXCXXXXXXXXXXXX-700-700.png");

        $request->setAcceptFormat('JSON');
        return $client->getAcsResponse($request);
    }

    //使用上传凭证和地址初始化OSS客户端（注意需要先Base64解码并Json Decode再传入）
    function init_oss_client($uploadAuth, $uploadAddress)
    {
        $ossClient = new OssClient($uploadAuth['AccessKeyId'], $uploadAuth['AccessKeySecret'], $uploadAddress['Endpoint'],
            false, $uploadAuth['SecurityToken']);
        $ossClient->setTimeout(86400 * 7);    // 设置请求超时时间，单位秒，默认是5184000秒, 建议不要设置太小，如果上传文件很大，消耗的时间会比较长
        $ossClient->setConnectTimeout(10);  // 设置连接超时时间，单位秒，默认是10秒
        return $ossClient;
    }

    //上传本地文件
    function upload_local_file($ossClient, $uploadAddress, $localFile)
    {
        return $ossClient->uploadFile($uploadAddress['Bucket'], $uploadAddress['FileName'], $localFile);
    }
    //刷新上传凭证
    function refresh_upload_video($vodClient, $videoId)
    {
        $request = new vod\RefreshUploadVideoRequest();
        $request->setVideoId($videoId);
        return $vodClient->getAcsResponse($request);
    }

    function add()
    {
//执行完整流程（注意捕获异常）：
        $accessKeyId = config('aliyun_oss.AccessKey_ID');                    // 您的AccessKeyId
        $accessKeySecret = config('aliyun_oss.AccessKey_Secret');            // 您的AccessKeySecret
        $file = $_FILES['file'];
        $localFile = $file['tmp_name'];   // 需要上传到VOD的本地视频文件的完整路径
        try {
            // 初始化VOD客户端并获取上传地址和凭证
            $vodClient = $this->initVodClient($accessKeyId, $accessKeySecret);
            $createRes = $this->createUploadVideo($vodClient,$file);
            // 执行成功会返回VideoId、UploadAddress和UploadAuth
            $videoId = $createRes->VideoId;
            $uploadAddress = json_decode(base64_decode($createRes->UploadAddress), true);
            $uploadAuth = json_decode(base64_decode($createRes->UploadAuth), true);
            // 使用UploadAuth和UploadAddress初始化OSS客户端
            $ossClient = $this->init_oss_client($uploadAuth, $uploadAddress);
            // 上传文件，注意是同步上传会阻塞等待，耗时与文件大小和网络上行带宽有关
            //$result = upload_local_file($ossClient, $uploadAddress, $localFile);
            $result = $this->upload_local_file($ossClient, $uploadAddress, $localFile);
            //printf("Succeed, VideoId: %s", $videoId);
            return ['type'=>1,'parameter'=>$videoId,'url'=>$result['info']['url']];
        } catch (\Exception $e) {
            // var_dump($e);
            printf("Failed, ErrorMessage: %s", $e->getMessage());

        }
    }

   /* public function add()
    {
        try {
            $client = $this->initVodClient(config('aliyun_oss.AccessKey_ID'), config('aliyun_oss.AccessKey_Secret'));
            $file = $_FILES['file'];
            $uploadInfo = $this->createUploadVideo($client, $file);

            //$refreshInfo = $this->refreshUploadVideo($client, $uploadInfo->VideoId);
            dump($uploadInfo);
        } catch (Exception $e) {
            print $e->getMessage() . "\n";
        }
    }*/

    /**
     * 刷新视频上传凭证
     * @param client 发送请求客户端
     * @return RefreshUploadVideoResponse 刷新视频上传凭证响应数据
     */
    function refreshUploadVideo($client, $videoId)
    {
        $request = new vod\RefreshUploadVideoRequest();
        $request->setVideoId($videoId);
        $request->setAcceptFormat('JSON');
        return $client->getAcsResponse($request);
    }


    //获取视频
    function getPlayInfo($client, $videoId)
    {
        $request = new vod\GetPlayInfoRequest();
        $request->setVideoId($videoId);
        $request->setAuthTimeout(3600 * 24);
        $request->setAcceptFormat('JSON');
        return $client->getAcsResponse($request);
    }

    //初始化
    function initVodClient($accessKeyId, $accessKeySecret)
    {

        $regionId = 'cn-shanghai';  // 点播服务接入区域
        $profile = \DefaultProfile::getProfile($regionId, $accessKeyId, $accessKeySecret);
        return new \DefaultAcsClient($profile);
    }

    /**
     * 构建图片水印的配置数据，根据具体设置需求修改对应的参数值
     * @return
     */
    function buildImageWatermarkConfig()
    {
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
    function buildTextWatermarkConfig()
    {
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
    function addWatermark($client)
    {
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