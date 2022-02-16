<?php


namespace app\common;


class Common
{
    public static function getHeadimg($img = ''){
        if(empty($img)){
            return config('API_URL').'/static/admin/images/headimg.png';
        }else{
            return strpos($img,'http') !== false ? $img :config('API_URL').$img;
        }
    }

}