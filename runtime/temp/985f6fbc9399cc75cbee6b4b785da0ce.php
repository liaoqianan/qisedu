<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:57:"D:\pgt\public/../application/admin\view\Config\index.html";i:1599547057;s:46:"D:\pgt\application\admin\view\Public\base.html";i:1599547052;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no,email=no,address=no">
    <title><?php echo config('APP_NAME'); ?>管理后台</title>
    <link rel="stylesheet" href="/static/admin/plugins/layui/css/layui.css">
    <script type="text/javascript" src="/static/admin/plugins/laydate/laydate.js"></script>
    <script type="text/javascript" src="/static/admin/plugins/layui/layui.js"></script>
    <script src="/static/admin/js/jquery.min.js"></script>
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
<script>
        $eb = parent._mpApi;
        window.controlle="<?php echo strtolower(trim(preg_replace("/[A-Z]/", "_\\0", think\Request::instance()->controller()), "_"));?>";
        window.module="<?php echo think\Request::instance()->module();?>";
    </script>
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
                    <label class="layui-form-label"><span style="color:red">*</span>关于我们:</label>
                    <div class="layui-input-block" style="width: 600px;">
                        <textarea style="height:300px" class="layui-textarea" name="about_us"><?php echo !empty($detail['about_us'])?$detail['about_us'] : ''; ?></textarea>
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

        layui.use('form', function(){
            var form = layui.form();
            form.on('submit(admin-form)', function(data){
                var data = data.field;

                $.ajax({
                    type: "POST",
                    url: '/Config/index',
                    data: data,
                    success: function(msg){
                        if( msg.code == 1 ){
                            parent.layer.msg('保存成功', {
                                icon: 1,
                                shade: [0.6, '#393D49'],
                                time:1500
                            });
                        }else{
                            parent.layer.msg(msg.msg, {
                                icon: 5,
                                shade: [0.6, '#393D49'],
                                time:1500
                            });
                        }
                    }
                });
                return false;
            });

        });
    </script>
    <!-- 配置文件 -->
    <script type="text/javascript" src="/static/admin/ueditor/ueditor.config.js"></script>
    <!-- 编辑器源码文件 -->
    <script type="text/javascript" src="/static/admin/ueditor/ueditor.all.js"></script>
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var third_clause_ue = UE.getEditor('third_clause');
        var third_explain_ue = UE.getEditor('third_explain');
        var privacy_strategy_ue = UE.getEditor('privacy_strategy');
        var privacy_policy_ue = UE.getEditor('privacy_policy');
        var user_protocol_ue = UE.getEditor('user_protocol');
        var help_center_ue = UE.getEditor('help_center');
    </script>
</block>
