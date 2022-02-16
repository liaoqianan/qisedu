<?php

namespace app\admin\controller;

use app\admin\model\AdminUser;
use app\common\Aes;
use think\Cookie;

class Login extends Base {

	/**
	 * 
	 * 登陆页面
	 * 
	 */
    public function index() {
    	if ($this->request->isGet()) {
        	return $this->fetch("Login/index");
    	}
    }

    /**
     * 
     * 提交登陆
     * 
     */
    public function login() {
    	if ($this->request->isPost()) {
	    	$user = input('post.username', '', 'trim');
	    	$pass = input('post.password', '', 'trim');

            $challenge = input('post.geetest_challenge');
            $validate = input('post.geetest_validate');
            if(!$challenge || md5($challenge) != $validate){
                return $this->ajaxError('请先通过验证！');
            }

	    	if (preg_match("/([\x81-\xfe][\x40-\xfe])/", $pass)) {	
	    		return $this->ajaxError('登录不能含有中文');
	    	}
//            if(!preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,18}$/',$pass)){
//                return $this->ajaxError('密码必须为8-18位的数字和字母的组合');
//            }

	    	$userModel = new AdminUser();
	    	$userInfo = $userModel->getOneByData(['username'=>$user, 'password'=>user_md5($pass)]);
	
	        if (!empty($userInfo)) {
	            if ($userInfo['status']) {
	
	                //保存用户信息和登录凭证
                    $aes = new Aes(Config('aes'));
                    $uid = $aes->aesEn($userInfo['id']);
	                cache('platform_'.$uid, $userInfo['id'], 1800);
	                cookie('uid', $uid);
	
	                //更新用户数据
	                $ip = $this->request->ip();
	
	                $data['login_count'] = $userInfo['login_count'] + 1;
	                $data['last_ip'] = $ip;
	                $data['last_login'] = time();
	                $userModel->saveDataByWhere($data, ['id'=>$userInfo['id']]);
	
	                return $this->ajaxSuccess('登录成功');
	            } else {
	                return $this->ajaxError('用户已被封禁，请联系管理员');
	            }
	        } else {
	            return $this->ajaxError('用户名密码不正确');
	        }
    	}
    }

    /**
     * 
     * 退出登陆
     * 
     */
    public function logOut() {
    	if ($this->request->isGet()) {
	        cache('platform_'.cookie('uid'), null);
	        Cookie::delete('uid');
	        $this->redirect('Login/index');
    	}
    }

    /**
     * 
     * 修改个人信息
     * 
     */
    public function changeUser() {
        if ($this->request->isPost()) {
            $nickname = input('post.nickname', '', 'trim');
            $password = input('post.password', '', 'trim');

            if ($password != '') {
	            if (preg_match("/([\x81-\xfe][\x40-\xfe])/", $password)) {	
		    		return $this->ajaxError('密码不能含有中文');
		    	}
                if(!preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,18}$/',$password)){
                    return $this->ajaxError('密码必须为8-18位的数字和字母的组合');
                }
            }

            $userModel = new AdminUser();
            $aes = new Aes(Config('aes'));
            $uid = $aes->aesDe(cookie('uid'));
            $row = $userModel->getOneByData(['id'=>$uid]);
            
            $newData = array();
            if (!empty($nickname)) {
                $newData['nickname'] = $nickname;
            }
                        
            if ($row['updateTime']==0) {
                $newData['password'] = empty($password) ? user_md5('123456') : user_md5($password);
                $newData['updateTime'] = time();
            } else {
            	if (!empty($password)) {
            		$newData['password'] = user_md5($password);
                	$newData['updateTime'] = time();
            	}
            }

            $aes = new Aes(Config('aes'));
            $uid = $aes->aesDe(cookie('uid'));
            $res = $userModel->saveDataByWhere($newData, ['id'=>$uid]);

            if ($res === false) {
                return $this->ajaxError('修改失败');
            } else {
                return $this->ajaxSuccess('修改成功');
            }
        } else {

            $userModel = new AdminUser();
            $aes = new Aes(Config('aes'));
            $uid = $aes->aesDe(cookie('uid'));
            $userInfo = $userModel->getOneByData(['id'=>$uid]);
            return $this->fetch("Login/add",["uname"=>$userInfo['username']]);
        }
    }

    /**
     * 
     * 权限提示页
     * 
     */
    public function ruleTip(){
    	if ($this->request->isGet()) {
	        $this->display();
	        return $this->fetch("Login/ruleTip");
    	}
    }

}
