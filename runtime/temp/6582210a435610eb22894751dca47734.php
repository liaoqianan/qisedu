<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:78:"/www/wwwroot/peigongtang/public/../application/admin/view/Course_node/add.html";i:1611558308;s:65:"/www/wwwroot/peigongtang/application/admin/view/Public/base2.html";i:1608535069;}*/ ?>
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
        src: url('data:application/x-font-woff;charset=utf-8;base64,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') format('woff')
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
        <legend>课节管理</legend>
        <div class="layui-field-box">
            <form class="layui-form" action="">
                <?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): ?>
                <input type="hidden" name="id" value="<?php echo $detail['id']; ?>">
                <?php endif; ?>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red">*</span> 课节标题</label>
                    <div class="layui-input-inline" style="line-height: 36px;">
                        <input type="text" name="title" maxlength="20" required
                               value="<?php echo !empty($detail['title'])?$detail['title']:''; ?>" lay-verify="required"
                               placeholder="请输入课节名称" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red">*</span> 课节封面</label>
                    <div class="layui-input-inline" style="width: 150px">
                        <span class="layui-btn" name="file" id="file">上传图片</span>
                        <input type="hidden" id="pic" name="pic"
                               value="<?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): ?><?php echo $detail['pic']; endif; ?>">
                        <div id="show"><?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): ?><img src="<?php echo !empty($detail['pic'])?$detail['pic'] : ''; ?>"
                                                                    height="100"><?php endif; ?>
                        </div>
                    </div>
                    <span style="color:red">
                        图片格式为jpg或png。
                    </span>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red">*</span> 选择课程</label>
                    <div class="layui-input-inline" style="width: 500px; ">
                        <select name="course_id">
                            <option value="">请选择</option>
                            <?php if(is_array($course) || $course instanceof \think\Collection || $course instanceof \think\Paginator): if( count($course)==0 ) : echo "" ;else: foreach($course as $key=>$vo): ?>
                            <option <?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): if($detail[
                            'course_id']==$vo['id']): ?> selected <?php endif; endif; ?> value="<?php echo $vo['id']; ?>"><?php echo $vo['title']; ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">课节类型</label>
                    <div class="layui-input-block">
                        <input type="radio" name="type" value="1" title="直播" <?php if(isset($detail['type'])): if(1
                               == $detail['type']): ?>checked<?php endif; endif; ?> checked>
                        <input type="radio" name="type" value="2" title="回放" <?php if(isset($detail['type'])): if(2
                               == $detail['type']): ?>checked<?php endif; endif; ?>>
                        <input type="radio" name="type" value="3" title="视频" <?php if(isset($detail['type'])): if(3
                               == $detail['type']): ?>checked<?php endif; endif; ?>>
                        <input type="radio" name="type" value="4" title="音频" <?php if(isset($detail['type'])): if(4
                               == $detail['type']): ?>checked<?php endif; endif; ?>>
                        <input type="radio" name="type" value="5" title="图文" <?php if(isset($detail['type'])): if(5
                               == $detail['type']): ?>checked<?php endif; endif; ?>>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"> 视频文件</label>
                    <div class="layui-input-inline" style="width: 350px">
                        <span class="layui-btn layui-btn-sm layui-btn-normal" id="video_file"><i class="layui-icon">&#xe681;</i>视频</span>
                        <input type="hidden" id="video" name="video"
                               value="<?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): ?><?php echo $detail['video']; endif; ?>">
                        <div id="show_video"><?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): if(!(empty($detail['video']) || (($detail['video'] instanceof \think\Collection || $detail['video'] instanceof \think\Paginator ) && $detail['video']->isEmpty()))): ?>
                            <video src="<?php echo !empty($detail['video'])?$detail['video'] : ''; ?>" controls height="200px"
                                   width="200px"></video>
                            <?php endif; endif; ?>
                        </div>
                        <div class="layui-progress" lay-filter="progressBar" style="display: none;margin: 10px">
                            <div class="layui-progress-bar" lay-percent="0%"></div>
                        </div>
                    </div>
                    <span style="color:red">
                        格式为mp4。
                    </span>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"> 音频文件</label>
                    <div class="layui-input-inline" style="width: 350px">
                        <span class="layui-btn layui-btn-sm layui-btn-normal" id="music_file"><i class="layui-icon">&#xe681;</i>音频</span>
                        <input type="hidden" id="music" name="music" value="<?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): ?><?php echo $detail['music']; endif; ?>">
                        <div id="show_music"><?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): if(!(empty($detail['music']) || (($detail['music'] instanceof \think\Collection || $detail['music'] instanceof \think\Paginator ) && $detail['music']->isEmpty()))): ?>
                            <video src="<?php echo !empty($detail['music'])?$detail['music'] : ''; ?>" controls height="200px"
                                   width="200px"></video>
                            <?php endif; endif; ?>
                        </div>
                        <div class="layui-progress" lay-filter="progressBar" style="display: none;margin: 10px">
                            <div class="layui-progress-bar" lay-percent="0%"></div>
                        </div>
                    </div>
                    <span style="color:red">
                        格式为mp3。
                    </span>
                </div>
                <div class="layui-form-item" style="display: none">
                    <label class="layui-form-label">参数</label>
                    <div class="layui-input-inline" style="line-height: 36px;">
                        <input type="text" name="parameter" id="parameter" maxlength="50"
                               value="<?php echo !empty($detail['parameter'])?$detail['parameter']:''; ?>"
                               placeholder="请输入参数" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">是否收费</label>
                    <div class="layui-input-block">
                        <input type="radio" name="is_gratis" value="1" title="收费" <?php if(isset($detail['is_gratis'])): if(1 == $detail['is_gratis']): ?>checked<?php endif; endif; ?>
                        checked>
                        <input type="radio" name="is_gratis" value="0" title="免费" <?php if(isset($detail['is_gratis'])): if(0 == $detail['is_gratis']): ?>checked<?php endif; endif; ?>>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">开课时间</label>
                    <div class="layui-input-inline">
                        <input type="text" id="test5" name="broadcast_time" placeholder="yyyy-MM-dd HH:mm:ss（点播不填）"
                               class="layui-input" <?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): ?> value="<?php echo $detail['broadcast_time']; ?>" <?php endif; ?>>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">可观看时长</label>
                    <div class="layui-input-inline" style="line-height: 36px;">
                        <input type="number" name="watch_time" maxlength="20"
                               value="<?php echo !empty($detail['watch_time'])?$detail['watch_time']:''; ?>" placeholder="请输入观看时长（秒）"
                               class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">讲课老师</label>
                    <div class="layui-input-inline" style="line-height: 36px;">
                        <input type="text" name="teacher" maxlength="20"
                               value="<?php echo !empty($detail['teacher'])?$detail['teacher']:''; ?>" placeholder="请输入讲课老师"
                               class="layui-input">*直播填
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red">*</span> 排序</label>
                    <div class="layui-input-inline" style="line-height: 36px;">
                        <input type="number" name="order" maxlength="20" required
                               value="<?php echo !empty($detail['order'])?$detail['order']:''; ?>" lay-verify="required"
                               placeholder="排序（数字越大越靠前）" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red">*</span> 课节简介</label>
                    <div class="layui-input-inline" style="width: 600px;">
                        <textarea class="layui-textarea" name="brief" maxlength="100" required lay-verify="required"
                                  placeholder="课节简介"
                                  class="layui-input"><?php echo !empty($detail['brief'])?$detail['brief']:''; ?></textarea>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label"><span style="color:red">*</span>课节详情:</label>
                        <div class="layui-input-block" style="width: 600px;">
                            <script style="height: 500px" id="details" name="details" type="text/plain"><?php echo !empty($detail['details'])?$detail['details'] : ''; ?></script>
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
        layui.use(['upload','element'], function () {
            var element = layui.element;
            var upload = layui.upload;
            //此时加在这里，对进度条进行初始化，会发现进度条不显示数字
            element.init();
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
            var uploadInst = upload.render({
                elem: '#video_file' //绑定元素
                , url: '<?php echo url("Video/add"); ?>' //上传接口
                , accept: 'video' //只允许上传视频
                , acceptMime: 'video/*' //只筛选视频
                , size: 1024 * 100 //限定大小
                , before: function () {
                    console.log(element.progress('progressBar', '0%'));

                    $('#video_file').parent().find('.layui-progress').show();
                }
                , xhr: xhrOnProgress
                , progress: function (n) {  //上传进度回调 value进度值
                    element.progress('progressBar', n + '%');
                }
                , done: function (res) {
                    //上传完毕回调
                    console.log(res); //上传成功返回值，必须为json格式
                    $('#video_file').parent().find('.layui-progress').hide();

                    if (res.type == 1) {
                        $("#show_video").html("<video src='" + res.url + "' controls height='200px' width='200px'></video>");
                        $("#video").val(res.url);
                        $("#parameter").val(res.parameter);
                    } else {
                        tips(res.msg);
                    }
                }
                , error: function () {
                    //请求异常回调
                    $('#video_file').parent().find('.layui-progress').hide();
                    tips('上传错误');
                }
            });
            var uploadInst = upload.render({
                elem: '#music_file' //绑定元素
                , url: '<?php echo url("Video/add"); ?>' //上传接口
                , accept: 'audio' //只允许上传音频
                , acceptMime: 'audio/*' //只筛选音频
                , size: 1024 * 100 //限定大小
                , before: function () {
                    console.log(element.progress('progressBar', '0%'));

                    $('#music_file').parent().find('.layui-progress').show();
                }
                , xhr: xhrOnProgress
                , progress: function (n) {  //上传进度回调 value进度值
                    element.progress('progressBar', n + '%');
                }
                , done: function (res) {
                    //上传完毕回调
                    console.log(res); //上传成功返回值，必须为json格式
                    $('#music_file').parent().find('.layui-progress').hide();

                    if (res.type == 1) {
                        $("#show_music").html("<audio src='" + res.url + "' controls></audio>");
                        $("#music").val(res.url);
                        $("#parameter").val(res.parameter);
                    } else {
                        tips(res.msg);
                    }
                }
                , error: function () {
                    //请求异常回调
                    $('#music_file').parent().find('.layui-progress').hide();
                    tips('上传错误');
                }
            });
            var xhrOnProgress=function(fun) {
                xhrOnProgress.onprogress = fun; //绑定监听
                //使用闭包实现监听绑
                return function() {
                    //通过$.ajaxSettings.xhr();获得XMLHttpRequest对象
                    var xhr = $.ajaxSettings.xhr();
                    //判断监听函数是否为函数
                    if (typeof xhrOnProgress.onprogress !== 'function')
                        return xhr;
                    //如果有监听函数并且xhr对象支持绑定时就把监听函数绑定上去
                    if (xhrOnProgress.onprogress && xhr.upload) {
                        xhr.upload.onprogress = xhrOnProgress.onprogress;
                    }
                    return xhr;
                }
            }
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

            var form = layui.form;
            //常规用法

            form.on('submit(admin-form)', function (data) {
                var data = data.field;
                delete(data['link']);
                data.pic = $('#pic').val();
                if (data.pic == '') {
                    parent.layer.msg('请上传图片', {
                        icon: 5,
                        shade: [0.6, '#393D49'],
                        time: 1500
                    });
                    return false;
                }
                if (data.course_id == '') {
                    parent.layer.msg('请选择栏目', {
                        icon: 5,
                        shade: [0.6, '#393D49'],
                        time: 1500
                    });
                    return false;
                }
                if (data.type == 1) {
                    if (data.broadcast_time == '') {
                        parent.layer.msg('请选择开课时间', {
                            icon: 5,
                            shade: [0.6, '#393D49'],
                            time: 1500
                        });
                        return false;
                    }
                }
                $.ajax({
                    type: "POST",
                    url: "<?php echo url('Course/course_node_edit'); ?>",
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
            var form = layui.form;
            form.on('submit(admin-form)', function (data) {
                var data = data.field;
                delete(data['link']);
                data.pic = $('#pic').val();
                if (data.pic == '') {
                    parent.layer.msg('请上传图片', {
                        icon: 5,
                        shade: [0.6, '#393D49'],
                        time: 1500
                    });
                    return false;
                }
                if (data.course_id == '') {
                    parent.layer.msg('请选择栏目', {
                        icon: 5,
                        shade: [0.6, '#393D49'],
                        time: 1500
                    });
                    console.log(data.course_id);
                    return false;
                }
                if (data.type == 1) {
                    if (data.broadcast_time == '') {
                        parent.layer.msg('请选择开课时间', {
                            icon: 5,
                            shade: [0.6, '#393D49'],
                            time: 1500
                        });
                        return false;
                    }
                }
                $.ajax({
                    type: "POST",
                    url: "<?php echo url('Course/course_node_add'); ?>",
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