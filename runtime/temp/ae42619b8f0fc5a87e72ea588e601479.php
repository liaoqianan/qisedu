<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:75:"/www/wwwroot/peigongtang/public/../application/admin/view/Config/index.html";i:1610075720;s:65:"/www/wwwroot/peigongtang/application/admin/view/Public/base2.html";i:1608535069;}*/ ?>
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
    .layui-form-label{
        width: 110px;
    }
</style>
<block name="main">
    <fieldset class="layui-elem-field">
        <legend>系统配置</legend>
        <div class="layui-field-box">
            <form class="layui-form" action="">
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label"><span style="color:red">*</span>关于我们:</label>
                    <div class="layui-input-block" style="width: 600px;">
                        <script style="height: 500px" id="about_us" name="about_us" type="text/plain"><?php echo !empty($detail['about_us'])?$detail['about_us'] : ''; ?></script>
                    </div>
                </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">公众号二维码</label>
                    <div class="layui-input-inline" style="width: 150px">
                        <span class="layui-btn" name="file" id="file">上传图片</span>
                        <input type="hidden" id="pic" name="wechat"
                    value="<?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): ?><?php echo $detail['wechat']; endif; ?>">
                        <div id="show"><?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): ?><img src="<?php echo !empty($detail['wechat'])?$detail['wechat'] : ''; ?>"
                    height="100"><?php endif; ?>
                    </div>
                    </div>
                    <span style="color:red">
                        图片格式为jpg或png。
                    </span>
                    </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label"><span style="color:red">*</span>第三方条款:</label>
                    <div class="layui-input-block" style="width: 600px;">
                        <script style="height: 500px" id="third_clause" name="third_clause" type="text/plain"><?php echo !empty($detail['third_clause'])?$detail['third_clause'] : ''; ?></script>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label"><span style="color:red">*</span>第三方声明:</label>
                    <div class="layui-input-block" style="width: 600px;">
                        <script style="height: 500px" id="third_explain" name="third_explain" type="text/plain"><?php echo !empty($detail['third_explain'])?$detail['third_explain'] : ''; ?></script>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label"><span style="color:red">*</span>隐私策略:</label>
                    <div class="layui-input-block" style="width: 600px;">
                        <script style="height: 500px" id="privacy_strategy" name="privacy_strategy" type="text/plain"><?php echo !empty($detail['privacy_strategy'])?$detail['privacy_strategy'] : ''; ?></script>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label"><span style="color:red">*</span>隐私政策:</label>
                    <div class="layui-input-block" style="width: 600px;">
                        <script style="height: 500px" id="privacy_policy" name="privacy_policy" type="text/plain"><?php echo !empty($detail['privacy_policy'])?$detail['privacy_policy'] : ''; ?></script>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label"><span style="color:red">*</span>用户协议:</label>
                    <div class="layui-input-block" style="width: 600px;">
                        <script style="height: 500px" id="user_protocol" name="user_protocol" type="text/plain"><?php echo !empty($detail['user_protocol'])?$detail['user_protocol'] : ''; ?></script>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label"><span style="color:red">*</span>帮助中心:</label>
                    <div class="layui-input-block" style="width: 600px;">
                        <script style="height: 500px" id="help_center" name="help_center" type="text/plain"><?php echo !empty($detail['help_center'])?$detail['help_center'] : ''; ?></script>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label"><span style="color:red">*</span>发送反馈:</label>
                    <div class="layui-input-block" style="width: 600px;">
                        <textarea style="height:300px" class="layui-textarea" name="feedback"><?php echo !empty($detail['feedback'])?$detail['feedback'] : ''; ?></textarea>
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
                        layui.use('form', function () {
                            var form = layui.form;
                            //常规用法

                            form.on('submit(admin-form)', function (data) {
                                var data = data.field;
                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo url('Config/index'); ?>",
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
                            var image_path = '<?php echo !empty($detail["wechat"])?$detail["wechat"] : ""; ?>';
                            if (image_path != '') {
                                $("#show").html("<img src='<?php echo !empty($detail["wechat"])?$detail["wechat"] : ""; ?>' style = 'height:100px; margin-top:5px;' / > ");
                            }
                            $("#pic").val(image_path);
                            layui.use('upload', function () {
                                var $ = layui.jquery
                                    ,upload = layui.upload;

                                console.log(upload);
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
                                            $("#show").html("<img src='" + res.image_path + "' style='height:100px; margin-top:5px;'/>");
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
                            })
                        </script>
    <!-- 配置文件 -->
    <script type="text/javascript" src="/static/admin/ueditor/ueditor.config.js"></script>
    <!-- 编辑器源码文件 -->
    <script type="text/javascript" src="/static/admin/ueditor/ueditor.all.js"></script>
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var about_us_ue = UE.getEditor('about_us');
        var third_clause_ue = UE.getEditor('third_clause');
        var third_explain_ue = UE.getEditor('third_explain');
        var privacy_strategy_ue = UE.getEditor('privacy_strategy');
        var privacy_policy_ue = UE.getEditor('privacy_policy');
        var user_protocol_ue = UE.getEditor('user_protocol');
        var help_center_ue = UE.getEditor('help_center');
    </script>
</block>
