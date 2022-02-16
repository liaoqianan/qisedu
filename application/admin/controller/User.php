<?php

namespace app\admin\controller;

use app\admin\model\ChangeUserRefer;
use app\admin\model\User as UserModel;
use app\common\Common;
use service\PHPExcelService;
use app\admin\model\UserVipUp;
class User extends Base {
	
	/**
	 * 
	 * 列表
	 * 
	 */

	public function index() {
		if ($this->request->isGet()) {
			return $this->fetch("User/index");
		}
	}
	
	/**
	 * 
	 * AJAX查询列表
	 * 
	 */
	public function ajaxGetIndex() {
		if ($this->request->isGet()) {
	        $start = input('get.start', '0', 'trim') ? input('get.start', '0', 'trim') : 0;
	        $limit = input('get.length', '0', 'trim') ? input('get.length', '0', 'trim') : 20;
	        $draw = input('get.draw', '0', 'trim');			
			$refer_name = input('get.refer_name', '', 'trim');			
            $nickname = input('get.nickname', '', 'trim');
            $uid = input('get.uid', '', 'trim');
			$create_time = input('get.create_time', '', 'trim');
			$excel = input('get.excel', '', 'trim') ? input('get.excel', '', 'trim') : 0;

			$where = [];
			if ($refer_name != '') {
				$where['b.nickname'] = ['like', '%'.$refer_name.'%'];
			}
			
			if ($nickname != '') {
				$where['a.nickname|a.mobile'] = ['like', '%'.$nickname.'%'];
            }
            
            if ($uid != '') {
				$where['a.id'] = $uid;
			}

			if ($create_time != '') {
				$create_time_arr = explode(" - ", $create_time);
				$start_time = strtotime($create_time_arr[0]);
				$end_time = strtotime($create_time_arr[1].' 23:59:59');
				$where['a.create_time'] = ['between', [$start_time,$end_time]];
			}

            if ($excel) {
                $listInfo = UserModel::getUserList($where,$start,$limit, $excel);
                $this->export($listInfo['data']);
                return true;
            }else{
                $listInfo = UserModel::getUserList($where,$start,$limit);
            }
	        $data = array(
	            'draw'            => $draw,
	            'recordsTotal'    => $listInfo['total'],
	            'recordsFiltered' => $listInfo['total'],
	            'data'            => $listInfo['data']
	        );
	        return json($data);     		
		}		
	}

    /**
     * 会员详情
     */
    public function see($uid=''){
        $this->assign([
            'uid'=>$uid,
            'userinfo'=>UserModel::getUserDetailed($uid),
            'is_layui'=>true,
            'headerList'=>UserModel::getHeaderList($uid),
            'count'=>UserModel::getCountInfo($uid),
        ]);
        return $this->fetch("User/see");
    }


    /*
    *
    * 用户列表导出
    */
    public function  export($listInfo){
        set_time_limit(0);
        ini_set('memory_limit','1024M');

        $export = [];
        foreach ($listInfo as $v) {

            $v['vip_time'] = str_replace("<br/>", "\n", $v['vip_time']);

            $row = [
                $v['id'],
                $v['nickname'],
                $v['level_name'],
                $v['balance_money'],
                $v['balance_flower'],
                $v['balance_oil'],
                $v['refer_name'],
                $v['create_time'],
                $v['last_time'],
                $v['vip_time'],
            ];
            $export[] = $row;
        }

        PHPExcelService::setExcelHeader(['编号','姓名','等级','余额','花宝','油卡余额','推荐人','首次访问日期','最近访问日期','会员有效期'])
            ->setExcelTile('用户导出', '用户列表' . time(), ' 生成时间：' . date('Y-m-d H:i:s', time()))
            ->setExcelContent($export)
            ->ExcelSave();
        exit();
    }
    //用户上课记录
    public function user_course_log()
    {
        if ($this->request->isGet()) {
            return $this->fetch("User/user_course_log");
        }
    }
    /**
     *
     * AJAX查询列表
     *
     */
    public function ajaxgetcourse_log() {
        if ($this->request->isGet()) {
            $start = input('get.start', '0', 'trim') ? input('get.start', '0', 'trim') : 0;
            $limit = input('get.length', '0', 'trim') ? input('get.length', '0', 'trim') : 20;
            $draw = input('get.draw', '0', 'trim');
            $nickname = input('get.nickname', '', 'trim');
            $couser = input('get.couser', '', 'trim');
            $couser_node = input('get.couser_node', '', 'trim');

            if ($nickname != '') {
                $where['nickname|mobile'] = ['like', '%'.$nickname.'%'];
                $whereIn = db('user')->where($where)->column('id');
                $log = db('user_course_log')->whereIn('user_id',$whereIn)->limit($start,$limit)->select();
            }else{
                if ($couser!=''){
                    $whereIn = db('couser')->where('title','like',$couser)->column('id');
                    $log = db('user_course_log')->whereIn('couser_id',$whereIn)->limit($start,$limit)->select();
                }elseif($couser_node!=''){
                    $whereIn = db('couser_node')->where('title','like',$couser_node)->column('id');
                    $log = db('user_course_log')->whereIn('couser_node_id',$whereIn)->limit($start,$limit)->select();
                }else{
                    $log = db('user_course_log')->order('time desc')->limit($start,$limit)->select();
                }
            }

            foreach ($log as &$v){
                $v['time'] = date('Y-m-d H:i:s',$v['time']);
                $v['nickname'] = db('user')->where('id',$v['user_id'])->value('nickname');
                $v['course_title'] = db('course')->where('id',$v['course_id'])->value('title');
                $v['course_node_title'] = db('course_node')->where('id',$v['course_node_id'])->value('title');
            }
            $data = array(
                'draw'            => $draw,
                'recordsTotal'    => count($log),
                'recordsFiltered' => count($log),
                'data'            => $log
            );
            return json($data);
        }
    }
}