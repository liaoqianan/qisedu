<?php

namespace app\admin\controller;

use app\admin\model\AdminUser;
use app\admin\model\AdminAuthGroup;
use app\admin\model\AdminAuthRule;
use app\admin\model\AdminMenu;

class Role extends Base {

	/**
	 *
	 * 列表
	 *
	 */
    public function index() {
    	if ($this->request->isGet()) {
    	    $groupModel = new AdminAuthGroup();
    	    $listInfo = $groupModel->getAll();
	        return $this->fetch("Role/index", ['list'=>$listInfo]);
    	}
    }

    /**
     *
     * 添加
     *
     */
    public function add() {
        if ($this->request->isPost()) {
            $postData = input('post.');
            $groupModel = new AdminAuthGroup();
            $row = $groupModel->getOneByData(['name'=>trim($postData['name'])]);
            if ($row) {
            	return $this->ajaxError(trim($postData['name']).'已存在');
            }
            $groupModel->saveData(['name'=>trim($postData['name']), 'description'=>trim($postData['description'])]);
            $groupId =$groupModel->getLastInsID();

            $needAdd = [];
            if(!empty($postData['rule']))
            {
                foreach ($postData['rule'] as $key => $value) {
                    if (!empty($value)) {
                        $data['url'] = $value;
                        $data['groupId'] = $groupId;
                        $needAdd[] = $data;
                    }
                }
            }
            if (count($needAdd)) {
                $roleModel = new AdminAuthRule();
                $roleModel->saveAllData($needAdd);
            }

            if ($groupId > 0) {
                return $this->ajaxSuccess('添加成功');

            } else {
                return $this->ajaxError('添加失败');
            }
        } else {
            $menuModel = new AdminMenu();
            $originList = $menuModel->getAll();
            $list = listToTree($originList);

            return $this->fetch("Role/add", ['list'=>$list]);
        }
    }

    /**
     * 
     * 编辑
     * 
     */
    public function edit() {
        if ($this->request->isGet()) {
            $id = input('get.id', '0', 'trim');
            $groupModel = new AdminAuthGroup();
            $detail = $groupModel->getOneByData(['id'=>$id]);

            $ruleModel = new AdminAuthRule();
            $has = $ruleModel->getListByWhere(['groupId'=>$id]);
            $hasRule = array_column($has, 'url');
            $menuModel = new AdminMenu();
            $originList = $menuModel->getAll();
            $list = listToTree($originList);

            return $this->fetch("Role/add", ['detail'=>$detail, 'hasRule'=>$hasRule, 'list'=>$list]);
        } elseif ($this->request->isPost()) {

            $postData = input('post.');
            $groupId = $postData['id'];

            $groupModel = new AdminAuthGroup();
            $row = $groupModel->getOneByData(['id'=>$groupId]);
            if ($row['name'] != trim($postData['name'])) {
                $count = $groupModel->getCountByWhere(['name'=>trim($postData['name'])]);
            	if ($count) {
            		return $this->ajaxError(trim($postData['name']).'已存在');
            	}
            }

            $res = $groupModel->saveDataByWhere(['name'=>trim($postData['name']), 'description'=>trim($postData['description'])], ['id'=>$groupId]);

            $needAdd = [];
            $ruleModel = new AdminAuthRule();
            $has = $ruleModel->getListByWhere(['groupId'=>$groupId]);
            $hasRule = array_column($has, 'url');
            $needDel = array_flip($hasRule);
            if(!empty($postData['rule']))
            {
                foreach ($postData['rule'] as $key => $value) {
                    if (!empty($value)) {
                        if (!in_array($value, $hasRule)) {
                            $data['url'] = $value;
                            $data['groupId'] = $groupId;
                            $needAdd[] = $data;
                        } else {
                            unset($needDel[$value]);
                        }
                    }
                }
            }
            if (count($needAdd)) {
                $ruleModel = new AdminAuthRule();
                $ruleModel->saveAllData($needAdd);
            }
            if (count($needDel)) {
                $urlArr = array_keys($needDel);
                $ruleModel = new AdminAuthRule();
                $ruleModel->delByWhere(['groupId'=>$groupId, 'url'=>['in', $urlArr]]);
            }

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
     * 删除
     * 
     */
    public function del() {
		if ($this->request->isPost()) {
	    	$id = input('post.id', '0', 'trim');
	        if ($id == cache('ADMIN_GROUP')) {
	            $this->error('没有权限');
	        }

	        $userModel = new AdminUser();
	        $user = $userModel->getOneByData(['groupId'=>$id]);
	        if(!empty($user))
	        {
	            return $this->ajaxError('该角色下有归属人员，无法删除');
	        }

	        $groupModel = new AdminAuthGroup();
	        $res = $groupModel->delByWhere(['id'=>$id]);
	        $ruleModel = new AdminAuthRule();
	        $res = $ruleModel->delByWhere(['groupId'=>$id]);
	        if ($res === false) {
	            return $this->ajaxError('删除失败');
	        } else {
	            return $this->ajaxSuccess('删除成功');
	        }
		}
    }

}