<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:53:"D:\pgt\public/../application/admin\view\Role\add.html";i:1599547053;s:46:"D:\pgt\application\admin\view\Public\base.html";i:1599547052;}*/ ?>
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
        <legend>角色管理 - <?php echo !empty($detail['id'])?'编辑':'新增'; ?>角色</legend>
        <div class="layui-field-box">
            <form class="layui-form" action="">
                <?php if(!(empty($detail) || (($detail instanceof \think\Collection || $detail instanceof \think\Paginator ) && $detail->isEmpty()))): ?>
                <input type="hidden" name="id" value="<?php echo $detail['id']; ?>">
                <?php endif; ?>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color:red">*</span> 角色名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" maxlength="12" required value="<?php echo !empty($detail['name'])?$detail['name']:''; ?>" lay-verify="required" placeholder="请输入权限组名称" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">角色描述</label>
                    <div class="layui-input-block">
                        <textarea name="description" placeholder="请输入权限组描述" maxlength="50" class="layui-textarea"><?php echo !empty($detail['description'])?$detail['description']:''; ?></textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">角色权限</label>
                    <div class="layui-input-block">
                        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <div class="layui-form-item">
                                <input lay-skin="primary" type="checkbox" data-id="<?php echo $vo['id']; ?>" lay-filter="admin-check" name="rule[<?php echo $vo['id']; ?>]" value="<?php echo $vo['url']; ?>" title="<?php echo $vo['name']; ?>" <?php if(isset($hasRule) && in_array($vo['url'],$hasRule)): ?>checked<?php endif; ?>>
                            </div>
                            <?php if(isset($vo['_child'])): ?>
                                <div class="layui-form-item">
                                    <div style="margin-left: 50px;">
                                        <?php if(is_array($vo['_child']) || $vo['_child'] instanceof \think\Collection || $vo['_child'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['_child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child): $mod = ($i % 2 );++$i;?>
                                            <input lay-skin="primary" type="checkbox" lay-filter="admin-check" data-id="<?php echo $child['id']; ?>" fid="<?php echo $vo['id']; ?>" name="rule[<?php echo $child['id']; ?>]" value="<?php echo $child['url']; ?>" title="<?php echo $child['name']; ?>" <?php if(isset($hasRule) && in_array($child['url'],$hasRule)): ?>checked<?php endif; ?>>
                                            <?php if(isset($child['_child'])): ?>
                                                <div style="margin-left: 50px;">
                                                    <?php if(is_array($child['_child']) || $child['_child'] instanceof \think\Collection || $child['_child'] instanceof \think\Paginator): $i = 0; $__LIST__ = $child['_child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$_child): $mod = ($i % 2 );++$i;?>
                                                        <input lay-skin="primary" type="checkbox" pid="<?php echo $vo['id']; ?>" data-id="<?php echo $_child['id']; ?>" fid="<?php echo $child['id']; ?>" name="rule[<?php echo $_child['id']; ?>]" value="<?php echo $_child['url']; ?>" title="<?php echo $_child['name']; ?>" <?php if(isset($hasRule) && in_array($_child['url'],$hasRule)): ?>checked<?php endif; ?>>
                                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                                </div>
                                            <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                                    </div>
                                </div>
                            <?php endif; endforeach; endif; else: echo "" ;endif; ?>
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
                form.on('checkbox(admin-check)', function(data){
                    var dataId = $(this).attr('data-id');
                    var $el = data.elem;
                    if( $el.checked ){
                        $('input[fid="'+dataId+'"]').prop('checked','checked');
                        $('input[pid="'+dataId+'"]').prop('checked','checked');
                    }else{
                        $('input[fid="'+dataId+'"]').prop('checked', false);
                        $('input[pid="'+dataId+'"]').prop('checked', false);
                    }
                    form.render();
                });
                form.on('submit(admin-form)', function(data){
                    $.ajax({
                        type: "POST",
                        url: "<?php echo url('role/edit'); ?>",
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
                form.on('checkbox(admin-check)', function(data){
                    var dataId = $(this).attr('data-id');
                    var $el = data.elem;
                    if( $el.checked ){
                        $('input[fid="'+dataId+'"]').prop('checked','checked');
                        $('input[pid="'+dataId+'"]').prop('checked','checked');
                    }else{
                        $('input[fid="'+dataId+'"]').prop('checked', false);
                        $('input[pid="'+dataId+'"]').prop('checked', false);
                    }
                    form.render();
                });
                form.on('submit(admin-form)', function(data){
                    $.ajax({
                        type: "POST",
                        url: "<?php echo url('role/add'); ?>",
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