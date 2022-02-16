<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
	// +----------------------------------------------------------------------
	// | 应用设置
	// +----------------------------------------------------------------------

	// 应用调试模式
	'app_debug' => true,
	// 应用Trace
	'app_trace' => false,
	// 应用模式状态
	'app_status' => '',
	// 是否支持多模块
	'app_multi_module' => true,
	// 入口自动绑定模块
	'auto_bind_module' => false,
	// 注册的根命名空间
	'root_namespace' => [],
	// 扩展函数文件
	'extra_file_list' => [THINK_PATH . 'helper' . EXT],
	// 默认输出类型
	'default_return_type' => 'html',
	// 默认AJAX 数据返回格式,可选json xml ...
	'default_ajax_return' => 'json',
	// 默认JSONP格式返回的处理方法
	'default_jsonp_handler' => 'jsonpReturn',
	// 默认JSONP处理方法
	'var_jsonp_handler' => 'callback',
	// 默认时区
	'default_timezone' => 'PRC',
	// 是否开启多语言
	'lang_switch_on' => false,
	// 默认全局过滤方法 用逗号分隔多个
	'default_filter' => '',
	// 默认语言
	'default_lang' => 'zh-cn',
	// 应用类库后缀
	'class_suffix' => false,
	// 控制器类后缀
	'controller_suffix' => false,
	// +----------------------------------------------------------------------
	// | 模块设置
	// +----------------------------------------------------------------------

	// 默认模块名
	'default_module' => 'admin',
	// 禁止访问模块
	'deny_module_list' => ['common'],
	// 默认控制器名
	'default_controller' => 'Index',
	// 默认操作名
	'default_action' => 'index',
	// 默认验证器
	'default_validate' => '',
	// 默认的空控制器名
	'empty_controller' => 'Error',
	// 操作方法后缀
	'action_suffix' => '',
	// 自动搜索控制器
	'controller_auto_search' => true,

	// +----------------------------------------------------------------------
	// | URL设置
	// +----------------------------------------------------------------------

	// PATHINFO变量名 用于兼容模式
	'var_pathinfo' => 's',
	// 兼容PATH_INFO获取
	'pathinfo_fetch' => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
	// pathinfo分隔符
	'pathinfo_depr' => '/',
	// URL伪静态后缀
	'url_html_suffix' => '',
	// URL普通方式参数 用于自动生成
	'url_common_param' => false,
	// URL参数方式 0 按名称成对解析 1 按顺序解析
	'url_param_type' => 0,
	// 是否开启路由
	'url_route_on' => true,
	// 路由使用完整匹配
	'route_complete_match' => false,
	// 路由配置文件（支持配置多个）
	'route_config_file' => ['route'],
	// 是否强制使用路由
	'url_route_must' => false,
	// 域名部署
	'url_domain_deploy' => true,
	\think\Route::domain('t.oms', 'admin'),
	\think\Route::domain('t.api', 'api'),
	// 域名根，如thinkphp.cn
	'url_domain_root' => '',
	// 是否自动转换URL中的控制器和操作名
	'url_convert' => true,
	// 默认的访问控制器层
	'url_controller_layer' => 'controller',
	// 表单请求类型伪装变量
	'var_method' => '_method',
	// 表单ajax伪装变量
	'var_ajax' => '_ajax',
	// 表单pjax伪装变量
	'var_pjax' => '_pjax',
	// 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
	'request_cache' => false,
	// 请求缓存有效期
	'request_cache_expire' => null,
	// 全局请求缓存排除规则
	'request_cache_except' => [],

	// +----------------------------------------------------------------------
	// | 模板设置
	// +----------------------------------------------------------------------

	'template' => [
		// 模板引擎类型 支持 php think 支持扩展
		'type' => 'Think',
		// 默认模板渲染规则 1 解析为小写+下划线 2 全部转换小写
		'auto_rule' => 1,
		// 模板路径
		'view_path' => '',
		// 模板后缀
		'view_suffix' => 'html',
		// 模板文件名分隔符
		'view_depr' => DS,
		// 模板引擎普通标签开始标记
		'tpl_begin' => '{',
		// 模板引擎普通标签结束标记
		'tpl_end' => '}',
		// 标签库标签开始标记
		'taglib_begin' => '{',
		// 标签库标签结束标记
		'taglib_end' => '}',
	],

	// 视图输出字符串内容替换
	'view_replace_str' => [],
	// 默认跳转页面对应的模板文件
	'dispatch_success_tmpl' => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
	'dispatch_error_tmpl' => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',

	// +----------------------------------------------------------------------
	// | 异常及错误设置
	// +----------------------------------------------------------------------

	// 异常页面的模板文件
	'exception_tmpl' => THINK_PATH . 'tpl' . DS . 'think_exception.tpl',

	// 错误显示信息,非调试模式有效
	'error_message' => '页面错误！请稍后再试～',
	// 显示错误信息
	'show_error_msg' => false,
	// 异常处理handle类 留空使用 \think\exception\Handle
	'exception_handle' => '',

	// +----------------------------------------------------------------------
	// | 日志设置
	// +----------------------------------------------------------------------

	'log' => [
		// 日志记录方式，内置 file socket 支持扩展
		'type' => 'File',
		// 日志保存目录
		'path' => LOG_PATH,
		// 日志记录级别
		'level' => [],
	],

	// +----------------------------------------------------------------------
	// | Trace设置 开启 app_trace 后 有效
	// +----------------------------------------------------------------------
	'trace' => [
		// 内置Html Console 支持扩展
		'type' => 'Html',
	],

	// +----------------------------------------------------------------------
	// | 缓存设置
	// +----------------------------------------------------------------------

	'cache' => [
		// 驱动方式
		'type' => 'File', //Redis,file
		// 缓存保存目录
		'path' => CACHE_PATH,
		// 缓存前缀
		'prefix' => 'rt_',
		// 缓存有效期 0表示永久缓存
		'expire' => 0,
	],

	// +----------------------------------------------------------------------
	// | 会话设置
	// +----------------------------------------------------------------------

	'session' => [
		'id' => '',
		// SESSION_ID的提交变量,解决flash上传跨域
		'var_session_id' => '',
		// SESSION 前缀
		'prefix' => 'think',
		// 驱动方式 支持redis memcache memcached
		'type' => '',
		// 是否自动开启 SESSION
		'auto_start' => true,
	],

	// +----------------------------------------------------------------------
	// | Cookie设置
	// +----------------------------------------------------------------------
	'cookie' => [
		// cookie 名称前缀
		'prefix' => '',
		// cookie 保存时间
		'expire' => 3600 * 30 * 24,
		// cookie 保存路径
		'path' => '/',
		// cookie 有效域名
		'domain' => '',
		//  cookie 启用安全传输
		'secure' => false,
		// httponly设置
		'httponly' => 'true',
		// 是否使用 setcookie
		'setcookie' => true,
	],

	//分页配置
	'paginate' => [
		'type' => 'bootstrap',
		'var_page' => 'page',
		'list_rows' => 15,
	],

	'ADMIN_URL' => 'http://t.oms.qisedu.com',
	'API_URL' => 'http://t.api.qisedu.com',

	'APP_VERSION' => 'v1.0',
	'APP_NAME' => '起色',
	'APP_SUPPLIER' => '供应商',

	//阿里云配置
	'ALICLOUD' => [
		'AccessKeyId' => 'LTAI4GEjCZSiaDeiuFKHdoFQ',
		'AccessKeySecret' => 'c6vlo16Y585FFMPMs7A2MMd812GxB5',
		//回放录制成功模板
		'RecordTemplateCode' => 'SMS_210076801',
		//验证码模板
		'TemplateCode' => 'SMS_197755048',
		//通知上课模板
		'TemplateNotice' => 'SMS_209195962',
		//购买模板
		'PayTemplateNotice' => 'SMS_210070957',
		'SignName' => '酷威',
		'SignNoticeName' => '酷威信息技术',
		'sms_product' => '',
		'sms_time_out' => '60',
	],

	// 后台在线时长设定，超时缓存过期
	'ONLINE_TIME' => 36000,
	'SUPPLIER_ONLINE_TIME' => 36000,
	//后台管理员
	'USER_ADMINISTRATOR' => array(1),

	//后台用户名前缀
	'USERNAME_PREFIX' => '',

	'AUTH_KEY' => 'I&TC{pft>L,C`wFQ>&#ROW>k{Kxlt1>ryW(>r<#R',

	'daterangepicker_days' => 3650,

	//阿里云Oss配置
	'aliyun_oss' => [
		'AccessKey_ID' => 'LTAI4GEjCZSiaDeiuFKHdoFQ',
		'AccessKey_Secret' => 'c6vlo16Y585FFMPMs7A2MMd812GxB5',
		'Endpoint' => "oss-cn-zhangjiakou.aliyuncs.com",
		'Bucket' => "aidazhoubian",
	],
	//直播配置
	'training' => [
		'url' => 'https://kuwei.gensee.com',
		'loginName' => 'admin@szpgt.com',
		'password' => '888888',
	],
	//直播配置
	'webcast' => [
		'url' => 'https://kuwei.gensee.com',
		'loginName' => 'admin@kuwei.com',
		'password' => '888888',
	],
	//保利威直播配置
	'Live' => [
		'userId' => 'e8369dc6df',
		'AppID' => 'fu8k6enpot',
		'AppSecret' => '6e15152c12ad4ad789773524e2528399',
	],
	//微信公众号
	'Wechat' => [
		// 应用id
		'appid' => 'wxe5838beab0b6f7cf',
		'appsecret' => 'fc54d718ce49a22201b60ec37fb517f1',
		// 公众号开发token
		//模板id
		'model' => '64jEQWCE3M9p4kPmNqUD-t41-edfR86yHszmAXgDa_E',
		//支付成功模板id
		'Paymodel' => '9Z2wmNfQhz93Y61V47njDhSbeMJ6nRc9ZQ2qB7IL3_I',
		//回放录制成功模板id
		'Recordmodel' => 'sOakKIA6AHKA4UXneOU38lW_MNXw1y6lgWUKGzWqsIk',
		'token' => 'cooov_wx_callback_api_token',
		// 消息加解密密钥
		'encodingaeskey' => 'cpNiyYdqqZlE2QYOf5PPKbxWGkcxcKnaHisU824WBVZ',
	],
];
