<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
//阿里云域名
define('ALIYUN', 'https://pgtcnd.cooov.com'); //上传用的
define('OSS', '');
//上传文件目录
define('UPLOAD_PATH', 'uploads');

$serverPort = $_SERVER['SERVER_PORT'];
switch ($serverPort) {
case '81':
	define('BIND_MODULE', 'admin');
	break;
case '82':
	define('BIND_MODULE', 'api');
	break;
}

// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
require __DIR__ . '/../vendor/autoload.php';
