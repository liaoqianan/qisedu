<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:56:"D:\pgt\public/../application/admin\view\Webcast\add.html";i:1608109537;s:47:"D:\pgt\application\admin\view\Public\base2.html";i:1599547052;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no,email=no,address=no">
    <title><?php echo config('APP_NAME'); ?>管理后台</title>
    <link rel="stylesheet" href="/static/admin/plugins/layui2/css/layui.css">
    <script type="text/javascript" src="/static/admin/plugins/laydate/laydate.js"></script>
    <script src="/static/admin/js/jquery.min.js"></script>
    <script type="text/javascript" src="/static/admin/plugins/layui2/layui.js"></script>
    <script type="text/javascript" src="/static/admin/plugins/dataTable/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="/static/admin/plugins/dataTable/dataTable.css">
    <link href="/static/admin/plugins/bootstrap/css/edit_bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/static/admin/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <script src="/static/admin/plugins/daterangepicker/moment.min.js" type="text/javascript"></script>
    <script src="/static/admin/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
    <block name="myCss"></block>
</head>
<body>
<div style="margin: 15px;">
    <block name="main"></block>
</div>
<block name="myScript"></block>
</body>
</html>
<style>
    .layui-form-label {
        width: 120px;
    }

    .layui-form-item .layui-input-inline {
        width: 300px;
    }

    /* 下拉多选样式 需要引用*/
    select[multiple] + .layui-form-select > .layui-select-title > input.layui-input {
        border-bottom: 0
    }

    select[multiple] + .layui-form-select dd {
        padding: 0;
    }

    select[multiple] + .layui-form-select .layui-form-checkbox[lay-skin=primary] {
        margin: 0 !important;
        display: block;
        line-height: 36px !important;
        position: relative;
        padding-left: 26px;
    }

    select[multiple] + .layui-form-select .layui-form-checkbox[lay-skin=primary] span {
        line-height: 36px !important;
        padding-left: 10px;
        float: none;
    }

    select[multiple] + .layui-form-select .layui-form-checkbox[lay-skin=primary] i {
        position: absolute;
        left: 10px;
        top: 0;
        margin-top: 9px;
    }

    .multiSelect {
        line-height: normal;
        height: auto;
        padding: 4px 10px;
        overflow: hidden;
        min-height: 38px;
        margin-top: -38px;
        left: 0;
        z-index: 99;
        position: relative;
        background: none;
    }

    .multiSelect a {
        padding: 2px 5px;
        background: #908e8e;
        border-radius: 2px;
        color: #fff;
        display: block;
        line-height: 20px;
        height: 20px;
        margin: 2px 5px 2px 0;
        float: left;
    }

    .multiSelect a span {
        float: left;
    }

    .multiSelect a i {
        float: left;
        display: block;
        margin: 2px 0 0 2px;
        border-radius: 2px;
        width: 8px;
        height: 8px;
        padding: 4px;
        position: relative;
        -webkit-transition: all .3s;
        transition: all .3s
    }

    .multiSelect a i:before, .multiSelect a i:after {
        position: absolute;
        left: 8px;
        top: 2px;
        content: '';
        height: 12px;
        width: 1px;
        background-color: #fff
    }

    .multiSelect a i:before {
        -webkit-transform: rotate(45deg);
        transform: rotate(45deg)
    }

    .multiSelect a i:after {
        -webkit-transform: rotate(-45deg);
        transform: rotate(-45deg)
    }

    .multiSelect a i:hover {
        background-color: #545556;
    }

    .multiOption {
        display: inline-block;
        padding: 0 5px;
        cursor: pointer;
        color: #999;
    }

    .multiOption:hover {
        color: #5FB878
    }

    @font-face {
        font-family: "iconfont";
        src: url('data:application/x-font-woff;charset=utf-8;base64,d09GRgABAAAAAAaoAAsAAAAACfwAAQAAAAAAAAAAAAAAAAAAAAAAAAAAAABHU1VCAAABCAAAADMAAABCsP6z7U9TLzIAAAE8AAAARAAAAFZW7kokY21hcAAAAYAAAABwAAABsgdU06BnbHlmAAAB8AAAAqEAAAOUTgbbS2hlYWQAAASUAAAALwAAADYR+R9jaGhlYQAABMQAAAAcAAAAJAfeA4ZobXR4AAAE4AAAABMAAAAUE+kAAGxvY2EAAAT0AAAADAAAAAwB/gLGbWF4cAAABQAAAAAfAAAAIAEVAGhuYW1lAAAFIAAAAUUAAAJtPlT+fXBvc3QAAAZoAAAAPQAAAFBD0CCqeJxjYGRgYOBikGPQYWB0cfMJYeBgYGGAAJAMY05meiJQDMoDyrGAaQ4gZoOIAgCKIwNPAHicY2Bk/s04gYGVgYOpk+kMAwNDP4RmfM1gxMjBwMDEwMrMgBUEpLmmMDgwVLwwZ27438AQw9zA0AAUZgTJAQAokgyoeJzFkTEOgCAQBOdAjTH+wtbezvggKyteTPyFLpyFvsC9DNnbHIEA0AJRzKIBOzCKdqVW88hQ84ZN/UBPUKU85fVcrkvZ27tMc17FR+0NMh2/yf47+quxrtvT6cVJD7pinpzyI3l1ysy5OIQbzBsVxHicZVM9aBRBFJ43c7szyeV2s/97m9zP3ppb5ZID72+9iJfDnyIiGImCMZWFXaKdaSyuESJYCFZpRZBUCpaJcCCKaexsRVHQytrC2/Pt5ZSIy+z3vvnemwfvY4ZIhAw/s33mEoMcJyfJebJCCMgVKCk0B37YqNIKWL5kOabCwiD0eVCqsjPglGTTrrUaZUfmsgoK5KHu11phlYbQbHToaajZOYDsjLeqz83q7BFMumH+fnyRPgGrEMyqnYV4eX7JrBUNsTWl61ldfyhkSRKUplQFNh17QpqYlOOnkupZ+4UTtABT2dC7tJYpzug3txu3c3POBECvB8ZMUXm2pHkarnuebehZPp0RrpcJjpmw9TXtGlO58heCXwpnfcVes7PExknPkVWctFxSIUxANgs4Q9RaglYjjIKwCqGvANfy4NQtBL8DkYaipAVVaGqNVuTnoQBYg8NzHzNaJ7HAdpjFXfF2DSEjxF2ui7T8ifP2CsBiZTCsLCbxCv4UDvlgp+kFgQcHXgAQP64s0gdQdOOKWwSM8CGJz4V4c11gQwc70hTlH4XLv12dbwO052OotGHMYYj8VrwDJQ/eeSXA2Ib24Me42XvX993ECxm96LM+6xKdBCRCNy6TdfSDoxmJFXYBaokV5RL7K/0nOHZ9rBl+chcCP7kVMML6SGHozx8Od3ZvCEvlm5KQ0nxPTJtiLHD7ny1jsnxYsAF7imkq8QVEOBgF5Yh0yNkpPIenN2QAsSdMNX6xu85VC/tiE3Mat6P8JqWM73NLhZ9mzjBy5uAlAlJYBiMRDPQleQ+9FEFfJJImGnHQHWIEmm/5UB8h8uaIIzrc4SEPozByel3oDvFcN+4D+dU/uou/L2xv/1mUQBdTCIN+jGUEgV47UkB+Aw7YpAMAAAB4nGNgZGBgAGLbQwYd8fw2Xxm4WRhA4HrO20sI+n8DCwOzE5DLwcAEEgUAPX4LPgB4nGNgZGBgbvjfwBDDwgACQJKRARWwAgBHCwJueJxjYWBgYH7JwMDCgMAADpsA/QAAAAAAAHYA/AGIAcp4nGNgZGBgYGWIYWBjAAEmIOYCQgaG/2A+AwASVwF+AHicZY9NTsMwEIVf+gekEqqoYIfkBWIBKP0Rq25YVGr3XXTfpk6bKokjx63UA3AejsAJOALcgDvwSCebNpbH37x5Y08A3OAHHo7fLfeRPVwyO3INF7gXrlN/EG6QX4SbaONVuEX9TdjHM6bCbXRheYPXuGL2hHdhDx18CNdwjU/hOvUv4Qb5W7iJO/wKt9Dx6sI+5l5XuI1HL/bHVi+cXqnlQcWhySKTOb+CmV7vkoWt0uqca1vEJlODoF9JU51pW91T7NdD5yIVWZOqCas6SYzKrdnq0AUb5/JRrxeJHoQm5Vhj/rbGAo5xBYUlDowxQhhkiMro6DtVZvSvsUPCXntWPc3ndFsU1P9zhQEC9M9cU7qy0nk6T4E9XxtSdXQrbsuelDSRXs1JErJCXta2VELqATZlV44RelzRiT8oZ0j/AAlabsgAAAB4nGNgYoAALgbsgJWRiZGZkYWRlZGNgbGCuzw1MykzMb8kU1eXs7A0Ma8CiA05CjPz0rPz89IZGADc3QvXAAAA') format('woff')
    }

    .iconfont {
        font-family: "iconfont" !important;
        font-size: 16px;
        font-style: normal;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .icon-fanxuan:before {
        content: "\e837";
    }

    .icon-quanxuan:before {
        content: "\e623";
    }

    .icon-qingkong:before {
        content: "\e63e";
    }
</style>
<link href="/static/admin/plugins/bootstrap/css/edit_bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="/static/admin/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css"/>
<script src="/static/admin/plugins/daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="/static/admin/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<block name="main">
    <fieldset class="layui-elem-field">
        <legend>直播</legend>
        <div class="layui-field-box">
            <form class="layui-form" action="">
                <?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): ?>
                <input type="hidden" name="id" value="<?php echo $detail['sdk_id']; ?>">
                <?php endif; ?>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red">*</span> 直播标题</label>
                    <div class="layui-input-inline" style="line-height: 36px;">
                        <input type="text" name="subject" maxlength="20" required
                               value="<?php echo !empty($detail['subject'])?$detail['subject']:''; ?>" lay-verify="required"
                               placeholder="请输入课程名称" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">老师加入直播密码</label>
                    <div class="layui-input-inline" style="line-height: 36px;">
                        <input type="text" name="teacherToken" maxlength="15"
                               value="<?php echo !empty($detail['teacherToken'])?$detail['teacherToken']:''; ?>"
                               placeholder="请输入密码" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">助教直播密码</label>
                    <div class="layui-input-inline" style="line-height: 36px;">
                        <input type="text" name="assistantToken" maxlength="15"
                               value="<?php echo !empty($detail['assistantToken'])?$detail['assistantToken']:''; ?>"
                               placeholder="请输入密码" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">学员web直播密码</label>
                    <div class="layui-input-inline" style="line-height: 36px;">
                        <input type="text" name="studentToken" maxlength="15"
                               value="<?php echo !empty($detail['studentToken'])?$detail['studentToken']:''; ?>"
                               placeholder="请输入密码" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">学员pc直播密码</label>
                    <div class="layui-input-inline" style="line-height: 36px;">
                        <input type="text" name="studentClientToken" maxlength="15"
                               value="<?php echo !empty($detail['studentClientToken'])?$detail['studentClientToken']:''; ?>"
                               placeholder="请输入密码" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label"><span style="color:red">*</span>创建时间</label>
                    <div class="layui-input-inline">
                        <input type="text" id="test5" name="startDate" placeholder="yyyy-MM-dd HH:mm:ss" required
                               class="layui-input" <?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): ?> value="<?php echo $detail['startDate']; ?>" <?php endif; ?>>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">失效时间</label>
                    <div class="layui-input-inline">
                        <input type="text" id="test6" name="invalidDate" placeholder="yyyy-MM-dd HH:mm:ss"
                               class="layui-input" <?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): ?> value="<?php echo $detail['invalidDate']; ?>" <?php endif; ?>>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">是否要求用户登录</label>
                    <div class="layui-input-block">
                        <input type="radio" name="loginRequired" value="true" title="是" <?php if(isset($detail['loginRequired'])): if(true
                               == $detail['loginRequired']): ?>checked<?php endif; endif; ?>>
                        <input type="radio" name="loginRequired" value="false" title="否" <?php if(isset($detail['loginRequired'])): if(false
                               == $detail['loginRequired']): ?>checked<?php endif; endif; ?> checked>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">老师介绍</label>
                    <div class="layui-input-inline" style="width: 600px;">
                        <textarea class="layui-textarea" name="speakerInfo" maxlength="250"
                                  placeholder="老师介绍"
                                  class="layui-input"><?php echo !empty($detail['speakerInfo'])?$detail['speakerInfo']:''; ?></textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">课程介绍</label>
                    <div class="layui-input-inline" style="width: 600px;">
                        <textarea class="layui-textarea" name="scheduleInfo" maxlength="250"
                                  placeholder="课程介绍"
                                  class="layui-input"><?php echo !empty($detail['scheduleInfo'])?$detail['scheduleInfo']:''; ?></textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">课堂介绍</label>
                    <div class="layui-input-inline" style="width: 600px;">
                        <textarea class="layui-textarea" name="description" maxlength="250"
                                  placeholder="课堂介绍"
                                  class="layui-input"><?php echo !empty($detail['description'])?$detail['description']:''; ?></textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">是否支持web端学员加入</label>
                    <div class="layui-input-block">
                        <input type="radio" name="webJoin" value="true" title="是" <?php if(isset($detail['webJoin'])): if(true
                               == $detail['webJoin']): ?>checked<?php endif; endif; ?> checked>
                        <input type="radio" name="webJoin" value="false" title="否" <?php if(isset($detail['webJoin'])): if(false
                               == $detail['webJoin']): ?>checked<?php endif; endif; ?>>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">是否支持客户端学员加入</label>
                    <div class="layui-input-block">
                        <input type="radio" name="clientJoin" value="true" title="是" <?php if(isset($detail['clientJoin'])): if(true
                               == $detail['clientJoin']): ?>checked<?php endif; endif; ?> checked>
                        <input type="radio" name="clientJoin" value="false" title="否" <?php if(isset($detail['clientJoin'])): if(false
                               == $detail['clientJoin']): ?>checked<?php endif; endif; ?>>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">课堂持续时长</label>
                    <div class="layui-input-inline" style="line-height: 36px;width: 100px">
                        <input type="number" name="duration" maxlength="20"
                               value="<?php echo !empty($detail['duration'])?$detail['duration']:''; ?>"
                               placeholder="请输入课堂持续时长" class="layui-input">
                    </div>（分钟）
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">Web端学生界面设置</label>
                    <div class="layui-input-block">
                        <input type="radio" name="uiMode" value="1" title="三分屏" <?php if(isset($detail['uiMode'])): if(1
                               == $detail['uiMode']): ?>checked<?php endif; endif; ?> checked>
                        <input type="radio" name="uiMode" value="2" title="是文档/视频为主" <?php if(isset($detail['uiMode'])): if(2
                               == $detail['uiMode']): ?>checked<?php endif; endif; ?>>
                        <input type="radio" name="uiMode" value="3" title="两分屏" <?php if(isset($detail['uiMode'])): if(3
                               == $detail['uiMode']): ?>checked<?php endif; endif; ?>>
                        <input type="radio" name="uiMode" value="4" title="互动增加四分屏" <?php if(isset($detail['uiMode'])): if(4
                               == $detail['uiMode']): ?>checked<?php endif; endif; ?>>
                        <input type="radio" name="uiMode" value="5" title="互动增强三分屏" <?php if(isset($detail['uiMode'])): if(5
                               == $detail['uiMode']): ?>checked<?php endif; endif; ?>>
                        <input type="radio" name="uiMode" value="6" title="互动增强全屏" <?php if(isset($detail['uiMode'])): if(6
                               == $detail['uiMode']): ?>checked<?php endif; endif; ?>>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">三分屏颜色选择</label>
                    <div class="layui-input-block">
                        <input type="radio" name="uiColor" value="default" title="灰色" <?php if(isset($detail['uiColor'])): if("default"
                               == $detail['uiColor']): ?>checked<?php endif; endif; ?> checked>
                        <input type="radio" name="uiColor" value="blue" title="蓝色" <?php if(isset($detail['uiColor'])): if("blue"
                               == $detail['uiColor']): ?>checked<?php endif; endif; ?>>
                        <input type="radio" name="uiColor" value="green" title="绿色" <?php if(isset($detail['uiColor'])): if("green"
                               == $detail['uiColor']): ?>checked<?php endif; endif; ?>>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">课别</label>
                    <div class="layui-input-block">
                        <input type="radio" name="scene" value="0" title="大讲堂" <?php if(isset($detail['scene'])): if(0
                               == $detail['scene']): ?>checked<?php endif; endif; ?> checked>
                        <input type="radio" name="scene" value="1" title="小班课" <?php if(isset($detail['scene'])): if(1
                               == $detail['scene']): ?>checked<?php endif; endif; ?>>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">是否显示小窗口</label>
                    <div class="layui-input-block">
                        <input type="radio" name="uiWindow" value="1" title="是" <?php if(isset($detail['uiWindow'])): if(1
                               == $detail['uiWindow']): ?>checked<?php endif; endif; ?>>
                        <input type="radio" name="uiWindow" value="0" title="否" <?php if(isset($detail['uiWindow'])): if(0
                               == $detail['uiWindow']): ?>checked<?php endif; endif; ?> checked>
                    </div>*如果Web端学生界面设置是文档/视频为主时选择
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">是否显示小窗口</label>
                    <div class="layui-input-block">
                        <input type="radio" name="uiVideo" value="1" title="是" <?php if(isset($detail['uiVideo'])): if(1
                               == $detail['uiVideo']): ?>checked<?php endif; endif; ?>>
                        <input type="radio" name="uiVideo" value="0" title="否" <?php if(isset($detail['uiVideo'])): if(0
                               == $detail['uiVideo']): ?>checked<?php endif; endif; ?> checked>
                    </div>*如果Web端学生界面设置是文档/视频为主时选择
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">是否允许web升级到客户端</label>
                    <div class="layui-input-block">
                        <input type="radio" name="upgrade" value="1" title="是" <?php if(isset($detail['upgrade'])): if(1
                               == $detail['upgrade']): ?>checked<?php endif; endif; ?> checked>
                        <input type="radio" name="upgrade" value="0" title="否" <?php if(isset($detail['upgrade'])): if(0
                               == $detail['upgrade']): ?>checked<?php endif; endif; ?>>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">实时状态</label>
                    <div class="layui-input-block">
                        <input type="radio" name="realtime" value="1" title="实时" <?php if(isset($detail['realtime'])): if(1
                               == $detail['realtime']): ?>checked<?php endif; endif; ?>>
                        <input type="radio" name="realtime" value="0" title="非实时" <?php if(isset($detail['realtime'])): if(0
                               == $detail['realtime']): ?>checked<?php endif; endif; ?> checked>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">背景颜色</label>
                    <div class="layui-input-block">
                        <input type="radio" name="playerTheme" value="" title="夜空" <?php if(isset($detail['playerTheme'])): if(""
                               == $detail['playerTheme']): ?>checked<?php endif; endif; ?> checked>
                        <input type="radio" name="playerTheme" value="skyblue" title="雪峰" <?php if(isset($detail['playerTheme'])): if("skyblue"
                               == $detail['playerTheme']): ?>checked<?php endif; endif; ?> >
                    </div>*是互动增强三分屏和互动增强四分屏可以选择
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">最大并发点数</label>
                    <div class="layui-input-inline" style="width: 100px;">
                        <input type="number" name="maxAttendees" <?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): ?> value="<?php echo $detail['maxAttendees']; ?>" <?php endif; ?>  placeholder="可不填" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="admin-form">立即提交</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            </form>
        </div>
    </fieldset>
</block>
<block name="myScript">
    <script>
        function tips(msg) {
            layer.msg(msg, {
                icon: 5,
                shade: [0.6, '#393D49'],
                time: 1500
            });
        }

        //banner
        var host = window.location.host;
        // var imageObj = $("#pic");
        var image_path = '<?php echo !empty($detail["pic"])?$detail["pic"] : ""; ?>';
        if (image_path != '') {
            $("#show").html("<img src='<?php echo !empty($detail["pic"])?$detail["pic"] : ""; ?>' style='height:100px; margin-top:5px;' />");
        }
        $("#pic").val(image_path);
        layui.use('upload', function () {
            var upload = layui.upload;

            //执行实例
            var uploadInst = upload.render({
                elem: '#file' //绑定元素
                , url: '<?php echo url("Upload/index"); ?>' //上传接口
                , accept: 'images' //只允许上传图片
                , acceptMime: 'image/*' //只筛选图片
                , size: 1024 * 3 //限定大小
                , done: function (res) {
                    //上传完毕回调
                    console.log(res); //上传成功返回值，必须为json格式
                    if (res.type == 1) {
                        $("#show").html("<img src='" + res.image_path + "' style='height:100px; margin-top:5px;' />");
                        $("#pic").val(res.image_path);
                    } else {
                        tips(res.msg);
                    }
                }
                , error: function () {
                    //请求异常回调
                    tips('上传错误');
                }
            });

            // var options = {
            //     elem: '#file',
            //     url: '<?php echo url("upload/index"); ?>',
            //     ext: 'jpg|png|jpeg',
            //     before: function(input){
            //         console.log('文件上传中');
            //     },
            //     success: function(res){
            //         console.log(res); //上传成功返回值，必须为json格式
            //         $("#show").html("<img src='http://"+host+"/"+res.image_path+"' style='height:100px; margin-top:5px;' />");
            //         $("#pic").val(res.image_path);
            //     }
            // };
            // layui.upload(options);
        })
    </script>
    <?php if(isset($detail['id'])): ?>
    <script>
        layui.use('form', function () {
            //日期时间选择器
            laydate.render({
                elem: '#test5'
                , type: 'datetime'
            });
            //日期时间选择器
            laydate.render({
                elem: '#test6'
                , type: 'datetime'
            });

            var form = layui.form;
            //常规用法

            form.on('submit(admin-form)', function (data) {
                var data = data.field;
                delete(data['link']);
                data.pic = $('#pic').val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo url('Webcast/edit'); ?>",
                    data: data,
                    success: function (msg) {
                        if (msg.code == 1) {
                            parent.location.reload();
                        } else {
                            parent.layer.msg(msg.msg, {
                                icon: 5,
                                shade: [0.6, '#393D49'],
                                time: 1500
                            });
                        }
                    }
                });
                return false;
            });

        });
    </script>
    <?php else: ?>
    <script>
        layui.use('form', function () {
            //日期时间选择器
            laydate.render({
                elem: '#test5'
                , type: 'datetime'
                , btns: ['confirm']
                , theme: 'grid'
                , trigger: 'click'
            });
            //日期时间选择器
            laydate.render({
                elem: '#test6'
                , type: 'datetime'
                , btns: ['confirm']
                , theme: 'grid'
                , trigger: 'click'
            });

            var form = layui.form;
            form.on('submit(admin-form)', function (data) {
                var data = data.field;
                delete(data['link']);

                $.ajax({
                    type: "POST",
                    url: "<?php echo url('Webcast/add'); ?>",
                    data: data,
                    success: function (msg) {
                        if (msg.code == 1) {
                            parent.location.reload();
                        } else {
                            parent.layer.msg(msg.msg, {
                                icon: 5,
                                shade: [0.6, '#393D49'],
                                time: 1500
                            });
                        }
                    }
                });
                return false;
            });

        });
    </script>
    <?php endif; ?>
    <!-- 配置文件 -->
    <script type="text/javascript" src="/static/admin/ueditor/ueditor.config.js"></script>
    <!-- 编辑器源码文件 -->
    <script type="text/javascript" src="/static/admin/ueditor/ueditor.all.js"></script>
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var details_ue = UE.getEditor('details');
    </script>
</block>