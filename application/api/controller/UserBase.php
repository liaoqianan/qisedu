<?php
/*
 * 需要登陆授权的接口，继承这个类UserBase
 */
namespace app\api\controller;

use app\api\controller\Base;
use service\JsonService;
use think\Cache;
use think\Request;

class UserBase extends Base {
    public $uid = 0 ;
    public $userInfo = [] ;

    public function __construct() {
        parent::__construct();
        $header = Request::instance()->header();

        //db('get_code')->insert(['code'=>'id：'.$header['uid'],'time'=>date('Y-m-d H:i:s',time())]);
        if(empty($header['uid']))
        {
            ajaxError('请登录', -200)->send();
            exit;
        }else{
            $this->uid = $header['uid'];
        }
        $info = model('User')->where(array('id'=>$this->uid))->find();
        if (empty($info)){
            ajaxError('用户不存在', -901)->send();
            exit;
        }
        if ($info['status'] != 0){
            ajaxError('账号已被禁用', -903)->send();
            exit;
        }

        //校验token合法性
        if(empty($header['token']))
        {
            ajaxError('TOKEN信息不存在', -904)->send();
            exit;
        }

        $accessToken = Cache::get("TOKEN_".$this->uid);

        if(empty($accessToken))
        {
            ajaxError('请获取TOKEN信息', -905)->send();
            exit;
        }

        if($accessToken != $header['token'])
        {
            ajaxError('TOKEN失效，请重新获取', -906)->send();
            exit;
        }

        $this->userInfo = $info;
    }
}
