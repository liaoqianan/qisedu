<?php

namespace app\admin\controller;

use app\admin\model\AdminUserAction;
use think\Request;

class Log extends Base {
	
	/**
	 * 
	 * 列表
	 * 
	 */
    public function index() {
        $is_admin = 1;
        $user_administrator = config('USER_ADMINISTRATOR');
        if (is_array($user_administrator) && !empty($user_administrator)) {
        	if (!in_array($this->uid, $user_administrator)) {
        		$where['uid'] = $this->uid;
        		$is_admin = 0;
        	} 
        }
        
        if (!$user_administrator) {
        	$this->userInfo['username'] = trim(strtolower($this->userInfo['username']));
        	if ($this->userInfo['username'] !='admin') {
        		$where['uid'] = $this->uid;
        		$is_admin = 0;
        	}
        }     
        $this->assign("is_admin", $is_admin);	
        return view("Log/index");
    }

    /**
     * 
     * AJAX查询列表
     * 
     */
    public function ajaxGetIndex() {
    	
   		$start = input('get.start','0','trim') ? input('get.start','0','trim') : 0;
        $limit = input('get.length', '0', 'trim') ? input('get.length', '0', 'trim') : 20;
        $draw = input('get.draw', '0', 'trim');
        $error = input('get.error', '', 'trim');
        $where = array();

        $is_admin = 1;
        $user_administrator = config('USER_ADMINISTRATOR');
        if (is_array($user_administrator) && !empty($user_administrator)) {
        	if (!in_array($this->uid, $user_administrator)) {
        		$where['uid'] = $this->uid;
        		$is_admin = 0;
        	} 
        }
        
        if (!$user_administrator) {
        	$this->userInfo['username'] = trim(strtolower($this->userInfo['username']));
        	if ($this->userInfo['username'] !='admin') {
        		$where['uid'] = $this->uid;
        		$is_admin = 0;
        	}
        }    
            
        $getInfo = input('get.');
        if (isset($getInfo['type']) && !empty($getInfo['type'])) {
            if (isset($getInfo['keyword']) && !empty($getInfo['keyword'])) {
                switch ($getInfo['type']) {
                    case 1:
                        $where['url'] = array('like', '%' . trim($getInfo['keyword']) . '%');
                        break;
                    case 2:
                        $where['nickname'] = array('like', '%' . trim($getInfo['keyword']) . '%');
                        break; 
                    case 3:
                        $where['uid'] = trim($getInfo['keyword']);
                        break;
                }
            }
        }
        if (!empty($getInfo['add_time'])) {
            $create_time =  explode(' - ', $getInfo['add_time']);
            $start_time = strtotime(trim($create_time[0]));
            $end_time = strtotime(trim($create_time[1]).' 23:59:59');
            $where['addTime'] = ['between',[$start_time,$end_time]];
        }

        if(!empty($getInfo['error'])){
            $where['error'] = $getInfo['error'];
        }

        $order = $error >= 0? 'addTime desc' :'error desc,addTime desc';
        $actionModel = new AdminUserAction();
        $total = $actionModel->getCountByWhere($where);
        $info = $actionModel->getListLimit($where, $start, $limit, $order);
        if (!empty($info)) {
            foreach ($info as &$val) {
                $val['error'] = $val['error'] > 0 ? '非法访问' : '正常';
            }
        }
        $data = array(
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $info
        );
        $this->assign("is_admin", $is_admin);
        return json($data);
    }

    /**
     * 
     * 删除日志
     * 
     */
    public function del() {
        $id = input('post.id');
        $actionModel = new AdminUserAction();
        $res = $actionModel->delByWhere(['id'=>$id]);
        if ($res === false) {
            return $this->ajaxError('操作失败');
        } else {
            return $this->ajaxSuccess('操作成功');
        }
    }

    /**
     * 
     * 批量删除日志
     */
    public function batchDelete()
    {
        $id = rtrim(input('post.id/s'),',');
        $arrId = explode(',', $id);

        $ids = [];
        foreach ($arrId as $val) {
            $actionModel = new AdminUserAction();
            $data = $actionModel->getOneByData(['id'=>$val]);
            if($data){
                $ids[] = $val;
            }
        }

        if(empty($ids))
        {
            return $this->ajaxError('操作失败');
        }else{
            $actionModel = new AdminUserAction();
            $actionModel->delByWhere(['id'=>['in', $ids]]);
            return $this->ajaxSuccess('批量删除成功');
        }
    }
    /*
     *
     * 日志导出
     * */
    public function export() {
        ini_set('max_execution_time', '0');

        $type   = input('get.type');
        $keyword  = input('get.keyword');
        $add_time = input('get.add_time');


        $where=[];

        $user_administrator = config('USER_ADMINISTRATOR');
        if (is_array($user_administrator) && !empty($user_administrator)) {
            if (!in_array($this->uid, $user_administrator)) {
                $where['uid'] = $this->uid;
            }
        }

        if (!$user_administrator) {
            $this->userInfo['username'] = trim(strtolower($this->userInfo['username']));
            if ($this->userInfo['username'] !='admin') {
                $where['uid'] = $this->uid;
            }
        }

        if($type != ''){
            if($type == 1){
                $where['url'] = ['like', '%'.$keyword.'%'];
            }elseif ($type == 2){
                $where['nickname'] = ['like', '%'.$keyword.'%'];
            }else{
                $where['uid'] = ['like', '%'.$keyword.'%'];
            }
        }
        if ($add_time != '') {
            $add_time_arr = explode(" - ", $add_time);
            $start_time = strtotime($add_time_arr [0]);
            $end_time = strtotime($add_time_arr [1]);
            $where['addTime'] = ['between', [$start_time,$end_time]];
        }

        $actionModel = new AdminUserAction();
        $listInfo = $actionModel->getListByWhere($where);


        Vendor('phpexcel.PHPExcel');
        Vendor('PHPExcel.PHPExcel.Worksheet.Drawing');
        Vendor('PHPExcel.PHPExcel.Writer.Excel2007');
        Vendor('PHPExcel.PHPExcel.Writer.Excel5');
        $objExcel = new \PHPExcel();
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $objExcel->getProperties()->setCreator('constantine')
            ->setLastModifiedBy('constantine')
            ->setTitle('管理员操作日志_'.date("Y-m-d"))
            ->setSubject('管理员操作日志'.date("Y-m-d"))
            ->setDescription('constantine for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Result file');

        $letter =explode(',',"A,B,C,D,E");
        $arrHeader = array('序号','用户昵称','操作内容','操作URL','操作时间');

        $objExcel->getActiveSheet()->mergeCells("A1:E1");
        $objExcel->setActiveSheetIndex(0)->setCellValue("A1",'操作日志_'.date("Y-m-d"));

        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]2","$arrHeader[$i]");
        };

        //填充表格信息
        if ($listInfo) {
            foreach($listInfo as $k=>$v){
                $k = $k + 3;
                $v['addTime'] = date('Y-m-d H:i:s',$v['addTime']);


                $objActSheet->setCellValue('A'.$k,$v['id']);
                $objActSheet->setCellValue('B'.$k, $v['nickname']);
                $objActSheet->setCellValue('C'.$k, $v['actionName']);
                $objActSheet->setCellValue('D'.$k, $v['url']);
                $objActSheet->setCellValue('E'.$k, $v['addTime']);

                // 表格高度
                $objActSheet->getRowDimension($k)->setRowHeight(30);
                //设置居中
                $objExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objExcel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            }
        }

        //设置表格的宽度
        $objActSheet->getColumnDimension('A')->setWidth("20");
        $objActSheet->getColumnDimension('B')->setWidth("20");
        $objActSheet->getColumnDimension('C')->setWidth("40");
        $objActSheet->getColumnDimension('D')->setWidth("20");
        $objActSheet->getColumnDimension('E')->setWidth("20");


        $outfile = "用户操作日志_".date('Y-m-d').'.xlsx';
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outfile.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');

        echo 'ok';
        exit();
    }
}
