<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:76:"/www/wwwroot/peigongtang/public/../application/admin/view/AdminUser/add.html";i:1608535056;s:64:"/www/wwwroot/peigongtang/application/admin/view/Public/base.html";i:1610075700;}*/ ?>
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
<block name="main">
    <fieldset class="layui-elem-field">
        <legend>人员管理 - <?php echo !empty($detail['id'])?'编辑':'新增'; ?>人员</legend>
        <div class="layui-field-box">
            <form class="layui-form" action="">
                <?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): ?>
                    <input type="hidden" name="id" value="<?php echo $detail['id']; ?>">
                <?php endif; ?>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red">*</span>用户账号</label>
                    <div class="layui-input-block" style="line-height: 36px;">
                        <input type="text" name="username" maxlength="12" required value="<?php echo !empty($detail['username'])?$detail['username']:''; ?>" lay-verify="required" placeholder="请输入登陆账号" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red">*</span> 真实姓名</label>
                    <div class="layui-input-block">
                        <input type="text" name="nickname" maxlength="12" required value="<?php echo !empty($detail['nickname'])?$detail['nickname']:''; ?>" lay-verify="required" placeholder="请输入真实姓名" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label" style="margin-right: 15px;"><span style="color:red">*</span> 用户密码</label>
                    <?php if(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty())): ?>
                    <div class="layui-input-inline">
                        <input type="password" name="password" maxlength="32" required lay-verify="required" value="<?php echo !empty($detail['password'])?'':'wxys2020'; ?>" placeholder="请输入密码" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">默认:wxys2020</div>
                    <?php else: ?>
                    <div class="layui-input-inline">
                        <input type="password" name="password" value="" placeholder="不修改请留空" class="layui-input">
                    </div>
                    <?php endif; ?>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red">*</span> 手机号码</label>
                    <div class="layui-input-block">
                        <input type="text" name="mobile" maxlength="20" required value="<?php echo !empty($detail['mobile'])?$detail['mobile']:''; ?>" lay-verify="required" placeholder="请输入手机号码" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red">*</span> 所属角色</label>
                    <div class="layui-input-block">
                        <select class="m-wrap" name="groupId" required lay-verify="required">
                            <?php if(is_array($roles) || $roles instanceof \think\Collection || $roles instanceof \think\Paginator): $i = 0; $__LIST__ = $roles;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                <option value="<?php echo $vo['id']; ?>" <?php if(isset($detail) && $detail['groupId'] == $vo['id']): ?>selected<?php endif; ?>><?php echo $vo['name']; ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
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
    <?php if(isset($detail['id'])): ?>
        <script>
            layui.use('form', function(){
                var form = layui.form();
                form.on('submit(admin-form)', function(data){
                    $.ajax({
                        type: "POST",
                        url: '<?php echo url("edit"); ?>',
                        data: data.field,
                        success: function(msg){
                            if( msg.code == 1 ){
                                parent.location.reload();
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
        <?php else: ?>
        <script>
            layui.use('form', function(){
                var form = layui.form();
                form.on('submit(admin-form)', function(data){
                    $.ajax({
                        type: "POST",
                        url: '<?php echo url("add"); ?>',
                        data: data.field,
                        success: function(msg){
                            if( msg.code == 1 ){
                                parent.location.reload();
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
    <?php endif; ?>
</block>