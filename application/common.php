<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

use \app\common\Common;
use \app\common\WechatAppPay;
use think\Cache;
use think\Request;

// 应用公共文件
/**
 * 判断是否是系统管理员
 * @param mixed $uid
 * @return bool
 */
function isAdministrator( $uid = '' ){
    if( empty($uid) ) $uid = session('uid');
    if( is_array(config('USER_ADMINISTRATOR')) ){
        if( is_array( $uid ) ){
            $m = array_intersect( config('USER_ADMINISTRATOR'), $uid );
            if( count($m) ){
                return TRUE;
            }
        }else{
            if( in_array( $uid, config('USER_ADMINISTRATOR') ) ){
                return TRUE;
            }
        }
    }else{
        if( is_array( $uid ) ){
            if( in_array(config('USER_ADMINISTRATOR'),$uid) ){
                return TRUE;
            }
        }else{
            if( $uid == config('USER_ADMINISTRATOR')){
                return TRUE;
            }
        }
    }
    return FALSE;
}

/**
 * 导出excel
 * @param $strTable	表格内容
 * @param $filename 文件名
 */
function downloadExcel($strTable,$filename)
{
    header("Content-type: application/vnd.ms-excel");
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=".$filename."_".date('Y-m-d').".xls");
    header('Expires:0');
    header('Pragma:public');
    echo '<html><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'.$strTable.'</html>';
}
//获取文字
function get_text($string,$start = 0,$end = 50){
    //把一些预定义的 HTML 实体转换为字符
    $html_string = htmlspecialchars_decode($string);
    //将空格替换成空
    $content = str_replace(" ", "", $html_string);
    //函数剥去字符串中的 HTML、XML 以及 PHP 的标签,获取纯文本内容
    $contents = strip_tags($content);
    //返回字符串中的前80字符串长度的字符
    $string = mb_substr($contents, $start, $end, "utf-8");
    return $string;
}
/**
 * 
 * 手机号验证
 * @param unknown_type $phone
 */
function is_mobile ( $mobile ) {
	if (preg_match("/^1[3-9]{1}\d{9}$/",$mobile)) {
		return true;
	} else {
		return false;
	}
}

/**
 * 
 * 电话号码验证
 * @param unknown_type $phone
 */
function is_phone ( $phone ) {
	if (preg_match("/^([0-9]{3,4}-)?[0-9]{7,8}$/", $phone)) {
		return true;
	} else {
		return false;
	}
}

/**
 * 
 * url验证
 * @param unknown_type $url
 */
function is_url ( $url ) {
	if (preg_match("/^http(s)?:\\/\\/.+/", $url)) {
		return true;
	} else {
		return false;
	}	
}

/**
 * 把返回的数据集转换成Tree
 * @param $list
 * @param string $pk
 * @param string $pid
 * @param string $child
 * @param string $root
 * @return array
 */
function listToTree($list, $pk='id', $pid = 'fid', $child = '_child', $root = '0') {
    $tree = array();
    if(is_array($list)) {
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = &$list[$key];
        }
        foreach ($list as $key => $data) {
            $parentId =  $data[$pid];
            if ($root == $parentId) {
                $tree[] = &$list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent = &$refer[$parentId];
                    $parent[$child][] = &$list[$key];
                }
            }
        }
    }
    return $tree;
}


/**
 * 将对象转换成数组
 */
function object_to_array($e)
{
    $e = (array )$e;
    foreach ($e as $k => $v) {
        if (gettype($v) == 'resource')
            return;
        if (gettype($v) == 'object' || gettype($v) == 'array')
            $e[$k] = (array )object_to_array($v);
    }
    return $e;
}

/**
 * @Action    调试打印
 * @Param     $var      需要打印的值
 *            $method   需要打印的方式
 *            $exit     是否停止程序继续执行
 * @Return    void
 */
function xdebug($var, $exit = true, $method = true)
{
    echo '<meta content-type:"text/html" charset="utf-8" />';
	echo ' <pre>';
    $method ? print_r($var) : var_dump($var);
    echo '</pre> ' . '<hr style="color:red">' . '<br>';

    if($exit)
    {
        exit;
    }
}

function formatTree($list, $lv = 0, $title = 'name'){
    $formatTree = array();
    foreach($list as $key => $val){
        $title_prefix = '';
        for( $i=0;$i<$lv;$i++ ){
            $title_prefix .= "|---";
        }
        $val['lv'] = $lv;
        $val['namePrefix'] = $lv == 0 ? '' : $title_prefix;
        $val['showName'] = $lv == 0 ? $val[$title] : $title_prefix.$val[$title];
        if(!array_key_exists('_child', $val)){
            array_push($formatTree, $val);
        }else{
            $child = $val['_child'];
            unset($val['_child']);
            array_push($formatTree, $val);
            $middle = formatTree($child, $lv+1, $title); //进行下一层递归
            $formatTree = array_merge($formatTree, $middle);
        }
    }
    return $formatTree;
}

if (!function_exists('array_column')) {
    function array_column($array, $val, $key = null){
        $newArr = array();
        if( is_null($key) ){
            foreach ($array as $index => $item) {
                $newArr[] = $item[$val];
            }
        }else{
            foreach ($array as $index => $item) {
                $newArr[$item[$key]] = $item[$val];
            }
        }
        return $newArr;
    }
}

/**
 * 系统非常规MD5加密方法
 * @param  string $str 要加密的字符串
 * @param  string $auth_key 要加密的字符串
 * @return string
 * @author jry <598821125@qq.com>
 */
function user_md5($str, $auth_key = ''){
    if(!$auth_key){
        $auth_key = config('AUTH_KEY');
    }
    return '' === $str ? '' : md5(sha1($str) . $auth_key);
}

function ajaxSuccess( $msg, $code = 1, $data = array() ){
    $returnData = array(
        'code' => $code,
        'msg' => $msg,
        'data' => $data
    );
    return json($returnData);
}

function ajaxError( $msg, $code = -1 ){
    $returnData = array(
        'code' => $code,
        'msg' => $msg
    );
    return json($returnData);
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip() {
    $ip = '';
    if (isset($_SERVER['HTTP_CDN_SRC_IP']) && $_SERVER['HTTP_CDN_SRC_IP']) {
        $ip = $_SERVER['HTTP_CDN_SRC_IP'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
        $allIps = $_SERVER['HTTP_X_FORWARDED_FOR'];
        $allIpsArray = explode(',', $allIps);
        $ip = $allIpsArray[0];
    } else if (isset($_SERVER['HTTP_X_REAL_IP']) && $_SERVER['HTTP_X_REAL_IP']) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    } else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    if (empty($ip)) {
        $ip = '0.0.0.0';
    }
    return $ip;
}

/**
 * 发送验证码短信
 * @param string $mobile  手机号码
 * @param int $code    验证码
 * @param string $sms_template_code    模板编号
 * @return bool    短信发送成功返回true失败返回false
 *
 * 验证码模板：${product}用户注册验证码：${code}。请勿将验证码告知他人并确认该申请是您本人操作！
 */
function send_sms_reg( $mobile, $code,$sms_template_code)
{
    $alicloud = config('ALICLOUD');
    $sign_name = $alicloud['SignName']; //签名名称
    if(empty($sms_template_code) || empty($sign_name)){
        return false;
    }

    $sms_cfg = json_encode(array('code'=>$code));

    $db = model("VerifySms");
    $map = array(
        'mobile' => $mobile,
        'code' => $code,
        'status' => 1,
        'addtime' => array('gt', time() -  60),
    );
    $data = $db->where($map)->find();
    if (!empty($data)){
        return array(false,'','发送信息过于频繁');
    }

    // 发送验证码短信
    vendor('dysms.Sms');
    $sms = new Sms();

    $alicloud = config('ALICLOUD');
    $sms->appkey = $alicloud['AccessKeyId'];
    $sms->secretKey = $alicloud['AccessKeySecret'];

    $sms_send = $sms->sendSms($mobile, $sms_template_code, $sms_cfg, $sign_name);

    $success = $sms_send->Code == 'OK' ? true : false; //成功标识
    $return_code = $sms_send->Code; //返回的编码
    $sub_code = $sms_send->Message; //错误码

    if ($success)
    {
        $db->insert(array('mobile' => $mobile, 'code' => $code, 'addtime' => time(), 'ip'=>get_client_ip()));

        return array(true,'','');
    } else {
        return array(false,$return_code,$sub_code);
    }
}
/**
 * 发送短信
 * @param string $mobile  手机号码
 * @param int $sms_cfg      json title=>标题,time=>2000-01-01 12:00
 * @param string $sms_template_code    模板编号
 * @return bool    短信发送成功返回true失败返回false
 *
 * 验证码模板：${product}用户注册验证码：${code}。请勿将验证码告知他人并确认该申请是您本人操作！
 */
function send_sms_notice( $mobile, $sms_cfg,$sms_template_code)
{
    $alicloud = config('ALICLOUD');
    $sign_name = $alicloud['SignNoticeName']; //签名名称
    if(empty($sms_template_code) || empty($sign_name)){
        return false;
    }

    // 发送验证码短信
    vendor('dysms.Sms');
    $sms = new Sms();

    $alicloud = config('ALICLOUD');
    $sms->appkey = $alicloud['AccessKeyId'];
    $sms->secretKey = $alicloud['AccessKeySecret'];
    $sms_send = $sms->sendSms($mobile,$sms_template_code, $sms_cfg, $sign_name);
    $success = $sms_send->Code == 'OK' ? true : false; //成功标识
    $return_code = $sms_send->Code; //返回的编码
    $sub_code = $sms_send->Message; //错误码

    if ($success)
    {
        return array(true,'','');
    } else {
        return array(false,$return_code,$sub_code);
    }
}
/**
 *
 * 是否为小数位
 * @param unknown_type $var
 * @param unknown_type $num
 * @return bool
 *
 */
function is_float_num($var, $num=2) {
    $regex = '';
    if ($num == 0) {
        $regex = '/^[1-9]\d*$/';
    }
    if ($num == 1) {
        $regex = '/^[0-9]+(.[0-9]{'.$num.'})?$/';
    }
    if ($num >= 2) {
        $regex = '/^[0-9]+(.[0-9]{1,'.$num.'})?$/';
    }

    if (preg_match($regex, $var)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 通过支付金额和成本价计算订单的CPS
 * @param float $payPrice 实付金额
 * @param float $cost 成本价
 */
function get_order_cps($payPrice,$cost)
{
    $cps = $payPrice-$cost;
        if($cps <= 0) return $cps = 0;
        $profit = ($payPrice-$cost)/$payPrice*100;
        if($profit >0 && $profit <= 10){
            $cps = $cps * 0.1;
        }elseif(10 < $profit && $profit <= 30){
            $cps = $cps * 0.2;
        }elseif (30 < $profit && $profit <=50){
            $cps = $cps * 0.3;
        }elseif(50 < $profit && $profit <= 100){
            $cps = $cps * 0.4;
        }else{
            $cps = $cps * 0.4;
        }
        return $cps;
}

/**
 * 敏感词过滤
 *
 * @param  string
 * @return string
 */
function sensitive_words_filter($str)
{
    if (!$str) return '';
    $file = ROOT_PATH. PUBILC_PATH.'/static/plug/censorwords/CensorWords';
    $words = file($file);
    foreach($words as $word)
    {
        $word = str_replace(array("\r\n","\r","\n","/","<",">","="," "), '', $word);
        if (!$word) continue;

        $ret = preg_match("/$word/", $str, $match);
        if ($ret) {
            return $match[0];
        }
    }
    return '';
}

/**
 * 上传路径转化,默认路径 UPLOAD_PATH
 * $type 类型
 */
function makePathToUrl($path,$type = 2)
{
    $path =  DS.ltrim(rtrim($path));
    switch ($type){
        case 1:
            $path .= DS.date('Y');
            break;
        case 2:
            $path .=  DS.date('Y').DS.date('m');
            break;
        case 3:
            $path .=  DS.date('Y').DS.date('m').DS.date('d');
            break;
    }
    if (is_dir(ROOT_PATH.UPLOAD_PATH.$path) == true || mkdir(ROOT_PATH.UPLOAD_PATH.$path, 0777, true) == true) {
        return trim(str_replace(DS, '/',UPLOAD_PATH.$path),'.');
    }else return '';

}
// 过滤掉emoji表情
function filterEmoji($str)
{
    $str = preg_replace_callback(    //执行一个正则表达式搜索并且使用一个回调进行替换
        '/./u',
        function (array $match) {
            return strlen($match[0]) >= 4 ? '' : $match[0];
        },
        $str);

    return $str;
}

function isMobile() {
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
        return true;
    }
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset($_SERVER['HTTP_VIA'])) {
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile','MicroMessenger');
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}

//加密函数
function lock_url($txt,$key='')
{
    if(empty($key)) $key = config('pass_keyword');
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
    $nh = rand(0,64);
    $ch = $chars[$nh];
    $mdKey = md5($key.$ch);
    $mdKey = substr($mdKey,$nh%8, $nh%8+7);
    $txt = base64_encode($txt);
    $tmp = '';
    $i=0;$j=0;$k = 0;
    for ($i=0; $i<strlen($txt); $i++) {
        $k = $k == strlen($mdKey) ? 0 : $k;
        $j = ($nh+strpos($chars,$txt[$i])+ord($mdKey[$k++]))%64;
        $tmp .= $chars[$j];
    }
    return urlencode($ch.$tmp);
}

//解密函数
function unlock_url($txt,$key='')
{
    if(empty($key)) $key = config('pass_keyword');
    $txt = urldecode($txt);
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
    $ch = $txt[0];
    $nh = strpos($chars,$ch);
    $mdKey = md5($key.$ch);
    $mdKey = substr($mdKey,$nh%8, $nh%8+7);
    $txt = substr($txt,1);
    $tmp = '';
    $i=0;$j=0; $k = 0;
    for ($i=0; $i<strlen($txt); $i++) {
        $k = $k == strlen($mdKey) ? 0 : $k;
        $j = strpos($chars,$txt[$i])-$nh - ord($mdKey[$k++]);
        while ($j<0) $j+=64;
        $tmp .= $chars[$j];
    }
    return base64_decode($tmp);
}
function curl_https($url, $param, $isPost = 1, $isPassHttps = true, $outTime = 30) {
//    $headers = array('Content-Type: application/x-www-form-urlencoded');
    $curl = curl_init(); // 启动一个CURL会话
//    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, $isPost); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_POSTFIELDS, $param); // Post提交的数据包
    curl_setopt($curl, CURLOPT_TIMEOUT, $outTime); // 设置超时限制防止死循环
    if ($isPassHttps) {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
    }
    $tmpInfo = curl_exec($curl);     //返回api的json对象
    curl_close($curl);    //关闭URL请求
    return $tmpInfo;    //返回json对象
}

/**
 * 新人扫码注册发放优惠券
 * @param $uid
 */
function nuser_issue_coupon($uid){
    $new_coupon = db('store_coupon')->where('coupon_type',1)->where('status',1)->where('is_del',0)->find();//获取新人券
    if (!$new_coupon) {
        return false;
    }
    $data['cid'] = $new_coupon['id'];
    $data['uid'] = $uid;
    $data['coupon_title'] = $new_coupon['title'];
    $data['coupon_price'] = $new_coupon['coupon_price'];
    $data['use_min_price'] = $new_coupon['use_min_price'];
    $data['add_time'] = time();
    $data['end_time'] = $data['add_time']+$new_coupon['coupon_time']*86400;
    $res = db('store_coupon_user')->insert($data);
    return $res;
}

/**
 * 通过UID往上获取level大于0的用户
 * @param int $uid
 * @param int $i 查询的次数
 */
function get_spread_info($uid,$i = 0)
{
    $data = [];
    $userInfo = db('user')->where('uid',$uid)->find();
    if($userInfo['spread_uid'] && $i<20){
        $spreadInfo = db('user')->where('uid',$userInfo['spread_uid'])->find();
        $spread_level = $spreadInfo['level'];
        if($spread_level == 0){
            $i ++;
            return get_spread_info($spreadInfo['uid'],$i);
        }else{
            $data['spread_level'] = $spread_level;
            $data['spread_uid'] = $spreadInfo['uid'];
            $data['times'] = $i;
            return $data;
        }
    }else{
        $data['spread_level'] = 0;
        $data['spread_uid'] = 0;
        $data['times'] = $i;
        return $data;
    }
}
/**
 * 顶级推荐人
 * @param int $uid
 * @param return int
 */
function get_top_spread($uid,$i = 0)
{
    $userInfo = db('user')->where('uid',$uid)->field('spread_uid,uid')->limit(1)->find();
    if($userInfo['spread_uid'] > 0 && $i < 20){
        $spreadInfo = db('user')->where('uid',$userInfo['spread_uid'])->field('spread_uid,uid')->limit(1)->find();
        if($spreadInfo['spread_uid']>0){
            $i ++;
            return get_top_spread($spreadInfo['uid'],$i);
        }else{
            $uid = $spreadInfo['uid'];
            return $uid;
        }
    }else{
        return $uid;
    }
}

/**
 * 抽奖概率计算
 * @param array $prize_arr
 */
function lottery_Probability($prize_arr)
{
    //拼装奖项数组
    // 奖项id，奖品，概率
    foreach ($prize_arr as $key => $val) {
        $arr[$val['id']] = $val['probability'];//概率数组
    }
    $rid = get_rand($arr); //根据概率获取奖项id
    foreach($prize_arr as $k=>$v){
        if($rid == $v['id']){
            $result = $v;//奖品信息
        }
    }
    return $result;
}

//计算中奖概率
function get_rand($proArr) {
    $result = '';
    //概率数组的总概率精度
    $proSum = array_sum($proArr);
    //概率数组循环
    foreach ($proArr as $key => $proCur) {
        $randNum = mt_rand(1, $proSum); //返回随机整数

        if ($randNum <= $proCur) {
            $result = $key;
            break;
        } else {
            $proSum -= $proCur;
        }
    }
    unset ($proArr);
    return $result;
}

function getAvatarAttr($value)
{
    $domain = request()->server('REQUEST_SCHEME') . '://' . request()->server('SERVER_NAME') . '/public';
    if(empty($value)){
        return $domain . config('avatar');
    }else{
        if(strpos($value, 'http') === false && strpos($value, 'https') === false){
            return $domain . $value;
        }else{
            return $value;
        }
    }
}

function getImageThumb($url,$width = 360){
    if (empty($url)){
        return '';
    }

//    $header_array = get_headers($url, true);
//    $size = $header_array['Content-Length'];
//
//    if ($size < 20971520){
//        return $url.'?x-oss-process=image/resize,m_lfit,w_'.$width.',limit_0';
//    }
    return $url;
}

/*
*    版本号比较  by sam 20170412
*    @param string $version1 版本A 如:5.3.2
*    @param string $version2 版本B 如:5.3.0
*    @return int -1版本A小于版本B , 0版本A等于版本B, 1版本A大于版本B
*
*    版本号格式注意：
*        1.要求只包含:点和大于等于0小于等于2147483646的整数 的组合
*        2.boole型 true置1，false置0
*        3.不设位默认补0计算，如：版本号5等于版号5.0.0
*        4.不包括数字 或 负数 的版本号 ,统一按0处理
*
*    @example:
*       if (versionCompare('5.2.2','5.3.0')<0) {
*            echo '版本1小于版本2';
*       }
*/
function versionCompare($versionA,$versionB) {
    if ($versionA>2147483646 || $versionB>2147483646) {
        return -1;
    }
    $dm = '.';
    $verListA = explode($dm, (string)$versionA);
    $verListB = explode($dm, (string)$versionB);

    $len = max(count($verListA),count($verListB));
    $i = -1;
    while ($i++<$len) {
        $verListA[$i] = intval(@$verListA[$i]);
        if ($verListA[$i] <0 ) {
            $verListA[$i] = 0;
        }
        $verListB[$i] = intval(@$verListB[$i]);
        if ($verListB[$i] <0 ) {
            $verListB[$i] = 0;
        }

        if ($verListA[$i]>$verListB[$i]) {
            return 1;
        } else if ($verListA[$i]<$verListB[$i]) {
            return -1;
        } else if ($i==($len-1)) {
            return 0;
        }
    }
}

function checkLogin(){
    $header = Request::instance()->header();

    if(empty($header['uid']))
    {
        return false;
    }else{
        $uid = $header['uid'];
    }

    $userModel = new \app\api\model\User();
    $info = $userModel->where(array('id'=>$uid))->find();
    if (empty($info)){
        return false;
    }
    if ($info['status'] != 0){
        return false;
    }

    //校验token合法性
    if(empty($header['token']))
    {
        return false;
    }

    $accessToken = Cache::get("TOKEN_".$uid);

    if(empty($accessToken))
    {
        return false;
    }

    if($accessToken != $header['token']) {
        return false;
    }

    return $info;
}

/**
 *
 * 验证码位数
 * @param unknown_type $ver_code
 * @param num
 *
 */
function is_vercode( $ver_code, $num=6 ) {
    $num = $num - 1;
    if (preg_match("/^[1-9][0-9]{".$num."}$/", $ver_code)) {
        return true;
    } else {
        return false;
    }
}

//分享图片还原oss
function share_pic($pic){
    return str_replace(ALIYUN,'https://'.config('aliyun_oss.Bucket').'.'.config('aliyun_oss.Endpoint'),$pic);
}




