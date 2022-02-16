<?php
/**
 * Created by PhpStorm.
 * User: design_02
 * Date: 2020/12/7
 * Time: 10:46
 */

namespace app\api\controller;


use phpseclib\Crypt\AES;

class Calendar extends UserBase
{
    private $months = [1 => '正月', 2 => '二月', 3 => '三月', 4 => '四月', 5 => '五月', 6 => '六月', 7 => '七月', 8 => '八月', 9 => '九月', 10 => '十月', 11 => '冬月', 12 => '腊月'];
    private $arr_n = ["〇", "一", "二", "三", "四", "五", "六", "七", "八", "九"];
    private $gan = [1 => '甲', 2 => '乙', 3 => '丙', 4 => '丁', 5 => '戊', 6 => '己', 7 => '庚', 8 => '辛', 9 => '壬', 10 => '癸'];
    private $zhi = [1 => '子', 2 => '丑', 3 => '寅', 4 => '卯', 5 => '辰', 6 => '巳', 7 => '午', 8 => '未', 9 => '申', 10 => '酉', 11 => '戌', 12 => '亥'];
    private $color = [1 => '甲', 2 => '乙', 3 => '丙', 4 => '丁', 5 => '戊', 6 => '己', 7 => '庚', 8 => '辛', 9 => '壬', 10 => '癸', 11 => '子', 12 => '丑', 13 => '寅', 14 => '卯', 15 => '辰', 16 => '巳', 17 => '午', 18 => '未', 19 => '申', 20 => '酉', 21 => '戌', 22 => '亥'];
    private $GZ = [
        1 => '甲子', 2 => '乙丑', 3 => '丙寅', 4 => '丁卯', 5 => '戊辰', 6 => '己巳', 7 => '庚午', 8 => '辛未', 9 => '壬申', 10 => '癸酉',
        11 => '甲戌', 12 => '乙亥', 13 => '丙子', 14 => '丁丑', 15 => '戊寅', 16 => '己卯', 17 => '庚辰', 18 => '辛巳', 19 => '壬午', 20 => '癸未',
        21 => '甲申', 22 => '乙酉', 23 => '丙戌', 24 => '丁亥', 25 => '戊子', 26 => '己丑', 27 => '庚寅', 28 => '辛卯', 29 => '壬辰', 30 => '癸巳',
        31 => '甲午', 32 => '乙未', 33 => '丙申', 34 => '丁酉', 35 => '戊戌', 36 => '己亥', 37 => '庚子', 38 => '辛丑', 39 => '壬寅', 40 => '癸卯',
        41 => '甲辰', 42 => '乙巳', 43 => '丙午', 44 => '丁未', 45 => '戊申', 46 => '己酉', 47 => '庚戌', 48 => '辛亥', 49 => '壬子', 50 => '癸丑',
        51 => '甲寅', 52 => '乙卯', 53 => '丙辰', 54 => '丁巳', 55 => '戊午', 56 => '己未', 57 => '庚申', 58 => '辛酉', 59 => '壬戌', 60 => '癸亥'
    ];
    private $term_name = [
        1 => "立春", 2 => "雨水", 3 => "惊蛰", 4 => "春分", 5 => "清明", 6 => "谷雨", 7 => "立夏", 8 => "小满",
        9 => "芒种", 10 => "夏至", 11 => "小暑", 12 => "大暑", 13 => "立秋", 14 => "处暑", 15 => "白露", 16 => "秋分",
        17 => "寒露", 18 => "霜降", 19 => "立冬", 20 => "小雪", 21 => "大雪", 22 => "冬至", 23 => "小寒", 24 => "大寒"
    ];
    private $zodiac = [1=>'鼠',2=>'牛',3=>'虎',4=>'兔',5=>'龙',6=>'蛇',7=>'马',8=>'羊',9=>'猴',10=>'鸡',11=>'狗',12=>'猪'];
    private $shishen = [1 => '比肩', 2 => '劫财', 3 => '食神', 4 => '伤官', 5 => '偏财', 6 => '正财', 7 => '七杀', 8 => '正官', 9 => '偏印', 10 => '正印'];
    private $brief_shishen = [1 => '比', 2 => '劫', 3 => '食', 4 => '伤', 5 => '才', 6 => '财', 7 => '杀', 8 => '官', 9 => '枭', 10 => '印'];
    private $code = [
        1 => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
        2 => [2, 1, 4, 3, 6, 5, 8, 7, 10, 9],
        3 => [9, 10, 1, 2, 3, 4, 5, 6, 7, 8],
        4 => [10, 9, 2, 1, 4, 3, 6, 5, 8, 7],
        5 => [7, 8, 9, 10, 1, 2, 3, 4, 5, 6],
        6 => [8, 7, 10, 9, 2, 1, 4, 3, 6, 5],
        7 => [5, 6, 7, 8, 9, 10, 1, 2, 3, 4],
        8 => [6, 5, 8, 7, 10, 9, 2, 1, 4, 3],
        9 => [3, 4, 5, 6, 7, 8, 9, 10, 1, 2],
        10 => [4, 3, 6, 5, 8, 7, 10, 9, 2, 1]
    ];
    private $CS_code = [1 => '长生', 2 => '沐浴', 3 => '冠带', 4 => '临官', 5 => '帝旺', 6 => '衰', 7 => '病', 8 => '死', 9 => '墓', 10 => '绝', 11 => '胎', 12 => '养'];
    private $changsheng = [
        1 => [12 => 1, 1 => 2, 2 => 3, 3 => 4, 4 => 5, 5 => 6, 6 => 7, 7 => 8, 8 => 9, 9 => 10, 10 => 11, 11 => 12],
        3 => [3 => 1, 4 => 2, 5 => 3, 6 => 4, 7 => 5, 8 => 6, 9 => 7, 10 => 8, 11 => 9, 12 => 10, 1 => 11, 2 => 12],
        5 => [3 => 1, 4 => 2, 5 => 3, 6 => 4, 7 => 5, 8 => 6, 9 => 7, 10 => 8, 11 => 9, 12 => 10, 1 => 11, 2 => 12],
        7 => [6 => 1, 7 => 2, 8 => 3, 9 => 4, 10 => 5, 11 => 6, 12 => 7, 1 => 8, 2 => 9, 3 => 10, 4 => 11, 5 => 12],
        9 => [9 => 1, 10 => 2, 11 => 3, 12 => 4, 1 => 5, 2 => 6, 3 => 7, 4 => 8, 5 => 9, 6 => 10, 7 => 11, 8 => 12],
        2 => [7 => 1, 6 => 2, 5 => 3, 4 => 4, 3 => 5, 2 => 6, 1 => 7, 12 => 8, 11 => 9, 10 => 10, 9 => 11, 8 => 12],
        4 => [10 => 1, 9 => 2, 8 => 3, 7 => 4, 6 => 5, 5 => 6, 4 => 7, 3 => 8, 2 => 9, 1 => 10, 12 => 11, 11 => 12],
        6 => [10 => 1, 9 => 2, 8 => 3, 7 => 4, 6 => 5, 5 => 6, 4 => 7, 3 => 8, 2 => 9, 1 => 10, 12 => 11, 11 => 12],
        8 => [1 => 1, 12 => 2, 11 => 3, 10 => 4, 9 => 5, 8 => 6, 7 => 7, 6 => 8, 5 => 9, 4 => 10, 3 => 11, 2 => 12],
        10 => [4 => 1, 3 => 2, 2 => 3, 1 => 4, 12 => 5, 11 => 6, 10 => 7, 9 => 8, 8 => 9, 7 => 10, 6 => 11, 5 => 12]
    ];

    /**
     * 首页
     * */
    public function index()
    {
        $calendar = db('calendar')->where('solar_year', date('Y'))->where('solar_month', date('m'))->where('solar_day', date('d'))->find();
        if (!$calendar) {
            ajaxError('当前日期有误！');
        }
//获取时辰
        $getPeriodByHour = $this->getPeriodByHour(date('H:i:s', time()));
        $Lcalendar = $calendar['lunar_year'] . '年' . $calendar['lunar_month'] . $calendar['lunar_day'] . '日' . $getPeriodByHour . '时';
        $gan = array_search($getPeriodByHour, $this->zhi);//时支数所在位置
        $Scalendar = $calendar['ganzhi_year'] . $calendar['ganzhi_month'] . $calendar['ganzhi_day'];
        $rigan = ($calendar['ri'] * 2 + $gan - 2) % 10;
        if ($rigan == 0) {
            $rigan = 10;
        }
//时柱
        $ganzhi_shi = $this->gan[$rigan] . $getPeriodByHour;
        $Scalendar = $Scalendar . $ganzhi_shi;
        $user_calendar = db('user_calendar')->where('is_del', 0)->where('uid', $this->uid)->field('id,name,G_calendar,sex')->select();
        foreach ($user_calendar as &$value) {
            $G_calendar = explode('-', $value['G_calendar']);
            $value['G_calendar'] = $G_calendar[0] . '年' . $G_calendar[1] . '月' . explode(' ', $G_calendar[2])[0] . '日';
        }
        $Scalendar = $this->mbStrSplit($Scalendar);
//获取天干地支的颜色
        $arr = [];
        foreach ($Scalendar as &$value) {
            $arr[] = [array_search($value, $this->color), $value];
        }
        $data = [
            'Gcalendar' => date('Y-m-d H:i', time()),
            'Lcalendar' => $Lcalendar,
            'Scalendar' => $arr,
            'user_calendar' => $user_calendar
        ];
        return ajaxSuccess('', 1, ['data' => $data]);
    }

    public function add()
    {
        if (request()->isPost()) {
            //try{
                $data = input('post.');
                //真太阳时
                if (!empty($data['G_calendar'])) {
                    $calen = $data['G_calendar'];
                    if ($data['type']) {
                        $L_calendar = explode('-', $data['sun_calendar']);
                    } else {
                        $L_calendar = explode('-', $data['G_calendar']);
                    }
                    $G_calendar = explode('-', $data['G_calendar']);
                    $calendar = db('calendar')->where('solar_year', $G_calendar[0])->where('solar_month', $G_calendar[1])->where('solar_day', explode(' ', $G_calendar[2])[0])->find();

                    $calendars = db('calendar')->where('solar_year', $L_calendar[0])->where('solar_month', $L_calendar[1])->where('solar_day', explode(' ', $L_calendar[2])[0])->find();

                    //获取时辰
                    $getPeriodByHour = $this->getPeriodByHour(explode(' ', $L_calendar[2])[1]);
                    $gan = array_search($getPeriodByHour, $this->zhi);//时支数所在位置
                    //农历日期
                    $Lcalendar = $calendars['lunar_year'] . '年' . $calendars['lunar_month'] . $calendars['lunar_day'] . '日' . $getPeriodByHour . '时';
                    //公历日期
                    $Gcalendar = $data['G_calendar'];
                } elseif (!empty($data['L_calendar'])) {
                    $calen = $data['L_calendar'];
                    $L_calendar = explode('-', $calen);
                    //将年份和日期转换成中文
                    $dateToChinese = explode('/', $this->dateToChinese($calen));
                    $calendar = db('calendar')->where('lunar_year', $dateToChinese[0])->where('lunar_month', $this->months[$L_calendar[1] * 1])->where('lunar_day', $dateToChinese[1])->find();

                    $getHourByPeriod = explode(' ', $calen)[1];//获取时间
                    //公历日期
                    $Gcalendar = $calendar['solar_year'] . '-' . $calendar['solar_month'] . '-' . $calendar['solar_day'] . ' ' . $getHourByPeriod;
                    $data['G_calendar'] = $Gcalendar;
                    //$data['sun_calendar'] = $this->get_zhentaiyangshi($data);
                    $L_calendar = explode('-', $data['sun_calendar']);
                    $calendars = db('calendar')->where('solar_year', $L_calendar[0])->where('solar_month', $L_calendar[1])->where('solar_day', explode(' ', $L_calendar[2])[0])->find();
                    //获取时辰
                    $getPeriodByHour = $this->getPeriodByHour(explode(' ', $data['sun_calendar'])[1]);
                    $gan = array_search($getPeriodByHour, $this->zhi);//时支数所在位置
                    // //农历日期
                    $Lcalendar = $calendars['lunar_year'] . '年' . $calendars['lunar_month'] . $calendars['lunar_day'] . '日' . $getPeriodByHour . '时';
                    //$data['sun_calendar'] = $this->get_zhentaiyangshi($data);
                } elseif (!empty($data['S_calendar'])) {
                    $calen = $data['S_calendar'];
                    $mbStrSplit = $this->mbStrSplit($calen, 2);
                    $calendar = db('calendar')->where('ganzhi_year', $mbStrSplit[0])->where('ganzhi_month', $mbStrSplit[1])->where('ganzhi_day', $mbStrSplit[2])->order('id desc')->find();
                } else {
                    return ajaxError('参数有误！');
                }
                if (!$calendar) {
                    return ajaxError('当前日期不存在！');
                }
                if (!empty($data['S_calendar'])) {//输入的是生辰八字
                    $Scalendar = $data['S_calendar'];//生辰八字
                    $getPeriodByHour = $this->mbStrSplit($mbStrSplit[3])[1];//截取时柱
                    $getHourByPeriod = $this->getHourByPeriod($getPeriodByHour);//获取时间
                    //农历日期
                    $Lcalendar = $calendar['lunar_year'] . '年' . $calendar['lunar_month'] . $calendar['lunar_day'] . '日' . $getPeriodByHour . '时';
                    //公历日期
                    $Gcalendar = $calendar['solar_year'] . '-' . $calendar['solar_month'] . '-' . $calendar['solar_day'] . ' ' . $getHourByPeriod;
                } else {
                    $shijian = explode(':', explode(' ', $calen)[1])[0];
                    if ($shijian >= 23) {
                        $u_calendar = db('calendar')->where('id', $calendar['id'] + 1)->find();
                        $Scalendar = $u_calendar['ganzhi_year'] . $u_calendar['ganzhi_month'] . $u_calendar['ganzhi_day'];
                        $rigan = ($u_calendar['ri'] * 2 + $gan - 2) % 10;
                    } else {
                        $Scalendar = $calendar['ganzhi_year'] . $calendar['ganzhi_month'] . $calendar['ganzhi_day'];;
                        $rigan = ($calendar['ri'] * 2 + $gan - 2) % 10;
                    }
                    if ($rigan == 0) {
                        $rigan = 10;
                    }
                    //时柱
                    $ganzhi_shi = $this->gan[$rigan] . $getPeriodByHour;
                    $Scalendar = $Scalendar . $ganzhi_shi;
                }
                if ($data['type'] == 0) {
                    $data['name'] = '即时局';
                    $data = [
                        'name' => $data['name'],
                        'type' => $data['type'],//输入类型 0，即时局入口、1，公历、2，农历、3，八柱
                        'sex' => 1,
                        'G_calendar' => $Gcalendar,
                        'L_calendar' => $Lcalendar,
                        'S_calendar' => $Scalendar,
                        'sun_calibration' => $Gcalendar
                    ];
                } else {
                    $data = [
                        'uid' => $this->uid,
                        'name' => $data['name'],
                        'province' => $data['province'],
                        'city' => $data['city'],
                        'area' => $data['area'],
                        'type' => $data['type'],//输入类型 1，公历、2，农历、3，八柱
                        'sex' => $data['sex'],
                        'G_calendar' => $Gcalendar,
                        'L_calendar' => $Lcalendar,
                        'S_calendar' => $Scalendar,
                        'sun_calibration' => $data['sun_calendar']
                    ];
                }
                $data['strokes_num'] = $this->get_strokes_num($data['name']);

                $data['liushijiazi'] = $this->get_liushi($data['strokes_num']);

                $data['zodiac'] = $this->zodiac[array_search($this->mbStrSplit($Scalendar)[1],$this->zhi)];

                $data['text'] = $this->get($data);
                if (input('post.id')) {
                    $res = db('user_calendar')->where('id', input('post.id'))->update($data);
                    if ($res) {
                        return ajaxSuccess('修改成功', 1, ['id' => input('post.id')]);
                    } else {
                        return ajaxSuccess('修改成功', 1, ['id' => input('post.id')]);
                    }
                } else {
                    $res = db('user_calendar')->insertGetId($data);
                    if ($res) {
                        return ajaxSuccess('添加成功', 1, ['id' => $res]);
                    } else {
                        return ajaxError('添加失败');
                    }
                }
            /*}catch (\Exception $exception){
                return ajaxError($exception->getMessage());
            }*/
        }
    }

//根据小时获取时柱
//1、日干 x 2 + 时支数 - 2 = 时干数（时干数超过10要减去10，只取个位数）
//2、时支是固定的，时辰顺余是：子时、丑时、寅时、卯时、辰时、巳时、午时、未时、申时、酉时 、戌时、亥时。
    public function getPeriodByHour($hour)
    {
        $hour = (integer)$hour;
        if ($hour < 0 || $hour > 23) {
            return false;
        }
        $timediff = ($hour - 23 + 24) % 24;//确保时间差为正数
        $offset = $timediff % 2 == 1 ? ceil($timediff / 2) : floor($timediff / 2) + 1;
        return $this->zhi[$offset];
    }

//根据时柱获取小时
    public function getHourByPeriod($period)
    {
        if (!in_array($period, $this->zhi)) {
            return false;
        }
        $start = (23 + (array_search($period, $this->zhi) - 1) * 2) % 24;
//$end   = ($start + 2)%24;
//        return $start . '-' . $end;
        return $start . ':00';
    }

//日期转中文
    public function dateToChinese($date)
    {
        $chineseDate = '';
//$date = '2018-10-29'
        if (false == empty($date)) {
            $chineseArr = array('〇', '一', '二', '三', '四', '五', '六', '七', '八', '九');//把数字化为中文
            $chineseTenArr = array('初', '十', '二十', '三十');//十位数对应中文
            $year = explode('-', $date)[0];
            $day = explode('-', $date)[2];
//转换为数组
            $yearArr = str_split($year);
            foreach ($yearArr as $value) {
                $chineseDate .= $chineseArr[$value];
            }
            $chineseDate .= '/';
            $dayArr = str_split($day);
            if ($dayArr[1] != 0) {
                if ($day > 20 && $day < 30) {
                    $chineseDate .= '廿' . $chineseArr[$dayArr[1]];
                } else {
                    $chineseDate .= $chineseTenArr[$dayArr[0]] . $chineseArr[$dayArr[1]];
                }
            } else {
                if ($day == 10) {
                    $chineseDate .= '初十';
                } else {
                    $chineseDate .= $chineseTenArr[$dayArr[0]];
                }
            }
        }
        return $chineseDate;
    }

//按汉字区分成数组
    public function mbStrSplit($string, $len = 1)
    {
        $start = 0;
        $strlen = mb_strlen($string);
        while ($strlen) {
            $array[] = mb_substr($string, $start, $len, "utf8");
            $string = mb_substr($string, $len, $strlen, "utf8");
            $strlen = mb_strlen($string);
        }
        return $array;
    }

    public function get($Scalendar)
    {
        //命盘
        $mbStrSplit = $this->mbStrSplit($Scalendar['S_calendar'], 2);//提取年柱，月柱，日柱时柱
        $ritiangan = $this->mbStrSplit($mbStrSplit[2], 1)[0];//提取日天干
        $arr = [];
        foreach ($mbStrSplit as $k=>$v) {
            $mb = $this->mbStrSplit($v);//截取时柱
            $state = false;
            if ($k == 2){
                $state = true;
            }

            //天干
            $arr['tian'][] = [array_search($mb[0], $this->color), $mb[0],$this->get_tian_xing($mb[0],$ritiangan,$Scalendar['sex'],$state)];
            //地支
            $arr['di'][] = [array_search($mb[1], $this->color), $mb[1],$this->get_di_xing($mb[1],$ritiangan)];
            //藏干
            $canggan = db('canggan')->where('id', array_search($mb[1], $this->zhi))->value('content');
            $arr['canggan'][] = $canggan;
            //主星
            $zhu_code = $this->code[array_search($ritiangan, $this->gan)][array_search($mb[0], $this->gan) - 1];
            $arr['zhuxing'][] = $this->shishen[$zhu_code];
            $canggan = $this->mbStrSplit($canggan, 2);//提取天干
            $fuxing = '';
            foreach ($canggan as $g) {
                $fu_code = $this->code[array_search($ritiangan, $this->gan)][array_search($this->mbStrSplit($g, 1)[0], $this->gan) - 1];
                $fuxing .= $this->shishen[$fu_code];;
            }
            //副星
            $arr['fuxing'][] = $fuxing;
        }
        //流年
        if (date('Y', strtotime($Scalendar['G_calendar'])) - date("Y", time()) > 0) {
            $arr['liunian'][] = $mbStrSplit[0];
            $arr['liuyue'] = $this->get_liuyue(date('Y', strtotime($Scalendar['G_calendar'])));
        } else {
            $arr['liunian'][] = db('calendar')->where('solar_year', date("Y", time()))->where('solar_month', date("m", time()))->where('solar_day', date("d", time()))->value('ganzhi_year');
            $arr['liuyue'] = $this->get_liuyue(date("Y", time()));
        }
        $calen = $Scalendar['G_calendar'];
        $L_calendar = explode('-', $calen);
        $calendar = db('calendar')->where('solar_year', $L_calendar[0])->where('solar_month', $L_calendar[1])->where('solar_day', explode(' ', $L_calendar[2])[0])->find();
        //节气
        $arr['jieqi'] = $this->get_jieqi($calendar);
        //echo array_search($arr['jieqi']['name1'],$this->term_name);
        //获取大运及流年
        $arr['dayun'] = $this->Get_DaYuan($Scalendar, $calendar);
        $arr['liunian'][] = $arr['dayun'][2];
        $liunian = $arr['liunian'];
        unset($arr['liunian']);
        foreach ($liunian as $k => $v) {
            foreach ($this->mbStrSplit($v) as $j=>$g) {
                if ($j%2 == 0){
                    $arr['liunian'][] = [array_search($g, $this->color), $g,$this->get_tian_xing($g,$ritiangan,$Scalendar['sex'],$state)];
                }else{
                    $arr['liunian'][] = [array_search($g, $this->color), $g,$this->get_di_xing($g,$ritiangan)];
                }
            }
        }
        $liuyue = $arr['liuyue'];
        unset($arr['liuyue']);
        foreach ($liuyue as $k => $v) {
            foreach ($this->mbStrSplit($v) as $g) {
                $arr['liuyue'][] = [array_search($g, $this->color), $g];
            }
        }
        $changsheng = $liunian[0] . $arr['dayun'][2] . $Scalendar['S_calendar'];
        //获取长生宫
        $arr['changsheng'] = $this->get_changsheng($changsheng);
        //获取空亡
        $arr['kongwang'] = $this->get_kongwang($changsheng);
        ////十神简介
        //$arr['brief_xing'] = $this->get_brief_xing($changsheng,$Scalendar['sex']);
        //命盘天干关系
        $tian = array_column($arr['tian'],1);
        $tian[] = $arr['liunian'][0][1];
        $tian[] = $arr['liunian'][2][1];
        $di = array_column($arr['di'],1);
        $di[] = $arr['liunian'][1][1];
        $di[] = $arr['liunian'][3][1];
        $arr['ming_tian_guanxi'] = $this->get_tiangan_guanxi(array_column($arr['tian'],1));
        $arr['ming_di_guanxi'] = $this->get_dizhi_guanxi(array_column($arr['di'],1));
        $arr['xi_tian_guanxi'] = $this->get_tiangan_guanxi($tian);
        $arr['xi_di_guanxi'] = $this->get_dizhi_guanxi($di);
        return json_encode($arr);
    }

//获取大运开始
    public function Get_DaYuan($Scalendar, $calendar)
    {
//出生时间戳
        $cs_time = strtotime(explode(' ', $Scalendar['sun_calibration'])[1]) - strtotime('00:00');
//1、顺排；2、逆排
        if ($Scalendar['sex'] == 1) {
            if (array_search($this->mbStrSplit($calendar['ganzhi_year'])[0], $this->gan) % 2 != 0) {//判断年干是否为阳干
                $is_along = 1;
            } else {
                $is_along = 2;
            }
        } else {
            $is_along = 2;

        }
        $state = 0;
        $patterns = "/\d+/"; //第一种
        preg_match_all($patterns, $calendar['jie_qi_day'], $arr);
//节气所在位置
        $num = db('solar_terms')->where('name', $calendar['jie_qi'])->where('year', $calendar['solar_year'])->value('id');
        if ($is_along == 1) {
            if ($num % 2 == 0) {//判断是节还是气
                $solar_terms = db('solar_terms')->where('id', $num)->value('time');
            } else {
                $solar_terms = db('solar_terms')->where('id', $num + 1)->value('time');
            }
//入节时间戳
            $jieqitime = strtotime(explode(' ', $solar_terms)[1]) - strtotime('00:00');
//当天是节气的情况下
            if ($num % 2 == 0 && $arr[0][0] == 1 && $jieqitime >= $cs_time) {
                $day = 0;
                $cs_time = ceil(($jieqitime - $cs_time) / 3600);
            } else {
                if ($num % 2 == 0) {//判断是节还是气
                    $solar_terms = db('solar_terms')->where('id', $num + 2)->value('time');
                } else {
                    $solar_terms = db('solar_terms')->where('id', $num + 3)->value('time');
                }
                $jieqitime = strtotime(explode(' ', $solar_terms)[1]) - strtotime('00:00');
//顺排
                $day = db('calendar')
                    ->where('id', '>', $calendar['id'])
                    ->where('id', '<', ($calendar['id'] + 35))
                    ->where('yue', $calendar['yue'])
                    ->count();
                $cs_time = ceil(($jieqitime + 86400 - $cs_time) / 3600);
            }
        } else {
            $day = db('calendar')
                    ->where('id', '<', $calendar['id'])
                    ->where('id', '>', ($calendar['id'] - 35))
                    ->where('yue', $calendar['yue'])
                    ->count() - 1;
            if ($num % 2 == 0) {//判断是节还是气
                $solar_terms = db('solar_terms')->where('id', $num)->value('time');
            } else {
                $solar_terms = db('solar_terms')->where('id', $num - 1)->value('time');
            }
            $jieqitime = strtotime(explode(' ', $solar_terms)[1]) - strtotime('00:00');
//当出生日期是节气当天且出生时间小于如节气时间
            if ($num % 2 == 0 && $day == -1 && $jieqitime <= $cs_time) {
                $day = 0;
                $cs_time = ceil(($cs_time - $jieqitime) / 3600);
            } elseif ($num % 2 == 0 && $day == -1 && $jieqitime >= $cs_time) {
                if ($num % 2 == 0) {//判断是节还是气
                    $solar_terms = db('solar_terms')->where('id', $num - 2)->value('time');
                } else {
                    $solar_terms = db('solar_terms')->where('id', $num - 3)->value('time');
                }
                $jieqitime = strtotime(explode(' ', $solar_terms)[1]) - strtotime('00:00');
                $day = db('calendar')
                        ->where('id', '<', $calendar['id'])
                        ->where('id', '>', ($calendar['id'] - 35))
                        ->where('yue', $calendar['yue'] - 1)
                        ->count() - 1;
                $state = 1;
                $cs_time = ceil((86400 - $jieqitime + $cs_time) / 3600);
            } else {
                $cs_time = ceil((86400 - $jieqitime + $cs_time) / 3600);
            }
        }
        $time = ($cs_time + 24 * $day) * 5;
        $date = date_create($Scalendar['G_calendar']);
        date_add($date, date_interval_create_from_date_string("$time days"));
        $xiaoyun = date_format($date, "Y-m-d");
        $date1 = date('Y', strtotime($Scalendar['G_calendar']));
        $date2 = date('Y', strtotime($xiaoyun));
        $data = $date2 - $date1;
        if ($state) {//出生年月少于入节气时间
            $calendar['yue'] = $calendar['yue'] - 1;
        }
        $y = date('Y', strtotime($xiaoyun));
        $s = $date1;
        if ($is_along == 2) {
            if ($data) {
                if ($data == 1) {
                    $dayue[$calendar['solar_year']][] = 1;
                } else {
                    $dayue[$calendar['solar_year']][] = '1-' . ($data - 1);
                }
                $dayue[$calendar['solar_year']][] = '小运';
            }
            for ($i = 0; $i < 10; $i++) {
                if ($y + $i * 10 < 2100) {
                    if ($calendar['yue'] - $i - 1 <= 0) {
                        $calendar['yue'] = 61 + $i;
                    }
                    //dump($calendar['yue'] - $i - 1);
                    $dayue[$y + $i * 10][] = $data + 1 + $i * 10;
                    $dayue[$y + $i * 10][] = $this->GZ[$calendar['yue'] - $i - 1];
                }
                if (date('Y', time()) >= $y + $i * 10 && date('Y', time()) <= $y + $i * 10 + 10 || date('Y', time()) >= $calendar['solar_year'] && date('Y', time()) <= $calendar['solar_year']+$data) {
                    if(date('Y', time()) >= $calendar['solar_year'] && date('Y', time()) <= $calendar['solar_year']+$data){
                        $s = $calendar['solar_year'];
                        $num = $data;
                        $dayun1 = db('calendar')->where('solar_year', $s)->where('solar_month', 10)->value('ganzhi_year');
                    }else{
                        $num = 10;
                        $s = $y + $i * 10;
                        $dayun1 = $dayue[$s][1];
                    }
                    for ($g = 0; $g < $num; $g++) {
                        $liunian1[$s + $g] = db('calendar')->where('solar_year', $s + $g)->where('solar_month', 10)->value('ganzhi_year');

                    }
                    ksort($liunian1);
                } else {
                    if (date('Y', time()) < $y && !$data) {
                        $dayun2 = $this->GZ[$calendar['yue']];
                        for ($g = 0; $g <= 9; $g++) {
                            $liunian2[$y + $g] = db('calendar')->where('solar_year', $y + $g)->where('solar_month', 10)->value('ganzhi_year');
                        }
                    } else {
                        for ($g = 1; $g <= $data; $g++) {
                            if ($y - $g == date('Y', time())) {
                                $a = array_search($this->mbStrSplit($Scalendar['S_calendar'], 2)[3], $this->GZ);
                                if ($a == 1) {
                                    $a = 61;
                                }
                                $dayun2 = $this->GZ[$a - 1];
                            }
                            $liunian2[$y - $g] = db('calendar')->where('solar_year', $y - $g)->where('solar_month', 10)->value('ganzhi_year');
                        }
                        if (empty($dayun2)) {
                            $dayun2 = $this->GZ[$calendar['yue']];
                        }
                    }
                    if (!empty($liunian2)) {
                        ksort($liunian2);
                    }
                }
            }
        } else {
            if ($data) {
                if ($data == 1) {
                    $dayue[$calendar['solar_year']][] = 1;
                } else {
                    $dayue[$calendar['solar_year']][] = '1-' . ($data - 1);
                }
                $dayue[$calendar['solar_year']][] = '小运';
            }
            for ($i = 0; $i < 10; $i++) {
                if ($y + $i * 10 < 2100) {
                    if ($calendar['yue'] + $i > 60) {
                        $calendar['yue'] = $calendar['yue'] - 60;
                    }
                    $dayue[$y + $i * 10][] = $data + 1 + $i * 10;
                    $dayue[$y + $i * 10][] = $this->GZ[$calendar['yue'] + $i + 1];
                }
                if (date('Y', time()) >= $y + $i * 10 && date('Y', time()) <= $y + $i * 10 + 10 || date('Y', time()) >= $calendar['solar_year'] && date('Y', time()) <= $calendar['solar_year']+$data) {
                    if(date('Y', time()) >= $calendar['solar_year'] && date('Y', time()) <= $calendar['solar_year']+$data){
                        $s = $calendar['solar_year'];
                        $num = $data;
                        $dayun1 = db('calendar')->where('solar_year', $s)->where('solar_month', 10)->value('ganzhi_year');
                    }else{
                        $num = 10;
                        $s = $y + $i * 10;
                        $dayun1 = $dayue[$s][1];
                    }
                    for ($g = 0; $g < $num; $g++) {
                        $liunian1[$s + $g] = db('calendar')->where('solar_year', $s + $g)->where('solar_month', 10)->value('ganzhi_year');

                    }
                    ksort($liunian1);
                } else {
                    if (date('Y', time()) <= $y + $i * 10) {
                        $b = array_search($this->mbStrSplit($Scalendar['S_calendar'], 2)[3], $this->GZ) + $i;
                        if ($b > 60){
                            $b = $b-60;
                        }
                        $dayun2 = $this->GZ[$b];
                        for ($g = 0; $g <= 9; $g++) {
                            $liunian2[$y + $g] = db('calendar')->where('solar_year', $y + $g)->where('solar_month', 10)->value('ganzhi_year');
                        }
                    } else {
                        for ($g = 1; $g <= $data; $g++) {
                            if ($y - $g == date('Y', time())) {
                                $a = array_search($this->mbStrSplit($Scalendar['S_calendar'], 2)[3], $this->GZ);
                                if ($a == 60) {
                                    $a = 0;
                                }
                                $dayun2 = $this->GZ[$a + 1];
                            }
                            $liunian2[$y - $g] = db('calendar')->where('solar_year', $y - $g)->where('solar_month', 10)->value('ganzhi_year');
                        }
                        if (empty($dayun2)) {
                            $dayun2 = $this->GZ[$calendar['yue'] + $i];
                        }
                    }
                    if (!empty($liunian2)) {
                        ksort($liunian2);
                    }
                }
            }
        }

        if (isset($liunian1)) {
            $liunian = $liunian1;
        } else {
            $liunian = $liunian2;
        }
        if (isset($dayun1)) {
            $dayun = $dayun1;
        } else {
            $dayun = $dayun2;
        }
        return [$dayue, $liunian, $dayun, $xiaoyun, $s, date('Y', time())];
//return [$xiaoyun,$date2 - $date1,$state];
    }

//获取当年流月
    public function get_liuyue($nian)
    {
//获取本年第一个月天干地支
        $code = db('calendar')->where('solar_year', $nian)->where('solar_month', '02')->where('solar_day', '10')->value('yue');
        for ($i = 0; $i < 12; $i++) {
            if ($code + $i > 60) {
                $code = $code - 60;
            }
            $data[] = $this->GZ[$code + $i];
        }
        return $data;
    }

//传流年大运及生辰八字获取长生宫
    public function get_changsheng($calendar)
    {
        $calendar = $this->mbStrSplit($calendar, 2);
//对应日天干的数据
        $changsheng = $this->changsheng[array_search($this->mbStrSplit($calendar[4])[0], $this->gan)];
        foreach ($calendar as $v) {
            $data[] = $this->CS_code[$changsheng[array_search($this->mbStrSplit($v)[1], $this->zhi)]];
        }
        return $data;
    }
//获取空亡
    public function get_kongwang($kongwang)
    {
        $calendar = $this->mbStrSplit($kongwang, 2);
        $data = [];
        foreach ($calendar as $v) {
            $num = array_search($v, $this->GZ);
            if ($num > 0 && $num <= 10) {
                $data[] = '戌亥';
            } elseif ($num > 10 && $num <= 20) {
                $data[] = '申酉';
            } elseif ($num > 20 && $num <= 30) {
                $data[] = '午未';
            } elseif ($num > 30 && $num <= 40) {
                $data[] = '辰巳';
            } elseif ($num > 40 && $num <= 50) {
                $data[] = '寅卯';
            } elseif ($num > 50 && $num <= 60) {
                $data[] = '子丑';
            }
        }
        return $data;

    }

//获取出生真太阳时
    public function get_zhentaiyangshi($data)
    {
        $address = $data['province'] . $data['city'] . $data['area'];
        $Address_info = json_decode(file_get_contents('https://apis.map.qq.com/ws/geocoder/v1/?address=' . $address . '&key=CKWBZ-WO6H4-FLRU3-X6M7G-LZ3XZ-DAF4O'));
        if ($Address_info->message == 'query ok') {
            $time = strtotime($data['G_calendar']) + ceil(4 * ($Address_info->result->location->lng - 120) * 60);
            $true_sun_difference = db('true_sun_difference')->where('date', date('m-d', strtotime($data['G_calendar'])))->find();
            $explode = explode(',', $true_sun_difference['time']);
            if ($true_sun_difference['operation'] == '+') {
                return date('Y-m-d H:i:s', $time + ceil((int)$explode[0] * 60 + (int)$explode[1]));
            } else {
                return date('Y-m-d H:i:s', $time - ceil((int)$explode[0] * 60 + (int)$explode[1]));
            }
        } else {
            return ajaxError('地址获取失败');
        }
    }

//获取入节点时间
    public function get_jieqi($calendar)
    {
        $num = db('solar_terms')->where('name', $calendar['jie_qi'])->where('year', $calendar['solar_year'])->value('id');
        if ($num % 2 == 0) {//判断是节还是气
            $solar_terms_one = db('solar_terms')->where('id', $num)->find();
        } else {
            $solar_terms_one = db('solar_terms')->where('id', $num - 1)->find();
        }
        $solar_terms_two = db('solar_terms')->where('id', $solar_terms_one['id'] + 1)->find();

        $data = [];
        $data[] = $solar_terms_one['name'] . $solar_terms_one['day'] . '日' . explode(':', $solar_terms_one['hour'])[0] . '时' . (int)explode(':', $solar_terms_one['hour'])[1] . '分';
        $data[] = $solar_terms_two['name'] . $solar_terms_two['day'] . '日' . explode(':', $solar_terms_two['hour'])[0] . '时' . (int)explode(':', $solar_terms_two['hour'])[1] . '分';
        return $data;
    }

//修改
    public function edit()
    {
        $id = input('id/d');
        $detail = db('user_calendar')->where('id', $id)->field('text,is_del', true)->find();

        if ($detail['type'] == 1) {
            $detail['calendar'] = $detail['G_calendar'];
        } elseif ($detail['type'] == 2) {
            $detail['calendar'] = $this->ChineseToDate($detail['L_calendar'], $detail['G_calendar']);
        } else {
            $detail['calendar'] = $detail['S_calendar'];
        }
        unset($detail['G_calendar']);
        unset($detail['L_calendar']);
        unset($detail['S_calendar']);
        return ajaxSuccess('', 1, ['data' => $detail]);

    }

//日期转中文
    public function ChineseToDate($L_calendar, $G_calendar)
    {
        $year = explode('年', $L_calendar)[0];
        $month = explode('月', explode('年', $L_calendar)[1])[0] . '月';
        $day = explode('日', explode('月', explode('年', $L_calendar)[1])[1])[0];
        $calendar = db('calendar')->where('lunar_year', $year)->where('lunar_month', $month)->where('lunar_day', $day)->find();
        return $calendar['solar_year'] . '-' . $calendar['solar_month'] . '-' . $calendar['solar_day'] . ' ' . explode(' ', $G_calendar)[1];
    }

//
    public function true_sun_difference()
    {
        $data = input('post.');
        if (!empty($data['G_calendar'])) {
            return ajaxSuccess('', 1, $this->get_zhentaiyangshi($data));
        } else if ($data['L_calendar']) {
            $calen = $data['L_calendar'];
            $L_calendar = explode('-', $calen);
//将年份和日期转换成中文
            $dateToChinese = explode('/', $this->dateToChinese($calen));
            $calendar = db('calendar')->where('lunar_year', $dateToChinese[0])->where('lunar_month', $this->months[$L_calendar[1] * 1])->where('lunar_day', $dateToChinese[1])->find();
            if (!$calendar) {
                return ajaxError('当前日期不存在');
            }
            $getHourByPeriod = explode(' ', $calen)[1];//获取时间
//公历日期
            $data['G_calendar'] = $calendar['solar_year'] . '-' . $calendar['solar_month'] . '-' . $calendar['solar_day'] . ' ' . $getHourByPeriod;
            return ajaxSuccess('', 1, $this->get_zhentaiyangshi($data));
        }
        /*else if($data['type'] == 3){
        $calen = $data['S_calendar'];
        $sun_calibration = $data['sun_calibration'];
        $mbStrSplit = $this->mbStrSplit($calen,2);
        $calendar = db('calendar')->where('ganzhi_year',$mbStrSplit[0])->where('ganzhi_month',$mbStrSplit[1])->where('ganzhi_day',$mbStrSplit[2])->where('solar_year',explode('-',$sun_calibration)[0])->order('id desc')->find();
        $getPeriodByHour = $this->mbStrSplit($mbStrSplit[3])[1];//截取时柱
        $getHourByPeriod = $this->getHourByPeriod($getPeriodByHour);//获取时间
        //平太阳时
        $SUNcalendar = $calendar['solar_year'].'-'.$calendar['solar_month'].'-'.$calendar['solar_day'].' '.$getHourByPeriod;
        return ajaxSuccess('',1,$SUNcalendar);
        }*/
    }

    public function bazi()
    {
        $calen = input('bazi');
        $mbStrSplit = $this->mbStrSplit($calen, 2);
        $calendar = db('calendar')->where('ganzhi_year', $mbStrSplit[0])->where('ganzhi_month', $mbStrSplit[1])->where('ganzhi_day', $mbStrSplit[2])->select();

        $getPeriodByHour = $this->mbStrSplit($mbStrSplit[3], 1)[1];
        $gan = array_search($getPeriodByHour, $this->zhi);//时支数所在位置
        foreach ($calendar as $key => $value) {
            $rigan = ($value['ri'] * 2 + $gan - 2) % 10;
            if ($rigan == 0) {
                $rigan = 10;
            }
            $ganzhi_shi = $this->gan[$rigan] . $getPeriodByHour;
            if ($ganzhi_shi != $mbStrSplit[3]) {
                unset($calendar[$key]);
            } else {
                $calendar[$key] = $value['solar_year'] . '-' . $value['solar_month'] . '-' . $value['solar_day'] . ' ' . $this->getHourByPeriod($getPeriodByHour);
            }
        }
        if (!$calendar) {
            return ajaxError('在当前范围内找不到结果，请重新输入');
        }
        return ajaxSuccess('', 1, $calendar);
    }

//查看用户排盘详情
    public function calendar_details()
    {
        $id = input('post.id');
        if (!$id) {
            return ajaxError('参数有误！');
        }
        $user_calendar = db('user_calendar')->where('id', $id)->where('is_del', 0)->find();
        $user_calendar['text'] = json_decode($user_calendar['text'], true);
        return ajaxSuccess('', 1, $user_calendar);
    }

//变动大运
    public function dayue_see()
    {
        try{
            $id = input('id');
            if (!$id) {
                return ajaxError('参数有误！');
            }
            $user_calendar = db('user_calendar')->where('id', $id)->where('is_del', 0)->find();
            $user_calendar['text'] = json_decode($user_calendar['text'], true);
            $data = [];
            $S_calendar = $this->mbStrSplit($user_calendar['S_calendar'], 2);
//流年
            $liunian = [];

//流月
            $data['liuyue'] = $this->get_liuyue(input('year'));
            if (input('dayun') == '小运') {
                $num = array_search($S_calendar[3], $this->GZ) + 1;
                if ($num > 60) {
                    $num = $num - 60;
                }
                $data['dayun'] = $this->GZ[$num];
                $data['liu'] = $S_calendar[0];
                for ($i = input('year'); $i < (int)$user_calendar['text']['dayun'][3]; $i++) {
                    $liunian[$i] = db('calendar')->where('solar_year', $i)->where('solar_month', 10)->value('ganzhi_year');
                }
            } else {
                $data['dayun'] = input('dayun');
                for ($i = 0; $i < 10; $i++) {
                    $liunian[input('year') + $i] = db('calendar')->where('solar_year', input('year') + $i)->where('solar_month', 10)->value('ganzhi_year');
                }
                $data['liu'] = $liunian[input('year')];
            }
            $changsheng = $data['liu'] . $data['dayun'] . $user_calendar['S_calendar'];
//获取长生宫
            $data['changsheng'] = $this->get_changsheng($changsheng);
//获取空亡
            $data['kongwang'] = $this->get_kongwang($changsheng);
            $data['liunian'] = $liunian;

            $data['s'] = input('year');
            $data['liu'] = $this->get_color($data['liu']);
            $ri = $this->mbStrSplit($S_calendar[2])[0];
            foreach ($data['liu'] as $k=>&$v){
                if ($k%2 == 0){
                    $v[2] = $this->get_tian_xing($v[1],$ri,1);
                }else{
                    $v[2] = $this->get_di_xing($v[1],$ri);
                }
            }
            $data['dayun'] = $this->get_color($data['dayun']);
            foreach ($data['dayun'] as $k=>&$v){
                if ($k%2 == 0){
                    $v[2] = $this->get_tian_xing($v[1],$ri,1);
                }else{
                    $v[2] = $this->get_di_xing($v[1],$ri);
                }
            }
            $data['liuyue'] = $this->get_color($data['liuyue']);
            $tian = array_column($user_calendar['text']['tian'],1);
            $tian[] = $data['dayun'][0][1];
            $tian[] = $data['liu'][0][1];
            $di = array_column($user_calendar['text']['di'],1);
            $di[] = $data['dayun'][1][1];
            $di[] = $data['liu'][1][1];
            $data['xi_tian_guanxi'] = $this->get_tiangan_guanxi($tian);
            $data['xi_di_guanxi'] = $this->get_dizhi_guanxi($di);
            return ajaxSuccess('', 1, $data);
        }catch (\Exception $e){
            return ajaxError($e->getMessage());
        }
    }

//变动流年
    public function liunian_see()
    {
        try{
            $id = input('id');
            if (!$id) {
                return ajaxError('参数有误！');
            }
            $user_calendar = db('user_calendar')->where('id', $id)->where('is_del', 0)->find();
            $user_calendar['text'] = json_decode($user_calendar['text'], true);
            $S_calendar = $this->mbStrSplit($user_calendar['S_calendar'], 2);
            $data = [];
            $data['liunian'] = input('liunian');
//流月
            $data['liuyue'] = $this->get_liuyue(input('year'));
            if (input('xiaoyun') == '小运') {
                $num = array_search($S_calendar[3], $this->GZ) + input('year') - (int)$user_calendar['sun_calibration'] + 1;
                if ($num > 60) {
                    $num = $num - 60;
                }
                $data['xiaoyun'] = $this->GZ[$num];
            } else {
                $data['xiaoyun'] = input('xiaoyun');
            }
            $changsheng = $data['liunian'] . $data['xiaoyun'] . $user_calendar['S_calendar'];
//获取长生宫
            $data['changsheng'] = $this->get_changsheng($changsheng);
//获取空亡
            $data['kongwang'] = $this->get_kongwang($changsheng);
            $data['liunian'] = $this->get_color($data['liunian']);
            $data['xiaoyun'] = $this->get_color($data['xiaoyun']);
            $ri = $this->mbStrSplit($S_calendar[2])[0];
            foreach ($data['liunian'] as $k=>&$v){
                if ($k%2 == 0){
                    $v[2] = $this->get_tian_xing($v[1],$ri,1);
                }else{
                    $v[2] = $this->get_di_xing($v[1],$ri);
                }
            }
            foreach ($data['xiaoyun'] as $k=>&$v){
                if ($k%2 == 0){
                    $v[2] = $this->get_tian_xing($v[1],$ri,1);
                }else{
                    $v[2] = $this->get_di_xing($v[1],$ri);
                }
            }
            $data['liuyue'] = $this->get_color($data['liuyue']);
//$data['brief_xing'] = $this->get_brief_xing($data['liuyue']);
            $tian = array_column($user_calendar['text']['tian'],1);
            $tian[] = $data['xiaoyun'][0][1];
            $tian[] = $data['liunian'][0][1];
            $di = array_column($user_calendar['text']['di'],1);
            $di[] = $data['xiaoyun'][1][1];
            $di[] = $data['liunian'][1][1];
            $data['xi_tian_guanxi'] = $this->get_tiangan_guanxi($tian);
            $data['xi_di_guanxi'] = $this->get_dizhi_guanxi($di);
            return ajaxSuccess('', 1, $data);
        }catch (\Exception $e){
            return ajaxError($e->getMessage());
        }
    }

//获取颜色
    public function get_color($data)
    {
        $arr = [];
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                foreach ($this->mbStrSplit($v) as $g) {
                    $arr[] = [array_search($g, $this->color), $g];
                }
            }
        } else {
            foreach ($this->mbStrSplit($data) as $v) {
                $arr[] = [array_search($v, $this->color), $v];
            }
        }
        return $arr;
    }

//删除用户
    public function del()
    {
        $id = input('post.id');
        if (!$id) {
            return ajaxError('参数错误');
        }
        $res = db('user_calendar')->where('id', $id)->update(['is_del' => 1]);
        if ($res) {
            return ajaxSuccess('', 1, '删除成功');
        } else {
            return ajaxError('删除失败');
        }
    }

//获取笔画数
    public function get_strokes_num($name)
    {
        $name = $this->mbStrSplit($name);
        $num = 0;
        foreach ($name as $v) {
            $num += db('strokes')->where('chinese', $v)->value('sum');
        }
        return $num;
    }
//通过数字获取六十甲子
    public function get_liushi($num)
    {
//获取六十甲子
        $a = $num%60;
        if ($a == 0){
            $a = 60;
        }
        $calendar = $this->GZ[$a];
        return $calendar;
    }

//传流年大运及生辰八字获取长生宫
    public function get_tian_xing($calendar,$ri,$sex,$state = false)
    {
        $zhu_code = $this->code[array_search($ri, $this->gan)][array_search($calendar, $this->gan) - 1];
        if ($state){
            $brief_zhuxing = '元'.($sex == 1 ? '男' :'女');
        }else{
            $brief_zhuxing = $this->brief_shishen[$zhu_code];
        }
        return $brief_zhuxing;
        /*$calendar = $this->mbStrSplit($calendar);
        $ritiangan = $calendar[8];
        $brief_zhuxing = [];
        $brief_fuxing = [];

        foreach ($calendar as $k=>$v)
        {
        if ($k%2 == 0){
        $zhu_code = $this->code[array_search($ritiangan, $this->gan)][array_search($v, $this->gan) - 1];
        if ($k == 8){
        $brief_zhuxing[] = '元'.($sex == 1 ? '男' :'女');
        }else{
        $brief_zhuxing[] = $this->brief_shishen[$zhu_code];
        }
        }else{
        $canggan = db('canggan')->where('id',array_search($v,$this->zhi))->value('content');
        $fuxing = '';
        foreach ($this->mbStrSplit($canggan,2) as $g){
        $fu_code = $this->code[array_search($ritiangan,$this->gan)][array_search($this->mbStrSplit($g,1)[0],$this->gan)-1];
        $fuxing .= $this->brief_shishen[$fu_code];;
        }
        $brief_fuxing[] = $fuxing;
        }
        }
        return [$brief_zhuxing,$brief_fuxing];*/
    }
    public function get_di_xing($calendar,$ri)
    {
        $canggan = db('canggan')->where('id',array_search($calendar,$this->zhi))->value('content');
        $fuxing = '';
        foreach ($this->mbStrSplit($canggan,2) as $g){
            $fu_code = $this->code[array_search($ri,$this->gan)][array_search($this->mbStrSplit($g,1)[0],$this->gan)-1];
            $fuxing .= $this->brief_shishen[$fu_code];;
        }
        return $fuxing;
    }
    //获取天干关系
    public function get_tiangan_guanxi($tiangan)
    {
        foreach ($tiangan as $v){
            $a[] = array_search($v,$this->gan);
        }
        sort($a);
        $result = $this->getCombinationToString($a,2);
        $result = array_unique($result);//去重
        $t_guanxi = db('t_guanxi')->whereIn('code',$result)->field('name,content')->order('grade')->select();
        $arr = [];
        foreach ($t_guanxi as $v){
            $arr[] = $v['name'].$v['content'];
        }
        $arr = implode(',',$arr);
        if ($arr){
            return $arr;
        }else{
            return '无和局关系';
        }
    }
    //获取地支关系
    public function get_dizhi_guanxi($dizhi)
    {
        foreach ($dizhi as $v){
            $a[] = array_search($v,$this->zhi);
        }
        sort($a);
        //获取排列组合
        $result1 = $this->getCombinationToString($a,2);
        $result2 = $this->getCombinationToString($a,3);
        $result = array_merge($result1,$result2);//合并
        $result = array_unique($result);//去重
        if (in_array('1-5-9',$result)){
            $result = array_diff($result, ["1-5", "1-9"]);
        }
        if (in_array('4-8-12',$result)){
            $result = array_diff($result, ["4-8", "4-12"]);
        }
        if (in_array('3-7-11',$result)){
            $result = array_diff($result, ["3-7", "7-11"]);
        }
        if (in_array('2-6-10',$result)){
            $result = array_diff($result, ["2-6", "6-10"]);
        }
        if (in_array('1-2-12',$result)){
            $result = array_diff($result, ["1-12"]);
        }
        if (in_array('3-4-5',$result)){
            $result = array_diff($result, ["3-4"]);
        }
        if (in_array('6-7-8',$result)){
            $result = array_diff($result, ["6-7"]);
        }
        if (in_array('9-10-11',$result)){
            $result = array_diff($result, ["9-10"]);
        }
        $d_guanxi = db('d_guanxi')->whereIn('code',$result)->field('name,content')->order('grade')->select();
        $arr = [];
        foreach ($d_guanxi as $v){
            $arr[] = $v['name'].$v['content'];
        }
        $arr = implode(',',$arr);
        if ($arr){
            return $arr;
        }else{
            return '无和局关系';
        }
    }
    //从$arr数组中，获取$m个数字组成数组
    public function getCombinationToString($arr,$m){
        $result = array();
        if($m == 1){
            return $arr;
        }
        if($m == count($arr)){
            $result[] = implode('-',$arr);
            return $result;
        }
        $temp_firstelement = $arr[0];
        unset($arr[0]);
        $arr = array_values($arr);
        $temp_first1 = $this->getCombinationToString($arr,$m - 1);
        foreach($temp_first1 as $s){
            $s = $temp_firstelement.'-'.$s;
            $result[] = $s;
        }
        unset($temp_first1);
        $temp_first2 = $this->getCombinationToString($arr,$m);
        foreach($temp_first2 as $s){
            $result[] = $s;
        }
        unset($temp_first2);
        return $result;
    }
}