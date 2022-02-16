<?php

namespace app\admin\model;

use think\Model;

class User extends Model {
    /*
     * 获取一条目录信息
     */
    public function getOneByData($data)
    {
        if(empty($data))
        {
            return [];
        }

        return $this->get($data);
    }

    /*
     * 根据查询条件获取列表
     */
    public function getListByWhere($where, $field='*')
    {
        return $this->where($where)->field($field)->select();
    }

    /*
     * 根据查询条件保存数据
     */
    public function saveDataByWhere($data, $where)
    {
        if(empty($data) && empty($where))
        {
            return false;
        }

        $this->save($data, $where);
        return true;

    }

    public static function getUserList($where,$start,$limit, $excel = ''){
        $total = self::alias('a')
               ->join('User b', 'a.refer_id=b.id', 'left')
               ->where($where)
               ->count();

        if($excel)
        {
            $listInfo = self::alias('a')
                ->join('User b', 'a.refer_id=b.id', 'left')
                ->field('a.id,
                    a.nickname,
                    a.headimg,
                    a.mobile,
                    a.money,
                    a.add_time,
                    a.last_time,
                    a.refer_id,
                    a.sub_time,
                    a.is_sub,
                    b.nickname as refer_name')
                ->where($where)
                ->limit(0, 10000)
                ->order('a.id desc')
                ->select();
        }else{
            $listInfo = self::alias('a')
                ->join('User b', 'a.refer_id=b.id', 'left')
                ->field('a.id,
                    a.nickname,
                    a.headimg,
                    a.mobile,
                    a.money,
                    a.last_time,
                    a.add_time,
                    a.refer_id,
                    a.sub_time,
                    a.is_sub,
                    b.nickname as refer_name')
                ->where($where)
                ->limit($start, $limit)
                ->order('a.id desc')
                ->select();
        }

        if ($listInfo) {
            foreach ($listInfo as &$v) {
                $v['refer_name'] = $v['refer_name'] == '' ? '暂无' : $v['refer_name'].' / '.$v['refer_id'];
                $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['last_time'] = date('Y-m-d H:i:s', $v['last_time']);
                if ($v['sub_time']){
                    $v['sub_time'] = date('Y-m-d H:i:s', $v['sub_time']);
                }else{
                    $v['sub_time'] = null;
                }
                if ($v['is_sub'] == 'nosub'){
                    $v['is_sub'] = '未关注';
                }elseif($v['is_sub'] == 'sub'){
                    $v['is_sub'] = '关注';
                }else{
                    $v['is_sub'] = '取消关注';
                }
                $v['nickname'] = static::emojiDecode($v['nickname']);
            }
        }
        return ['total'=>$total,'data'=>$listInfo];
    }
    //字符串转表情
    public static function emojiDecode($content) {
        return json_decode(preg_replace_callback('/\\\\\\\\/i', function() {
            return '\\';
        }, json_encode($content)));
    }
    public function getUidData($uid){
        if (!$uid) {
            return false;
        }
         return $this->where(['id'=>$uid])->find();
    }

    public static function editUser($data = [],$uid){
        if (!$uid || empty($data)) {
            return false;
        }
        $user = self::get($uid);
        if (empty($user)) {
            return false;
        }
        $bool = self::where(['id'=>$uid])->update($data);

        return $bool;
    }
}