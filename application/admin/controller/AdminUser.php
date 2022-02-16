<?php

namespace app\admin\controller;

use app\admin\model\AdminUser as UserModel;
use app\admin\model\AdminAuthGroup;

class AdminUser extends Base {

    public function index() {
        $userModel = new UserModel();
        $listInfo = $userModel->alias('a')->field('a.id,a.username,a.nickname,a.status,a.last_login,a.last_ip,a.login_count,b.name')
                            ->join('AdminAuthGroup b', 'a.groupId = b.id', 'left')
                            ->order('a.id', 'desc')
                            ->select();
        foreach ($listInfo as $key => $value) {
            $listInfo[$key]['lastLoginTime'] = $value['last_login'] > 0 ? date('Y-m-d H:i:s', $value['last_login']) : '';
        }

        return $this->fetch("AdminUser/index", ['list'=>$listInfo]);
    }

    /**
     * 
     * 添加
     * 
     */
    public function add() {
        if ($this->request->isPost()) {

            $username = input('post.username', '', 'trim');
            $password = input('post.password', '', 'trim');
            $mobile = input('post.mobile', '', 'trim');
            $groupId = input('post.groupId', '0', 'trim');
            $nickname = input('post.nickname', '', 'trim');

            $userModel = new UserModel();
            $user = $userModel->getOneByData(['username'=>$username]);
            if(!empty($user)){
                return $this->ajaxError('用户已存在');
            }
			if (!is_mobile($mobile)) {
				return $this->ajaxError('手机号码格式不正确');
			}
			$password == '' ? $password = 'wxys2020' : $password;
	
            if (preg_match("/([\x81-\xfe][\x40-\xfe])/", $password)) {	
	    		return $this->ajaxError('用户密码不能含有中文');
	    	}
            if(!preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,18}$/',$password)){
                return $this->ajaxError('密码必须为8-18位的数字和字母的组合');
            }
			$data = [];
	    	$data['username'] = $username;
	    	$data['nickname'] = $nickname;
	    	$data['mobile'] = $mobile;
            $data['password'] = user_md5($password);
            $data['groupId'] = $groupId;
            $data['regIp'] = $_SERVER['REMOTE_ADDR'];
            $data['regTime'] = time();
            $res = $userModel->saveData($data);
            if ($res === false) {
                return $this->ajaxError('操作失败');
            } else {
                return $this->ajaxSuccess('添加成功');
            }
        } else {
            $groupModel = new AdminAuthGroup();
            $roles = $groupModel->getAll();
            return $this->fetch("AdminUser/add", ['roles'=>$roles]);
        }
    }

    /**
     * 
     * 编辑
     * 
     */
    public function edit() {
        if ($this->request->isGet()) {
            $id = input('get.id' ,'0', 'trim');
            $userModel = new UserModel();
            $detail = $userModel->getOneByData(['id'=>$id]);

            $groupModel = new AdminAuthGroup();
            $roles = $groupModel->getAll();

            return $this->fetch("AdminUser/add", ['detail'=>$detail, 'username'=>$detail['username'], 'roles'=>$roles]);
        } elseif ($this->request->isPost()) {

            $id = input('post.id', '0', 'trim');
            $username = input('post.username', '', 'trim');
            $password = input('post.password', '', 'trim');
            $mobile = input('post.mobile', '', 'trim');
            $groupId = input('post.groupId', '0', 'trim');
            $nickname = input('post.nickname', '', 'trim');            
            
            if (!empty($password)) {
	            if (preg_match("/([\x81-\xfe][\x40-\xfe])/", $password)) {	
		    		return $this->ajaxError('用户密码不能含有中文');
		    	}
                if(!preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,18}$/',$password)){
                    return $this->ajaxError('密码必须为8-18位的数字和字母的组合');
                }
            }
            
        	if (!is_mobile($mobile)) {
				return $this->ajaxError('手机号码格式不正确');
			}

            $userModel = new UserModel();
            $user = $userModel->getOneByData(['username'=>$username]);
            if (!empty($user) && $user['id'] != $id){
                return $this->ajaxError('用户已存在');
            }

            $postData = [];
            $postData['username'] = $username;
            $postData['nickname'] = $nickname;
            $postData['mobile'] = $mobile;
            $postData['groupId'] = $groupId;
            $password != '' ? $postData['password'] = user_md5($password) : '';
            $res = $userModel->saveDataByWhere($postData, ['id'=>$id]);

            if ($res === false) {
                return $this->ajaxError('修改失败');
            } else {
                return $this->ajaxSuccess('修改成功');
            }
        } else {
            return $this->ajaxError('非法操作');
        }
    }

    /**
     * 
     * 停用
     * 
     */
    public function close() {
    	if ($this->request->isPost()) {
	        $id = input('post.id', '0', 'trim');
	        $isAdmin = isAdministrator($id);
	        if ($isAdmin) {
	            return $this->ajaxError('超级管理员不可以被操作');
	        }
	        $userModel = new UserModel();
	        $res = $userModel->saveDataByWhere(['status' => 0], ['id' => $id]);
	        if ($res === false) {
	            return $this->ajaxError('操作失败');
	        } else {
	            return $this->ajaxSuccess('操作成功');
	        }
    	}
    }

    /**
     * 
     * 启用
     * 
     */
    public function open() {
    	if ($this->request->isPost()) {
	        $id = input('post.id', '0', 'trim');
	        $isAdmin = isAdministrator($id);
	        if ($isAdmin) {
	            return $this->ajaxError('超级管理员不可以被操作');
	        }
            $userModel = new UserModel();
            $res = $userModel->saveDataByWhere(['status' => 1], ['id' => $id]);
	        if ($res === false) {
	            return $this->ajaxError('操作失败');
	        } else {
	            return $this->ajaxSuccess('操作成功');
	        }
    	}
    }

    /**
     * 
     * 删除
     * 
     */
    public function del() {
    	if ($this->request->isPost()) {
    	    $id = input('post.id', '0', 'trim');
	        $isAdmin = isAdministrator($id);
	        if ($isAdmin) {
	            return $this->ajaxError('超级管理员不可以被操作');
	        }

            $userModel = new UserModel();
            $res = $userModel->delByWhere(['id'=>$id]);
	        if ($res === false) {
	            return $this->ajaxError('操作失败');
	        } else {
	            return $this->ajaxSuccess('操作成功');
	        }    		
    	}

    }
}