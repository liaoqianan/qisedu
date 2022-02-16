<?php
/**
 * Created by PhpStorm.
 * User: hutuo
 * Date: 2017/7/13
 * Time: 11:06
 */
namespace app\common;

class AliAPPPay{
    const APP_ID  = "2019091867525925";
    const RSA_PRIVATE_KEY  = "MIIEowIBAAKCAQEAsWmwC7FjxwPMvRkydqKGvRPesCI3bCbDPmuPWn/c5HY5xGjjBLX5EAh8mfZIqKGT8vHxW7OH8zywK68/DolWAzsXTck3ccbtqCTWu231GAIi13htG2J/L59eTB3eRQ8KCLz7xgIwnRB9LyPmu0UractV0qMCY+Lwl0Xn2sjgTKcCU79Qs9I3K3eP3IevGW0+xa+TtgjFqEvQGieTvyG2SEikpcOQj2V1tR6h1Ni8j3PrwcKhu8Koy/gakd+yM4kGtX+1nAQoFlbSClevEWBiqfmlIj4ORE3Sbjcd+xE90N3OKaY7GEFoJs0qvvSHWapMHfLu8PnUH/J5bNRpK1BkzQIDAQABAoIBADntr3zx7Al0lSp9iruv79zXGxRZ58zZj1DXYBSFwYObktsuAEpufKeejcjb4Pem4p8mHs/5e/+RJljtPOKrNzmQ1tggRolREhKgDLlgevHe6K+Ac+fILo1HNUwXSJc2BOm7g14xItx2INn+l/035agCq8A+V65z0Gkke0M16f880Heb1yoLuVrwnWumlkbicXc4mxcXEUB0PbAyXe3U3zENNCqjgO8nBkGq8mVkm8H9r1j3QfiQKsTZb7xKpR+fZiXCNIeVghtYvEY17H+9ln/5UV7LenpqxVe75TJDYKAeH78pUPx1KaQTiFpsNRtdJC4K9JpGGZy6eTh05MuYjgUCgYEA9WJCfj3x+6aDLamjd+zlDwxuA3Q6xtdBZW/TYiOZm2wbJQEGbQOb4bILGd5BCHlsoajXoKgKR0OMLYz1pzHI1zg74h6JWPXvZZbICO9xmt8gim4T2btjJZ8TLYqg3RDbMNhks/apCe7lF0b7otmszR8ydeDnr4f0denI9q5MSXMCgYEAuRaeGzvbGHKFDMpeffRVE1bMXlaeY8qfXlN5vqOY45RDKUuRusbTgLH2qcdyFuULwnaI2Nvl/htTsYSCSAotsvyVtetx3UTPUP8fBOWZ9WnH8euCIZw/B79GPQyr0wQw/EQGIQEZ9sUbGAbzDFer3WTznqX5F+owsLpA3yr+CL8CgYEApIFbUob002CuUM6JaLzIU25h0q75OJTWosp1TDXRpQC0sfod9LeWqZhTGOSHdMbyrO4koPAG8/+02lTF23Dk1GQ+wTj3m0xFUjXjpRnhb5Jmnmdtp2qY7X/Xu5BUh9/lErn/ySZz7NTbgN91k5ea4Wkoyx4cNPFQUjyJHdd8DUECgYAEi0qcbo7FfeJIWxuQyhPgoqvn0QaYepwJC9GcXZXeWICBngpY5JLCwRvGNVBWwY7VWmWntVZjM7aNUCyGqkFO+KaQTdSA0zSnz2uqihyKiutOxDJwss5VWVvXGd2KE7tEJkNkQguqJFKy91C2R9y/VcApb/e6n5RMcnYvRHXyLwKBgEQcDCEAEA5a2W+WleXozr2oJx705LTTGa7YojJ00rc1OC3aAxOmBAdlQaPC1KhwksKLQQFXZ/7FcYZn54ZrphKxV/rXXsj60Gtv7es6shtV2w0FYznNlDfoL6mPgSMwY+T0emER06WxwNjULWXlIK6VxcZx0sRtYGNtifXVj/Er";
    const RSA_PUBLIC_KEY = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAsWmwC7FjxwPMvRkydqKGvRPesCI3bCbDPmuPWn/c5HY5xGjjBLX5EAh8mfZIqKGT8vHxW7OH8zywK68/DolWAzsXTck3ccbtqCTWu231GAIi13htG2J/L59eTB3eRQ8KCLz7xgIwnRB9LyPmu0UractV0qMCY+Lwl0Xn2sjgTKcCU79Qs9I3K3eP3IevGW0+xa+TtgjFqEvQGieTvyG2SEikpcOQj2V1tR6h1Ni8j3PrwcKhu8Koy/gakd+yM4kGtX+1nAQoFlbSClevEWBiqfmlIj4ORE3Sbjcd+xE90N3OKaY7GEFoJs0qvvSHWapMHfLu8PnUH/J5bNRpK1BkzQIDAQAB";
    const ALI_PUBLIC_KEY = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArAbcRTJ/uJgOx0Lt4y5SuKrZT/9Rh/gMKXkBA3EF8pHgoMba2em0BVFUe/ZlwVgekR83CPdDiH3henxEguO33S5HCp4AyRz3XgylUTP/ypEeSkr/xiM8o4r03sMKMcRQ1hztYxiuD03HSiVyjVtVCSyg08g6LQqwgBwGTIdyxhdz4zIWNaL/Pyhnnk1kSGhdr/hx7h4Tt3JdH7K2zb63Adp3/ZP/xM6SCNpbSU1+4TiJ+ahejzN11GaZc+oUlTrS2Oda6LJvTWefg6aTGmf5UmnZpUIMLjMvsajQHM9O5XeLATbZJnOPv1ht0Zsb+RgIydkmYF6W1+q0YWI6W4wKxwIDAQAB";
    const GATEWAY_URL = "https://openapi.alipay.com/gateway.do";//接口链接

    /**
     * 统一收单交易退款接口
     * @param string $out_trade_no 订单支付时传入的商户订单号
     * @param string $refund_fee 需要退款的金额
     * @return array
     */
    public function payRefundAli($out_trade_no = "", $refund_fee = "0.00")
    {
        vendor('alipays.aop.AopClient');
        vendor('alipays.aop.request.AlipayTradeRefundRequest');

        $aop = new \AopClient ();
        $aop->gatewayUrl = AliAPPPay::GATEWAY_URL; //支付宝网关
        $aop->appId = AliAPPPay::APP_ID;
        $aop->rsaPrivateKey = AliAPPPay::RSA_PRIVATE_KEY;
        $aop->alipayrsaPublicKey = AliAPPPay::ALI_PUBLIC_KEY;

        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset = 'utf-8';
        $aop->format = 'json';
        $request = new \AlipayTradeRefundRequest();
        //TODO 方便多次退款的设置
        $out_request_no = $out_trade_no.rand(1000,9999);
        $request->setBizContent("{" .
            //订单支付时传入的商户订单号,不能和 trade_no同时为空。
            "\"out_trade_no\":\"$out_trade_no\"," .
            //支付宝交易号，和商户订单号不能同时为空
            //"\"trade_no\":\"2019060622001445431042039169\"," .
            //需要退款的金额，该金额不能大于订单金额,单位为元，支持两位小数 c
            "\"refund_amount\":$refund_fee," .
            //标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传
            "\"out_request_no\":\"$out_request_no\"" .
            "  }");
        $result = $aop->execute($request);
        //var_dump($result);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;

        if (!empty($resultCode) && $resultCode == 10000) {
            $status = 1;
            $message = "退款成功";
        } else {
            $status = 0;
            $message = $result->alipay_trade_refund_response->sub_msg;
        }
        //echo $message;
        return ['status'=>$status,'msg'=>$message];
    }
}