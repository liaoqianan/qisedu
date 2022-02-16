<?php
/**
 * Created by PhpStorm.
 * User: design_02
 * Date: 2020/12/9
 * Time: 17:18
 */

namespace app\admin\controller;
use app\admin\controller\Calendar;

class Rili
{
    public function index()
    {
        $get = db('user_calendar')->order('id desc')->find();
        $get['text'] = json_decode($get['text'],true);
      dump($get);
    }
}