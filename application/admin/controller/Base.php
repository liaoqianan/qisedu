<?php

namespace app\admin\controller;

use app\admin\model\AdminAuthGroup;
use app\common\Aes;
use think\Controller;
use think\Request;
use app\admin\model\AdminMenu;
use app\admin\model\AdminUser;
use app\admin\model\AdminUserAction;
use service\JsonService;

class Base extends Controller {

    protected $userInfo;
    protected $allMenu;
    protected $uid;

    private $url;
    private $menuInfo;

    public function _initialize(){
        //初始化系统
        $aes = new Aes(Config('aes'));
        $this->uid = $aes->aesDe(cookie('uid'));
        $this->assign('uid', $this->uid);
        $this->iniSystem();

        //控制器初始化
        if(method_exists($this, 'myInit')){
            $this->myInit();
        }
    }

    /**
     * 自定义初始化函数
     */
    public function myInit(){}

    /**
     * Ajax正确返回，自动添加debug数据
     * @param $msg
     * @param array $data
     * @param int $code
     */
    public function ajaxSuccess( $msg, $code = 1, $data = array() ){
        $returnData = array(
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        );
        if( !empty($this->debug) ){
            $returnData['debug'] = $this->debug;
        }
        return json($returnData);
    }

    /**
     * Ajax错误返回，自动添加debug数据
     * @param $msg
     * @param array $data
     * @param int $code
     */
    public function ajaxError( $msg, $code = 0, $data = array() ){
        $returnData = array(
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        );
        if( !empty($this->debug) ){
            $returnData['debug'] = $this->debug;
        }

        return json($returnData);
    }

    /**
     * 将二维数组变成指定key
     * @param $array
     * @param $keyName
     * @return array
     */
    protected function buildArrByNewKey($array, $keyName = 'id') {
        $list = array();
        foreach ($array as $item) {
            $list[$item[$keyName]] = $item;
        }
        return $list;
    }

    private function iniSystem() {
        $request = Request::instance();
        $this->url = $request->controller() .'/' . $request->action();
        if($this->url == '')
        {
            $this->url = $request->controller() .'/' . $request->action();
        }
        if ($request->controller() != 'Login') {
            $menuModel = new AdminMenu();
            $this->allMenu = $menuModel->getMenuExcludeById('135');
            $this->allMenu = $this->allMenu->toArray();
            $this->menuInfo = $menuModel->getOneByData(['url'=>['like', '%'.$this->url.'%']]);
            if (empty($this->menuInfo)) {
                if ($request->isAjax()) {
                    $this->ajaxError('当前URL非法')->send();
                    exit;
                } else {
                    if (!$this->menuInfo['nickname'] && !$this->menuInfo['url']){
                        echo '请先新增菜单URL';
                        exit;
                    }
                    $this->errorLog();
                    //$this->error(lang('没有权限'), 'Login/ruleTip');
                }
            }
            $this->isForbid();
            $this->checkLogin();
            $this->checkRule();
            $this->iniLog();
        }
    }

    /**
     * 封号，或者封IP等特殊需求才用到的
     * @return bool
     */
    private function isForbid() {
        if (isset($this->uid) && !empty($this->uid)) {
            $userModel = new AdminUser();
            $userInfo = $userModel->getOneByData(['id'=>$this->uid]);
            if (!$userInfo['status']) {
                $this->error(lang("此账号已禁用！"),'Login/index');
            }
        }
    }

    /**
     * 
     * 检测登录
     * 
     */
    private function checkLogin() {
        $request = Request::instance();
        if (isset($this->uid) && !empty($this->uid)) {
            $aes = new Aes(Config('aes'));
            $sidNow = $aes->aesDe(cookie('uid'));
            $sidOld = cache('platform_'.cookie('uid'));

            if (isset($sidOld) && !empty($sidOld)) {
                if ($sidOld != $sidNow) {
                    $this->error("您的账号在别的地方登录了，请重新登录！", 'Login/index');
                } else {
                    cache('platform_'.cookie('uid'), $sidNow, 1800);
                    $userModel = new AdminUser();
                    $authGroupModel = new AdminAuthGroup();
                    $this->userInfo = $userInfo = $userModel->getOneByData(['id'=>$this->uid]);
                    $this->userInfo['auth_name'] = $authGroupModel::where('id',$this->userInfo['groupId'])->value('name');
                    $this->assign('userInfo', $this->userInfo);
                }
            } else {
                if ($request->isAjax()) {
                    $this->error("登录超时，请重新登录！", 'Login/index');
                }else{
                    exit('<script>top.location.href="/Login/index"</script>');
                }
            }
        } else {
            if ($request->isAjax()) {
                $this->error("由于您长时间未操作，请重新登录", 'Login/index');
            }else{
                exit('<script>top.location.href="/Login/index"</script>');
            }
        }

    }

    /**
     * 
     * 检测权限
     * 
     */
    private function checkRule() {
        $isAdmin = isAdministrator();
        if ($isAdmin) {
            return true;
        } else {
            $authObj = new Auth();
            $check = $authObj->check(strtolower($this->url), $this->uid);
            if (!$check) {
                //修改没有权限时的跳转页面
                if (strtolower($this->url) == 'index/index'){ //登录时没有首页的权限直接返回登录页面
                    $this->error(lang('没有权限'), 'Login/index');
                } else {
                    $request = Request::instance();
                    if($request->isAjax()){
                        $this->errorLog();
                        $this->ajaxError(lang('没有权限'))->send();
                        exit;
                    }else{
                    	if (strtolower($this->url) != 'upload/index') {
                            $this->errorLog();
                    		$this->error(lang('没有权限'), 'Login/ruleTip');
                    	}
                    }

                }
            }
        }
    }

    /**
     * 
     * 根据菜单级别进行区别Log记录，当然，如果有更加细节的控制，也可以在这个函数内实现
     * 
     */
    private function iniLog() {
    	if (strtolower($this->url) == "upload/index") {
	        $data = array(
	            'actionName' => $this->menuInfo['name'],
	            'uid' => $this->uid,
	            'nickname' => $this->userInfo['nickname'],
	            'addTime' => time(),
	            'url' => $this->menuInfo['url'],
	        	'data' => json_encode(['s'=>'文件上传']),
	        );
    	} else {
	        $data = array(
	            'actionName' => $this->menuInfo['name'],
	            'uid' => $this->uid,
	            'nickname' => $this->userInfo['nickname'],
	            'addTime' => time(),
	            'url' => $this->menuInfo['url'],
	            'data' => json_encode($_REQUEST)
	        );    		
    	}
    	$actionModel = new AdminUserAction();
        $actionModel->saveData($data);
    }


    /**
     * 
     * 根据菜单级别进行区别Log记录
     * 存入非法记录
     */
    private function errorLog() {
        if (strtolower($this->url) == "upload/index") {
            $data = array(
                'actionName' => $this->menuInfo['name'],
                'uid' => $this->uid,
                'nickname' => $this->userInfo['nickname'],
                'addTime' => time(),
                'url' => $this->menuInfo['url'],
                'data' => json_encode(['s'=>'文件上传']),
                'error' => 1,
            );
        } else {
            $data = array(
                'actionName' => $this->menuInfo['name'],
                'uid' => $this->uid,
                'nickname' => $this->userInfo['nickname'],
                'addTime' => time(),
                'url' => $this->menuInfo['url'],
                'data' => json_encode($_REQUEST),
                'error' => 1,
            );          
        }
        $actionModel = new AdminUserAction();
        $actionModel->saveData($data);
    }

        /**
     * 错误提醒页面
     * @param string $msg
     * @param int $url
     */
    protected function failed($msg = '哎呀…亲…您访问的页面出现错误', $url = 0)
    {
        if($this->request->isAjax()){
            exit(JsonService::fail($msg,$url)->getContent());
        }else{
            $this->assign(compact('msg','url'));
            exit($this->fetch('Public/error'));
        }
    }

}
