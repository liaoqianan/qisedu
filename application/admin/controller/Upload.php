<?php
namespace app\admin\controller;

use app\admin\model\AdminUser;
use app\admin\model\AdminAuthRule;
use service\JsonService;
use service\OSSUploadService as OSSUpload;
use OSS\OssClient;
use OSS\Core\OssException;
class Upload extends Base{
    /**
	 * 
	 * 单图上传
	 * 
	 */
    public function index()
    {
        if(request()->has('base64', 'post')){
            $data = $_POST['base64'];
            $result = $this->new_base64_upload($data);
            if ($result['code']){
                $fileResult = $result['data'];
                $filePath = $fileResult['path'] . $fileResult['name'];
                $ossFileName = implode('/', ['upload/image',date('Ymd'),$fileResult['name']]);
                try {
                    $config = config('aliyun_oss');
                    //实例化对象 将配置传入
                    $ossClient = new OssClient($config['AccessKey_ID'], $config['AccessKey_Secret'], $config['Endpoint']);
                    $result = $ossClient->uploadFile($config['Bucket'], $ossFileName, $filePath);
                    if (empty($result)){
                        $res = [
                            'type'=>1,
                            'msg'=>'success',
                            'image_path'=> ALIYUN.'/'.$filePath,
                        ];
                    }else{
                        $res = [
                            'type'=>0,
                            'msg'=>'上传失败'
                        ];
                    }
                    return $this->ajaxSuccess($res);
                } catch (OssException $e) {
                    return $e->getMessage();
                }
            }
        }else{
            /*获取到上传的文件*/
            //$file = request()->file('file');
            $file = $_FILES['file'];
            if ($file) {
                $name = $file['name'];
                $format = strrchr($name, '.');//截取文件后缀名如 (.jpg)
                /*判断图片格式*/
                $allow_type = ['.jpg', '.jpeg', '.gif', '.bmp', '.png','.mp4','.mp3'];
                if (! in_array($format, $allow_type)) {
                    return $this->ajaxError( "文件格式不在允许范围内哦");

                }
                // 尝试执行
                try {
                    $config = config('aliyun_oss');
                    //实例化对象 将配置传入
                    $ossClient = new OssClient($config['AccessKey_ID'], $config['AccessKey_Secret'], $config['Endpoint']);
                    //这里是有sha1加密 生成文件名 之后连接上后缀
                    $fileName = 'uplaod/image/' . date("Ymd") . '/' . sha1(date('YmdHis', time()) . uniqid()) . $format;
                    //执行阿里云上传
                    $result = $ossClient->uploadFile($config['Bucket'], $fileName, $file['tmp_name']);
                    if (!empty($result)){
                        $res = [
                            'type'=>1,
                            'msg'=>'success',
                            'image_path'=> ALIYUN.'/'.$fileName,
                        ];
                    }else{
                        $res = [
                            'type'=>0,
                            'msg'=>'上传失败'
                        ];
                    }
                    echo json_encode($res);
                } catch (OssException $e) {
                    return $e->getMessage();
                }
                //将结果返回
            }else{
                $res = [
                    'type'=>0,
                    'msg'=>'上传错误'
                ];
                return json_encode($res);
            }
        }
    }
    public function new_base64_upload($base64, $path = '') {
        $data = explode(',',$base64);
        trace($data,'api');
        unset($base64);
        if (count($data) !== 2){
            $this->ajaxError( "文件格式错误");
        }
        if (preg_match('/^(data:\s*image\/(\w+);base64)/', $data[0], $result)){
            $type = $result[2];
            if(!in_array($type,array('jpeg','jpg','gif','bmp','png'))){
                $this->ajaxError( "文件格式不在允许范围内哦");
            }
            $image_name = md5(uniqid()).'.'.$result[2];
            $image_path = "./upload/posts/";
            $image_file = $image_path . $image_name;
            //服务器文件存储路径
            try {
                if (file_put_contents($image_file, base64_decode($data[1]))) {
                    $res = [
                        'type'=>1,
                        'msg'=>'success',
                        'image_path'=> $image_file,
                    ];
                    return json_encode($res);
                } else {
                    $this->ajaxError( "文件保存失败");
                }
            }catch (\Exception $e){
                $msg = $e->getMessage();
                $this->ajaxError($msg);
            }
        }
        return['code'=>400,'msg'=>'文件格式错误'];
    }

    //多图上传
    public function uploadMultiple(){
        $file = $_FILES['files'];
        empty($file) && $this->ajaxError('请选择要上传的文件');
        unset($_FILES['files']);
        $count = count($file['name']);       // 上传图片的数量
        $count > 10 && $this->ajaxError('批量上传图片一次最多上传10张图片');
        $tmpFile  = [];
        $returnData = [];
        for($i=0;$i<$count;$i++)          // 循环处理图片
        {
            $tmpFile['name']   = $file['name'][$i];
            $tmpFile['type']   = $file['type'][$i];
            $tmpFile['tmp_name'] = $file['tmp_name'][$i];
            $tmpFile['error']  = $file['error'][$i];
            $tmpFile['size']   = $file['size'][$i];
            $_FILES['file_'.$i] = $tmpFile;
            // 判断是否是允许的图片类型
            $ext = substr($_FILES['file_'.$i]['name'],strrpos($_FILES['file_'.$i]['name'],'.')+1); // 上传文件后缀
            stripos('jpeg|png|bmp|jpg',$ext) === FALSE && $this->ajaxError('图片格式支持 JPEG、PNG、BMP格式图片');
            $type=explode('/', $tmpFile['type']);
            $file_name=time().rand(11111,99999).'.'.$type['1'];
            $fileInfo=file_get_contents($tmpFile['tmp_name']);
            $object='uploads/image/'.date('Y').'/'.date('m').'/'.date('d').'/'.$file_name;
            // 尝试执行
            try {
                $config = config('aliyun_oss');
                //实例化对象 将配置传入
                $ossClient = new OssClient($config['AccessKey_ID'], $config['AccessKey_Secret'], $config['Endpoint']);
                //这里是有sha1加密 生成文件名 之后连接上后缀
                $fileName = 'uplaod/image/' . date("Ymd") . '/' . sha1(date('YmdHis', time()) . uniqid()) . $ext;
                //执行阿里云上传
                $result = $ossClient->uploadFile($config['Bucket'], $fileName, $fileInfo);

                if(empty($result)){//上传失败
                    $this->ajaxError('第'.($i+1).'张图片上传失败');
                }else{
                    $returnData[$i]  = ALIYUN.'/'.$object;   // 图片路径
                }
                return json_encode($res);
            } catch (OssException $e) {
                return $e->getMessage();
            }


        }
        return $this->ajaxSuccess('',1,$returnData);

    }
}