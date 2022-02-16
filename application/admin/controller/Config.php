<?php


namespace app\admin\controller;
use think\Db;
use think\Exception;
use think\Request;

class Config extends Base
{
    public function index(Request $request)
    {
        if($request->isGet()){
            $info = collection(model('Config')->select())->toArray();
            $info = array_column($info,'content','type');
            $this->assign('detail',$info);
            return $this->fetch('Config/index');
        }else{
            $third_clause = input('post.third_clause');
            $third_explain = input('post.third_explain');
            $privacy_policy = input('post.privacy_policy');
            $privacy_strategy = input('post.privacy_strategy');
            $user_protocol = input('post.user_protocol');
            $about_us = input('post.about_us');
            $feedback = input('post.feedback');
            $help_center = input('post.help_center');
            $wechat_center = input('post.wechat');
            Db::startTrans();
            try{
                model('Config')->where('type','third_clause')->update(['content'=>$third_clause]);
                model('Config')->where('type','third_explain')->update(['content'=>$third_explain]);
                model('Config')->where('type','privacy_policy')->update(['content'=>$privacy_policy]);
                model('Config')->where('type','privacy_strategy')->update(['content'=>$privacy_strategy]);
                model('Config')->where('type','user_protocol')->update(['content'=>$user_protocol]);
                model('Config')->where('type','about_us')->update(['content'=>$about_us]);
                model('Config')->where('type','feedback')->update(['content'=>$feedback]);
                model('Config')->where('type','help_center')->update(['content'=>$help_center]);
                model('Config')->where('type','wechat')->update(['content'=>$wechat_center]);

                Db::commit();
                return $this->ajaxSuccess('操作成功');
            }catch (Exception $e){
                Db::rollback();
                return $this->ajaxError('操作失败'.$e->getMessage());
            }
        }
    }
}