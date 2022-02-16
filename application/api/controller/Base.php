<?php
/**
 * Created by PhpStorm.
 * User: hutuo
 * Date: 2018/11/02
 * Time: 09:21
 */

namespace app\api\controller;

use think\Controller;
use think\Request;

class Base extends Controller {
	public $_uid = 0;
	public $platform = 'mobile';

	public function _initialize() {
		parent::_initialize();
		$header = Request::instance()->header();
		//获取platform

		//dump(strtotime('2021-02-07 08:05')-time());
		/* if (empty($header['platform']) || !in_array(strtolower($header['platform']), ['ios', 'android', 'routine', 'mobile'])) {
	            ajaxError('平台类型非法', -1)->send();
	            //db('get_code')->insert(['code'=>'平台类型非法','time'=>time()]);
	            //db('get_code')->insert(['code'=>json_encode($header),'time'=>time()]);
	            exit();
*/
		//db('get_code')->insert(['code'=>json_encode($header),'time'=>time()]);
		if (!empty($header['platform'])) {
			$this->platform = strtolower($header['platform']);
		}
	}
	//获取sign函数
	public function getSign($params, $appSecret) {
		// 1. 对加密数组进行字典排序
		foreach ($params as $key => $value) {
			$arr[$key] = $key;
		}
		sort($arr);
		$str = $appSecret;
		foreach ($arr as $k => $v) {
			$str = $str . $arr[$k] . $params[$v];
		}
		$restr = $str . $appSecret;
		$sign = strtoupper(md5($restr));
		return $sign;
	}
}