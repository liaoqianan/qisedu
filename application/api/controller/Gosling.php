<?php
namespace app\api\controller;

use app\common\HttpCurl;
use think\Cache;

class Gosling //extends Base
{
    private $client_id = 'xoptNhAjW4t6960';
    private $client_sercet = 'xwbgOQBCNcQxJHixC9VEGSILxVf4eT7N';
    private $app_id = 'appTTxeukvc3994';
    /*private $client_id = 'xopamXVXexg8827';
    private $client_sercet = 'FZCCmhpkcHXu9ceXaaAEgRuQTldwI2jK';
    private $app_id = 'appmreby6zf2873';*/
    public function get_access_token()
    {
        $data = [
            'app_id' => $this->app_id,
            'client_id' => $this->client_id,
            'secret_key' => $this->client_sercet,
            "grant_type" => "client_credential"
        ];

        $XET_token = Cache::get('XET_token');
        if ($XET_token){
            return $XET_token;
        }else{
            //dump($data);exit;
            $url = "https://api.xiaoe-tech.com/token";
            $res = json_decode(HttpCurl::curlRequest($url,$data,"GET"), true);
            Cache::set('XET_token',$res['data']['access_token'],7000);
            return $res['data']['access_token'];
        }
    }
    //注册用户
    public function register()
    {
        try{
            $user = db('user')->where('id',42)->find();
            $url = 'https://api.xiaoe-tech.com/xe.user.register/1.0.0';
            $data['access_token'] = $this->get_access_token();;
            $data['data']['wx_union_id'] = $user['unionid'];
            $data['data']['phone'] = $user['mobile'];
            $data['data']['avatar'] = $user['headimg'];
            $data['data']['nickname'] = $user['nickname'];
            $res = json_decode(HttpCurl::curlRequest($url,$data,'POST'), true);
            if ($res['code'] == 0){
                return ajaxSuccess('',1,$res['data']);
            }else{
                return ajaxError($res['msg']);
            }
            //;
        }catch (\Exception $e){
            return ajaxError($e->getMessage());
        }
    }
    //获取用户信息
    public function get_user_info()
    {
        try{
            $user = db('user')->where('id',42)->find();
            $url = 'https://api.xiaoe-tech.com/xe.user.info.get/1.0.0';
            $data['access_token'] = $this->get_access_token();
            $data['user_id'] = 'u_api_604b0a77ee170_w9wGPsK8gM';
            $data['data']['phone'] = $user['mobile'];
            $data['data']['wx_union_id'] = $user['unionid'];
            $data['data']['field_list'] = ['wx_union_id','wx_open_id','wx_app_open_id','nickname','name','avatar','gender',
                'city','province','country','phone','birth','address','company','job','phone_collect'];
            $res = json_decode(HttpCurl::curlRequest($url,$data,'POST','josn'), true);
            if ($res['code'] == 0){
                return ajaxSuccess('',1,$res['data']);
            }else{
                return ajaxError($res['msg']);
            }
        }catch (\Exception $e){
            return ajaxError($e->getMessage());
        }
    }
    //批量获取用户数据
    public function get_user_info_list()
    {
        $url = 'http://api.xiaoe-tech.com/xe.user.batch.get/1.0.0';
        $data['access_token'] = $this->get_access_token();
        $data['page'] = 1; //第几页
        $data['page_size'] = 50; //每页最大条数最大50条
        $res = json_decode(HttpCurl::curlRequest($url,$data,'POST','josn'), true);
        if ($res['code'] == 0){
            return ajaxSuccess('',1,$res['data']);
        }else{
            return ajaxError($res['msg']);
        }
    }

    //获取用户学习数据
    public function get_user_records()
    {
        $url = 'https://api.xiaoe-tech.com/xe.user.learning.records.get/1.0.0';
        $data['access_token'] = $this->get_access_token();
        $data['shop_id'] = $this->app_id;
        $data['user_id'] = 'u_604ed54a6c95c_CqEFKJy5LT';
        $data['data']['page'] = 1; //第几页
        $data['data']['page_size'] = 50; //默认10每页最大条数最大50条
        $res = json_decode(HttpCurl::curlRequest($url,$data,'POST','josn'), true);
        if ($res['code'] == 0){
            return ajaxSuccess('',1,$res['data']);
        }else{
            return ajaxError($res['msg']);
        }

    }

    //查看商品列表
    public function goods_lists()
    {
        $url = 'http://api.xiaoe-tech.com/api/xe.goods.list.get/3.0.0';
        $data['access_token'] = $this->get_access_token();
        $data['resource_type'] = 1;
        $res = json_decode(HttpCurl::curlRequest($url,$data,'POST','josn'), true);
        if ($res['code'] == 0){
            return ajaxSuccess('',1,$res['data']);
        }else{
            return ajaxError($res['msg']);
        }
    }
    //商品详情
    public function goods_detail()
    {
        $url = 'https://api.xiaoe-tech.com/xe.goods.detail.get/3.0.0';
        $data['access_token'] = $this->get_access_token();
        $data['data']['goods_id'] = 'i_6041fae7e4b0e51d821b529c';//商品id
        $data['data']['goods_type'] = 1;
        $res = json_decode(HttpCurl::curlRequest($url,$data,'POST','josn'), true);
        if ($res['code'] == 0){
            return ajaxSuccess('',1,$res['data']);
        }else{
            return ajaxError($res['msg']);
        }
    }
    //拉取专栏的资源列表
    public function goods_relation()
    {
        $url = 'https://api.xiaoe-tech.com/xe.goods.detail.get/3.0.0';
        $data['access_token'] = $this->get_access_token();
        $data['data']['goods_id'] = 'i_6041fae7e4b0e51d821b529c';//专栏id
        $data['data']['goods_type'] = 5;//类型;会员-5，专栏-6，大专栏-8
        $data['data']['page_size'] = 20;//页容量：每次获取资源条数
        $data['data']['resource_type'] = 1;//图文-1，音频-2，视频-3，直播-4，专栏-6，电子书-20
        $res = json_decode(HttpCurl::curlRequest($url,$data,'POST','josn'), true);
        if ($res['code'] == 0){
            return ajaxSuccess('',1,$res['data']);
        }else{
            return ajaxError($res['msg']);
        }
    }
    //查询用户订单
    public function user_orders()
    {
        $url = 'http://api.xiaoe-tech.com/xe.get.user.orders/1.0.0';
        $data['access_token'] = $this->get_access_token();
        $data['user_id'] = 'u_604ed54a6c95c_CqEFKJy5LT';
    }
}
