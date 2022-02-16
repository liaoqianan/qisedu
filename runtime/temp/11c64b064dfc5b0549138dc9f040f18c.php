<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:66:"/www/wwwroot/qisedu/public/../application/admin/view/Menu/add.html";i:1620814255;s:59:"/www/wwwroot/qisedu/application/admin/view/Public/base.html";i:1620814255;}*/ ?>
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
        <legend>菜单维护 - <?php echo !empty($detail['id'])?'编辑':'新增'; ?>菜单</legend>
        <div class="layui-field-box">
            <form class="layui-form" action="">
                <?php if(isset($detail['id'])): ?>
                    <input type="hidden" name="id" value="<?php echo $detail['id']; ?>">
                <?php endif; ?>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red">*</span> 菜单名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" maxlength="12" required value="<?php echo !empty($detail['name'])?$detail['name']:''; ?>" lay-verify="required" placeholder="请输入菜单名称" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red">*</span> 父级菜单</label>
                    <div class="layui-input-block">
                        <select name="fid" lay-verify="" lay-search="">
                            <option value="0">顶级菜单</option>
                            <?php if(is_array($options) || $options instanceof \think\Collection || $options instanceof \think\Paginator): $i = 0; $__LIST__ = $options;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                <option value="<?php echo $key; ?>" <?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): ?><?php echo $detail['fid']==$key?'selected':''; endif; ?>><?php echo $vo; ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red">*</span> 是否隐藏</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="hide"lay-skin="switch" lay-text="隐藏|显示" <?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): ?><?php echo $detail['hide']==1?'checked':''; endif; ?>>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">菜单URL</label>
                    <div class="layui-input-block">
                        <input type="text" name="url" maxlength="30" value="<?php echo !empty($detail['url'])?$detail['url']:''; ?>" placeholder="请输入菜单URL" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">菜单排序</label>
                    <div class="layui-input-block">
                        <input type="num" name="sort" maxlength="5" lay-verify="required|number" value="<?php echo !empty($detail['sort'])?$detail['sort']:''; ?>" placeholder="请输入正整数，越大排名越靠后" class="layui-input">
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
                        url: "<?php echo url('menu/edit'); ?>",
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
                        url: "<?php echo url('menu/add'); ?>",
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