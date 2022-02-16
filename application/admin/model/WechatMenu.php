<?php
namespace app\admin\model;

use think\Model;

class WechatMenu extends Model
{
    /**
     * 分类树列表
     */
    public function getTree()
    {
        $CategoryTree = db('wechat_menu')->where('is_del',0)->order('order desc')->select();
        return $this->sort($CategoryTree);
    }

    /**
     * 生成树结构
     */
    public function sort($data, $pid = 0, $level = 0)
    {
        static $arr = array();
        foreach ($data as $k => $v) {
            if ($v['pid'] == $pid) {
                $v['level'] = $level;
                $arr[] = $v;
                $this->sort($data, $v['id'], $level + 1);
            }
        }
        return $arr;
    }

    /**
     *生成菜单结构
     */
    public function getMenu()
    {
        $menu = db('wechat_menu')->where('is_del',0)->order('order desc')->select();
        return $this->sorts($menu);
    }

    public function sorts($data, $pid = 0, $level = 0)
    {
        $arr = array();
        foreach ($data as $k => $v) {
            if ($v['pid'] == $pid) {
                $v['sub_button'] = $this->sorts($data, $v['id'], $level + 1);
                if (!empty($v['sub_button']) && $v['sub_button'][0]) {
                    $new = [
                        'name' => $v['title'],
                        'sub_button' => $v['sub_button']
                    ];
                } else {
                    if ($v['type'] == 'click') {
                        $new = [
                            'type' => $v['type'],
                            'name' => $v['title'],
                            'key' => $v['key']
                        ];
                    } elseif ($v['type'] == 'view'){
                        $new = [
                            'type' => $v['type'],
                            'name' => $v['title'],
                            'url' => $v['url']
                        ];
                    }else{
                        $new = [
                            'type' => $v['type'],
                            'name' => $v['title'],
                            'url' => $v['url'],
                            'appid' => $v['appid'],
                            'pagepath' => $v['pagepath'],
                        ];
                    }
                }
                $arr[] = $new;
            }
        }
        return $arr;
    }


}
