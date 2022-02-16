<?php

namespace app\admin\controller;

use app\admin\model\Withdraw;
use app\admin\model\Goods;
use app\admin\model\Order;
use app\admin\model\User;
use app\admin\model\SupplierUser;

class Index extends Base {
	/**
	 * 
	 * 首页
	 * 
	 */
    public function index() {
        $isAdmin = isAdministrator();
        $list = array();
        $menuAll = $this->allMenu;

        foreach ($menuAll as $menu) {
            if ($menu['hide'] == 0) {
                if ($isAdmin) {
                    $menu['url'] = $menu['url'] ? url($menu['url']) : '';
                    $list[] = $menu;
                } else {
                    $authObj = new Auth();
                    $authList = $authObj->getAuthList($this->uid);
                    if (in_array(strtolower($menu['url']), $authList) || $menu['url'] == '') {
                        $menu['url'] = $menu['url'] ? url($menu['url']) : '';
                        $list[] = $menu;
                    }
                }
            }
        } ;

        $list = listToTree($list);
        foreach ($list as $key => $item) {
            if(empty($item['_child']) && empty($item['url'])){
            //if(empty($item['_child'])){
                unset($list[$key]);
            }
        }
        $list = formatTree($list);
        return $this->fetch("Index/index",["list"=>$list, 'auth_name'=>$this->userInfo['auth_name'], 'username'=>$this->userInfo['username'], 'nickname'=>$this->userInfo['nickname']]);
    }
    
    /**
     * 
     * 欢迎页面
     * 
     */
    public function welcome(){
        exit('欢迎进入起色后台');
    }
}
