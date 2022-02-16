<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:62:"D:\pgt\public/../application/admin\view\Music\addCategory.html";i:1599547055;s:46:"D:\pgt\application\admin\view\Public\base.html";i:1599547052;}*/ ?>
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
<style type="text/css">
    .layui-input-block ul{margin-top: 5px;}
	.layui-input-block ul li {list-style:none; display:inline-block; position:relative; border: 1px solid #DFDFDF; margin: 0 2px;}
	.my-icons { color: #ffff00; position: absolute; right: 5px; z-index: 2147483647; cursor: pointer; top: 5px;}
	.myiframe { border:0; height: 650px; width: 100%;}
    .layui-form-select dl {z-index:99999;}
</style>
<block name="main">
    <fieldset class="layui-elem-field">
        <legend><?php echo !empty($detail['id'])?'编辑':'新增'; ?>分类</legend>
        <div class="layui-field-box">
            <form class="layui-form" action="">
                <?php if(isset($detail['id'])): ?>
                <input type="hidden" name="id" value="<?php echo $detail['id']; ?>">
                <?php endif; ?>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red">*</span>分类名称:</label>
                    <div class="layui-input-block" style="line-height: 36px;">
                        <input type="text" name="name" required value="<?php echo !empty($detail['name'])?$detail['name']:''; ?>" maxlength="100" lay-verify="required" placeholder="请输入分类名称" class="layui-input">
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
                time:1500
            });
        }	
						
	</script>	
    <?php if(isset($detail['id'])): ?>
        <script>
            layui.use('form', function(){
                var form = layui.form();
                form.on('submit(admin-form)', function(data){
                    $.ajax({
                        type: "POST",
                        url: '<?php echo url("editCategory"); ?>',
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
                        url: '<?php echo url("addCategory"); ?>',
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