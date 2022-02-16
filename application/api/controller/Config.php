<?php

namespace app\api\controller;

use think\Controller;

class Config extends Controller {
	public function view() {
		$type = input('get.type/s');

		$data = model('Config')->where('type', $type)->find();
		/* if(empty($data)){
			            return $this->error('参数错误');
		*/

		return view('Config/index', ['data' => $data]);
	}

	public function getQrcode() {
		$type = input('get.type/s', 'mnp');
		if ($type == 'H5') {
			$url = input('get.url');
			$id = input('get.id');
			$refer_id = input('get.refer_id');

			if (empty($id)) {
				return ajaxError('参数错误');
			}

			header('Content-Type:image/png');

			vendor('phpqrcode.phpqrcode');
			$value = $url . '?id=' . $id . '&refer_id=' . $refer_id;
		} else {
			$album_id = input('get.album_id/d', 0);
			$user_id = input('get.refer_id/d', 0);

			if (empty($album_id)) {
				return ajaxError('参数错误');
			}

			header('Content-Type:image/png');

			vendor('phpqrcode.phpqrcode');
			$value = config('API_URL') . '?album_id=' . $album_id . '&refer_id=' . $user_id;
		}
		$errorCorrectionLevel = 'L'; //容错级别
		$matrixPointSize = 6; //生成图片大小
		$images = \think\image();
		dump($images);die;
		$data = \phpqrcode\QRcode::png($value, false, $errorCorrectionLevel, $matrixPointSize, 2);
		xdebug($data);
	}
	public function index() {
		/*Cache::set('jieqi',input('arr'));
			        $jieqi = Cache::get('jieqi');
			        $data = [];
			        $arr = explode('/',$jieqi);
			        foreach ($arr as $v){
			            if (!empty($v)){
			                $str = explode('-',$v);
			                $time = explode(' ',$str[3]);
			                $data[] = ['name'=>trim($str[0]),'time'=>trim($str[1]).'-'.trim($str[2]).'-'.$str[3],'year'=>trim($str[1]),'month'=>trim($str[2]),'day'=>trim($time[0]),'hour'=>$time[1]];
			            }
			        }
			        $jieqi = Cache::set('jieqi',$data);
		*/
		/*$add = time();
			        $res = Cache::get('jieqi');
			        $num = 100;
			        $limit = ceil(count($res)/$num);
			        for($i=1;$i<=$limit;$i++){
			            $offset=($i-1)*$num;
			            $data=array_slice($res,$offset,$num);
			            db('solar_terms')->insertAll($data);
			        };
			        $end = time();
		*/
		/*
			        $data = [];
			        $arr = explode('/',input('arr'));
			        foreach ($arr as $v){

			        }
		*/
	}
}