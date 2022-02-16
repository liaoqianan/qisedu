<?php
namespace app\api\controller;

use app\common\Common;

class Article extends Base
{
    //学习首页
    public function index()
    {
        $article = db('article')->where('is_del',0)->order('order desc')->field('id,pic,title,brief,like,comment')->select();
        //dump($article);
        return ajaxSuccess('',1,$article);
    }
    //学习详情
    public function details()
    {
        $id = input('post.id/d');
        if (empty($id)){
            return ajaxError('参数错误');
        }
        $article = model('article')->where('id',$id)->where('is_del',0)->find();
        if (empty($article)){
            return ajaxError('文章已下架或不存在',-2);
        }
        model('article')->where('id',$id)->where('is_del',0)->setInc('hits');
        return ajaxSuccess('',1,$article);
    }
    //学习评论
    public function comment()
    {
        $id = input('post.id/d');
        $table = input('post.table/s');
        if (empty($id) || empty($table)){
            return ajaxError('参数错误');
        }

        $comments = db('comments')->where('table',$table)->where('class_id',$id)->where('is_del',0)->select();
        $new_comments = [];
        if(!empty($comments)){
            foreach ($comments as $m){
                $m['is_like'] = 0;
                if($m['user_id']){
                    $m['userinfo'] = db('user')->where('id',$m['user_id'])->field('nickname,headimg')->find();
                    $m['userinfo']['headimg'] = Common::getHeadimg($m['userinfo']['headimg']);
                }

                if($m['superior_user_id']){
                    $m['superior_userinfo'] = db('user')->where('id',$m['superior_user_id'])->field('nickname,headimg')->find();
                    $m['superior_userinfo']['headimg'] = Common::getHeadimg($m['superior_userinfo']['headimg']);
                }
                //$m['comment']=$this->hidtel($m['comment']);
                if($m['superior']==0){
                    $new_comments[$m['id']]=$m;
                    $path=explode("-", $m['path']);

                    $new_comments[$path[1]]['children']=[];
                }else{
                    if(!empty($m['path'])){
                        $path = explode("-", $m['path']);
                    }
                    $new_comments[$path[1]]['children'][]=$m;
                }
                if (input('post.uid')){
                    $res = db('like')->where('user_id',input('post.uid'))->where('table','comments')->where('class_id',$m['id'])->find();
                    if ($res){
                        $new_comments[$m['id']]['is_like'] = 1;
                    }
                }
            }
        }
        $order  = input('post.order');
        if ($order == 1){
            //按点赞顺序重新排序
            array_multisort(array_column($new_comments,'like'),SORT_DESC,SORT_NUMERIC,$new_comments);
        }else{
            //按时间顺序重新排序
            array_multisort(array_column($new_comments,'time'),SORT_DESC,SORT_NUMERIC,$new_comments);
        }
        return ajaxSuccess('',1,$new_comments);
    }
    //过滤评论手机号
    public function hidtel($phone)
    {
        $IsWhat = preg_match('/(0[0-9]{2,3}[\-]?[2-9][0-9]{6,7}[\-]?[0-9]?)/i',$phone);
        if($IsWhat == 1){
            return preg_replace('/(0[0-9]{2,3}[\-]?[2-9])[0-9]{3,4}([0-9]{3}[\-]?[0-9]?)/i','$1****$2',$phone);
        }else{
            return preg_replace('/(1[345678]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$phone);
        }
    }

    public function like()
    {
        $header = new UserBase();
        if(empty($header->uid)){
            return ajaxError('请先登录！');
        }
        $id = input('post.id/d');
        $table = input('post.table/s');
        if (!$id && !$table){
            return ajaxError('参数有误！');
        }
        $like = db('like')->where('user_id',$header->uid)->where('class_id',$id)->where('table',$table)->find();
        if ($like){
            db('like')->where('id',$like['id'])->delete();
            db($table)->where('id',$id)->setDec('like');
            return ajaxSuccess('',0,'取消点赞');
        }else{
            db('like')->insert(['class_id'=>$id,'table'=>$table,'user_id'=>$header->uid]);
            db($table)->where('id',$id)->setInc('like');
            return ajaxSuccess('',1,'点赞成功');
        }
    }

    //添加评论
    public function add_comment()
    {
        $header = new UserBase();
        if(empty($header->uid)){
            return ajaxError('请先登录！');
        }
        $id = input('post.id/d');
        $superior = input('post.superior/d');
        $superior_user_id = input('post.superior_user_id/d');
        $table = input('post.table/s');
        $comment = input('post.comment/s');
        if (empty($id) || empty($table) || empty($comment)){
            return ajaxError('参数错误');
        }
        $data = [];
        $data['user_id']          = $header->uid;
        $data['table']            = $table;
        $data['class_id']         = $id;
        $data['comment']          = $comment;
        $data['superior']         = $superior;
        $data['superior_user_id'] = $superior_user_id;
        $data['time']             = time();
        $comment_id = db('comments')->insertGetId($data);
        if ($comment_id){
            db($table)->where('id',$id)->setInc('comment');
            if($superior !=0) {
                $path =  db('comments')->where('id', $superior)->value('path');
                $parentid = explode("-", $path);
                db('comments')->where('id', $comment_id)->setField('path', $path .'-'. $comment_id);
                foreach ($parentid as $k=>$v){
                    db('comments')->where('id',$v)->setInc('num');
                }
            }else{
                db('comments')->where('id', $comment_id)->setField('path', '0-'. $comment_id);
            }
            return ajaxSuccess('',1,['id'=>$comment_id,'msg'=>'评论成功']);
        }else{
            return ajaxError('评论失败');
        }
    }
}