<?php
/**
 * Created by PhpStorm.
 * User: design_02
 * Date: 2020/12/15
 * Time: 11:38
 */

if (is_file('../../../../../vendor/autoload.php')) {
    require_once ('../../../../../vendor/autoload.php');
}
use OSS\OssClient;
use OSS\Core\OssException;

/**
 * Notes: 阿里云配置Ueditor上传
 * Created by assasin.
 * Date: 2019/12/27
 * Time: 15:53
 * Request-Method: POST+AES
 */
class Oss
{
    public function __construct(){

    }

    /**
     * Notes: 阿里云配置Ueditor上传
     * Created by assasin.
     * Request-Method: POST+AES
     */
    function uploadToAliOSS($file,$fullName){
        if ($fullName[0] == '/'){
            $fullName = substr($fullName, 1);
        }
        $accessKeyId = 'LTAI4GEjCZSiaDeiuFKHdoFQ';//涉及到隐私就不放出来了
        $accessKeySecret = 'c6vlo16Y585FFMPMs7A2MMd812GxB5';//涉及到隐私就不放出来了
        $endpoint = 'oss-cn-zhangjiakou.aliyuncs.com';//节点
        $bucket= 'aidazhoubian';//" <您使用的Bucket名字，注意命名规范>";
        $object = $fullName;//" <您使用的Object名字，注意命名规范>";
        $content = $file["tmp_name"];//上传的文件
        try {
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $ossClient->setTimeout(3600 /* seconds */);
            $ossClient->setConnectTimeout(10 /* seconds */);
            //$ossClient->putObject($bucket, $object, $content);
            // 先把本地的example.jpg上传到指定$bucket, 命名为$object
            $ossClient->uploadFile($bucket, $object, $content);
            $signedUrl = $ossClient->signUrl($bucket, $object);
            $path = explode('?',$signedUrl)[0];
            $obj['status'] = true;
            $obj['path'] = $path;
        } catch (OssException $e) {
            $obj['status'] = false;
            $obj['path'] = "";
            print $e->getMessage();
        }
        return $obj;
    }
}