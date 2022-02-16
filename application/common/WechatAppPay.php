<?php
/**
 * Created by PhpStorm.
 * User: hutuo
 * Date: 2017/7/13
 * Time: 11:06
 */
namespace app\common;

class WechatAppPay{

    //公众账号ID
    private $appid;
    //商户号
    private $mch_id;
    private $notify_url;
    private $key;

    const SSLCERT_PATH = './cert/apiclient_cert.pem';
    const SSLKEY_PATH = './cert/apiclient_key.pem';

    //微信APP支付
    const SSLCERT_APP_PATH = './cert/APP/apiclient_cert.pem';
    const SSLKEY_APP_PATH = './cert/APP/apiclient_key.pem';

    var $parameters;//请求参数，类型为关联数组
    public $response;//微信返回的响应
    public $result;//返回参数，类型为关联数组
    public $data;//接收到的数据，类型为关联数组
    var $returnParameters;//返回参数，类型为关联数组
    var $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";//接口链接
    var $refund_url = "https://api.mch.weixin.qq.com/secapi/pay/refund";//接口链接
    var $transfer_url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";//接口链接(提现)
    var $curl_timeout = 230;//curl超时时间

    public function __construct($appid, $mch_id, $key,$notify_url)
    {
        $this->appid = $appid;
        $this->mch_id = $mch_id;
        $this->notify_url = $notify_url;
        $this->key = $key;
    }

    function trimString($value)
    {
        $ret = null;
        if (null != $value)
        {
            $ret = $value;
            if (strlen($ret) == 0)
            {
                $ret = null;
            }
        }
        return $ret;
    }

    /**
     * 	作用：设置请求参数
     */
    function setParameter($parameter, $parameterValue)
    {
        $this->parameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
    }

    /**
     * 	作用：产生随机字符串，不长于32位
     */
    public function createNoncestr( $length = 32 )
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {
            $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }

    /**
     * 	作用：格式化参数，签名过程需要使用
     */
    function formatBizQueryParaMap($paraMap, $urlencode)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v)
        {
            if($urlencode)
            {
                $v = urlencode($v);
            }
            //$buff .= strtolower($k) . "=" . $v . "&";
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar;
        if (strlen($buff) > 0)
        {
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }

    /**
     * 	作用：生成签名
     */
    public function getSign($Obj, $type='wx')
    {
        foreach ($Obj as $k => $v)
        {
            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        //echo '【string1】'.$String.'</br>';
        //签名步骤二：在string后加入KEY

        $String = $String."&key=".$this->key;

        //echo "【string2】".$String."</br>";
        //签名步骤三：MD5加密
        $String = md5($String);
        //echo "【string3】 ".$String."</br>";
        //签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);
        //echo "【result】 ".$result_."</br>";
        return $result_;
    }

    /**
     * 	作用：array转xml
     */
    function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            if (is_numeric($val))
            {
                $xml.="<".$key.">".$val."</".$key.">";

            }
            else
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
        }
        $xml.="</xml>";
        return $xml;
    }

    /**
     * 	作用：将xml转为array
     */
    public function xmlToArray($xml)
    {
        //将XML转为array
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }

    /**
     * 	作用：设置标配的请求参数，生成签名，生成接口参数xml
     */
    function createXml()
    {
        $this->parameters["appid"] = $this->appid;//公众账号ID
        $this->parameters["mch_id"] = $this->mch_id;//商户号
        $this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
        $this->parameters["sign"] = $this->getSign($this->parameters);//签名
        return  $this->arrayToXml($this->parameters);
    }

    /**
     * 将xml数据返回微信
     */
    function returnXml()
    {
        $returnXml = $this->arrayToXml($this->returnParameters);
        return $returnXml;
    }

    /**
     * 	作用：以post方式提交xml到对应的接口url
     */
    public function postXmlCurl($xml,$url,$second=30)
    {
        //初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //curl_close($ch);
        //返回结果
        if($data)
        {
            curl_close($ch);
            return $data;
        }
        else
        {
            $error = curl_errno($ch);
            echo "curl出错，错误码:$error"."<br>";
            echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
            curl_close($ch);
            return false;
        }
    }

    /**
     * 	作用：post请求xml
     */
    function postXml()
    {
        $xml = $this->createXml();
        $this->response = $this->postXmlCurl($xml,$this->url,$this->curl_timeout);
        return $this->response;
    }

    /**
     *
     * 获取支付结果通知数据
     * return array
     */
    public function getNotifyData()
    {
        //获取通知的数据
        $xml = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");
        $data = array();
        if (empty($xml)) {
            return false;
        }
        $data = $this->xmlToArray($xml);
        if (!empty($data['return_code'])) {
            if ($data['return_code'] == 'FAIL') {
                return false;
            }
        }
        return $data;
    }

    /**
     * 接收通知成功后应答输出XML数据
     * @param string $xml
     */
    public function replyNotify()
    {
        $data['return_code'] = 'SUCCESS';
        $data['return_msg'] = 'OK';
        $xml = $this->arrayToXml($data);
        echo $xml;
        die();
    }

    /**
     * 获取prepay_id
     */
    function getPrepayId()
    {
        $this->postXml();
        $this->result = $this->xmlToArray($this->response);
        $prepay_id = $this->result["prepay_id"];
        return $prepay_id;
    }

    /**
     * 执行退款
     */
    function doRefund(){
        $xml = $this->createXml();
        $this->response = $this->postXmlSSLCurl($xml,$this->refund_url,$this->curl_timeout);
        $this->result = $this->xmlToArray($this->response);
        return $this->result;
    }

    function saveData($xml)
    {
        $this->data = $this->xmlToArray($xml);
    }

    function checkSign()
    {
        $tmpData = $this->data;
        unset($tmpData['sign']);
        $sign = $this->getSign($tmpData);//本地签名
        if ($this->data['sign'] == $sign) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 设置返回微信的xml数据
     */
    function setReturnParameter($parameter, $parameterValue)
    {
        $this->returnParameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
    }

    //需要使用证书的请求
    function postXmlSSLCurl($xml,$url,$second=30)

    {

        $ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);

        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');

        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);

        curl_setopt($ch,CURLOPT_URL, $url);

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);

        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);

        //设置header

        curl_setopt($ch,CURLOPT_HEADER,FALSE);

        //要求结果为字符串且输出到屏幕上

        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);

        //设置证书

        //使用证书：cert 与 key 分别属于两个.pem文件

        //默认格式为PEM，可以注释

        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');

        curl_setopt($ch,CURLOPT_SSLCERT, self::SSLCERT_PATH);

        //默认格式为PEM，可以注释

        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');

        curl_setopt($ch,CURLOPT_SSLKEY, self::SSLKEY_PATH);

        //post提交方式

        curl_setopt($ch,CURLOPT_POST, true);

        curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);

        $data = curl_exec($ch);

        //返回结果

        if($data){

            curl_close($ch);

            return $data;

        }

        else {

            $error = curl_errno($ch);

            echo "curl出错，错误码:$error"."<br>";

            curl_close($ch);

            return false;

        }

    }

    //需要使用证书的请求
    function postXmlSSLCurlByAPP($xml,$url,$second=30)

    {

        $ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);

        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');

        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);

        curl_setopt($ch,CURLOPT_URL, $url);

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);

        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);

        //设置header

        curl_setopt($ch,CURLOPT_HEADER,FALSE);

        //要求结果为字符串且输出到屏幕上

        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);

        //设置证书

        //使用证书：cert 与 key 分别属于两个.pem文件

        //默认格式为PEM，可以注释

        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');

        curl_setopt($ch,CURLOPT_SSLCERT, self::SSLCERT_APP_PATH);

        //默认格式为PEM，可以注释

        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');

        curl_setopt($ch,CURLOPT_SSLKEY, self::SSLKEY_APP_PATH);

        //post提交方式

        curl_setopt($ch,CURLOPT_POST, true);

        curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);

        $data = curl_exec($ch);

        //返回结果

        if($data){

            curl_close($ch);

            return $data;

        }

        else {

            $error = curl_errno($ch);

            echo "curl出错，错误码:$error"."<br>";

            curl_close($ch);

            return false;

        }

    }

    /**
     *提现 (转账)
     */
    function doTransfers(){
        $this->parameters["mch_appid"] = $this->appid;//公众账号ID
        $this->parameters["mchid"] = $this->key;//商户号
        $this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
        $this->parameters["sign"] = $this->getSign($this->parameters);//签名
        $xml =   $this->arrayToXml($this->parameters);
        $this->response = $this->postXmlSSLCurl($xml,$this->transfer_url,$this->curl_timeout);
        $this->result = $this->xmlToArray($this->response);
        return $this->result;
    }
}