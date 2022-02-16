<?php

return [
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter' => 'htmlspecialchars,addslashes,strip_tags',

    // 微信H5支付参数
    'wechat_app_pay' => [
        // 应用id
        'appid' => 'wxe5838beab0b6f7cf',
        'appsecret'=>'fc54d718ce49a22201b60ec37fb517f1',
        //课程预约模板id
        'model'=>'64jEQWCE3M9p4kPmNqUD-t41-edfR86yHszmAXgDa_E',
        //支付成功模板id
        'Paymodel'=>'9Z2wmNfQhz93Y61V47njDhSbeMJ6nRc9ZQ2qB7IL3_I',
        // 商户号
        'mch_id' => '10029213',
        // 商户key
        'key' => 'kw24yijns9804axb8209844mmkjh172p',
        // 异步通知地址
        'notify_url' => url('Notify/notify_url_for_app'),
        // 公众号开发token
        'token' => 'cooov_wx_callback_api_token',
        // 消息加解密密钥
        'encodingaeskey' => 'cpNiyYdqqZlE2QYOf5PPKbxWGkcxcKnaHisU824WBVZ',
    ],
    // 微信app支付参数
    'wechat_pay' => [
        // 应用id
        'appid' => 'wx8f95a89e4bf167fa',
        'appsecret'=>'3dcea329f80b6551b197c17d0861b756',
        // 商户号
        'mch_id' => '10029213',
        // 商户key
        'key' => 'kw24yijns9804axb8209844mmkjh172p',
        // 异步通知地址
        'notify_url' => url('Notify/notify_url_for_app'),
    ],
    // 微信小程序支付参数
    'wechat_routine_pay' => [
        // 应用id
        'appid' => 'wxd01c53b5b22439cc',
        'appsecret'=>'aa3b787a377051bf63ffb9d24b5ba790',
        // 商户号
        'mch_id' => '10029213',
        // 商户key
        'key' => '8324yijns9804axb8209844mmkjh172p',
        // 异步通知地址
        'notify_url' => url('notify/notify_url_for_routine')
    ],

    //APP支付宝支付
    'alipay_for_app' => [
        //应用ID,您的APPID。
        'app_id' => "2019032963748521",

        //商户私钥, 请把生成的私钥文件中字符串拷贝在此
        'merchant_private_key' => "MIIEowIBAAKCAQEAtFXZlDbJnYIgBDzCENS1ryxDOwOyAfZUoG0EE4btm5ZKYm3gC4256W2mhhDG4EA4a8O4hW+tVxnW5H075/G28BRJ/9Ym3zipym8Ldof5N7Xxz/tLTaytqTwG5iySNnm11yJEVUP8x3z/fYD4MJCEtU9W3JBPIAFZbqE37PVqJndmXiwCGe4mD51cVvRCmbf5uU7hXQn5SkLZVtISMBtjI15lsLsuJEnSS/sSa0uug7uGU1kD6xcB0vE54KOBERWOdfZwqQRwtWCo3dP5i2+thXVa48GZ1z9SyV7EAPUH/rL5aV4LV/e+QUF1aKdyMwlLvuUxjW/br3WdigvtISGydQIDAQABAoIBAFUdP/hoc3hX9myJkL8I5keriH/OGrI6ZW7ihU4CHRuWL72NRnWtitXV3wbf3D3zka2rTugL8bBujbKCOUgcGc/ug46wsOAiimD1UF+9ha75vQwA+2XJ3p1Fq6vv1YLIpz7G+aGU2TX+lUfTG1EwpLI+pCyuH+pnOKKyjQbWPiew4Dgw3k85VeQhC353AhCPx9IEkldlbhHES9JJElWeE45v0CFGMB6EsGBQFFod7pSXRaxi7ug9sh0SPgAXneUpQ0aGNArLFuvpfrCOLSr/nVCoISD/Ki0RAg8FIEwnR8y7/waYR34NH/5tMFZYOHlHzf8Nts55NPTDUN7+niIYkwECgYEA3qlfu+ZPSyhrqz3d4xdRUCaGJJQKy4C+65psRfQoJwfjWdADfkGQvpq5XxYuoSa0/fVldXwTy8iCf/INYFaDDifgansz7DAB0CmmiZ4kOUbWwWkvPYw+EBBRbqum45m7/f98o8eLxlR09Gxu+kyz4AyAzwwHqJNa/tEqhI7CgUUCgYEAz1Yb52N1w0EMrwQ16url2SFUmkHMYkPZ9IBFlOTIFuSqCLlNtPHg2FO7SgpxbkYJECIGy9EhYh0RT+CzvYHN67trUGzJhLHcKPdXEaJwKi/PSDJvC2H8iKxYuAIdvkQG8Mxwf+j44QUnfHyhONKRWLQ/lwIeYWg+MbjJEStCx3ECgYAivGmi9psILpYdcNlcxBZ7WocyzaWhecRPHRgnEZ/x9tkQ+dKRr8jU825X1y4PFerGc7IQUfsEn5M8QpazoMtxOedYtT+0aQjfYKAKeBFOni6CndYw0+AawXDywvSWLTWWunTHWCoGFsboGsJ1aeHl4g12P3oyYkqf1iG77EmLwQKBgDaVH7FxoJ3vcQxeGmemwS3BNYmKiujPngBdCHRjKj3EovK6/bcF+kBZArRCPYxfSaGCo7Fic9xldJpHuGnKU1CcvWqlyQNy41exja1pyweAIJyMEmm3uQit/okqnXPqar1XxMIXrfVnKvb9xF+2J2dnxFnXx/GyxLTIRb6MWf8xAoGBAMDQ10ssrS+n9nLd/v2uHp4X8WhbyBDns5DT/1mZb3nsQCygUYp7ILEXKos52ALx79rZGUFgBMZJeEXJLtXhaGzKS2HdgGHHUXDQWoxNRW0bIfvs9QkFWjiEv5aqrUetkt/acvGvLNx3fmSfyNVLeFzhTI9RR5kmnu/9AmxYu+du",

        //异步通知地址
        'notify_url' => url('alipay/notify_url'),

        //同步跳转
        'return_url' => request()->server('REQUEST_SCHEME') . '://' . request()->server('SERVER_NAME') . '/' . url('alipay/return_url'),

        //编码格式
        'charset' => "UTF-8",

        //签名方式
        'sign_type' => 'RSA2',

        //支付宝网关
        'gatewayUrl' => 'https://openapi.alipay.com/gateway.do',

        //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
        'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAsloRh05MBufcIK19VdY5hH5x4nWLGS9q1mOd44I0JRS9/gVzZmFKS9LfATr6tsn0M6/RrhqN8951VVcIh5e72hAs3deoEXFP0qtg9GqJiSSa30PoabVIDbGRAbU0UE0nw7BAZK/pSPUMCEysQmE99NaHUI7htP/XjUi4jCmAHgFzAdgdiPLDKqFWfY6neey9sluSbXxEbhiQWIIGvVwWt1f4cpb4YKMf6VD+tux5XFAT63ApLk5gOe16fAmqOb1knOWdx+SUIB6IxZRB4ScgdQAguVCcdXoxIHdctcIznWBMm2ceWmwfKx3kMsXaCTibFPp84RNuVDgHHmOz/E6mtQIDAQAB",
    ],
];

