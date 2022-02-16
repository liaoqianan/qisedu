<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:75:"/www/wwwroot/peigongtang/public/../application/admin/view/banner/index.html";i:1608535058;s:64:"/www/wwwroot/peigongtang/application/admin/view/Public/base.html";i:1610075700;}*/ ?>
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
        <legend>轮播图维护</legend>
        <div class="layui-field-box">
            <span class="layui-btn layui-btn-normal api-add" data-title="新增"><i class="layui-icon">&#xe608;</i>
                新增</span>
            <table class="layui-table" lay-even>
                <thead>
                <tr>
                    <th width="100">编号</th>
                    <th width="250">轮播图</th>
                    <th width="100">排序</th>
                    <th width="100">发布时间</th>
                    <th width="100">隐藏</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody id="menu-list">
                <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><img src="<?php echo $vo['pic']; ?>" height="50px" alt=""></td>
                    <td><?php echo $vo['order']; ?></td>
                    <td><?php echo $vo['create_time']; ?></td>
                    <td>
                        <?php if($vo['status']): ?>
                        <span class="layui-btn layui-btn-small layui-btn-danger confirm" data-info="你确定隐藏当前轮播图么？" data-title="隐藏" data-id="<?php echo $vo['id']; ?>" data-url="<?php echo url('close'); ?>">停用</span>
                        <?php else: ?>
                        <span class="layui-btn layui-btn-small confirm" data-info="你确定显示当前轮播图么？" data-title="显示" data-id="<?php echo $vo['id']; ?>" data-url="<?php echo url('open'); ?>">启用</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span data-url="<?php echo url('edit'); ?>" data-id="<?php echo $vo['id']; ?>" data-title="编辑人员" class="layui-btn layui-btn-small edit layui-btn-normal"><i class="layui-icon">&#xe642;</i></span>
                        <span class="layui-btn layui-btn-small layui-btn-danger confirm" data-id="<?php echo $vo['id']; ?>" data-title="删除用户" data-info="你确定删除该用户么？" data-url="<?php echo url('del'); ?>"><i class="layui-icon">&#xe640;</i></span>
                    </td>
                </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
        </div>
    </fieldset>
</block>
<block name="myScript">
    <script>
        layui.use(['form', 'layer'], function() {
            var form = layui.form();

            $('.api-add').on('click', function () {
                var ownObj = $(this);
                layer.open({
                    type: 2,
                    offset:'35px',
                    title: ownObj.attr('data-title'),
                    area: ['80%', '80%'],
                    maxmin: true,
                    content: '<?php echo url("add"); ?>'
                });
            });
            $(document).on('click', '.edit', function () {
                var ownObj = $(this);
                layer.open({
                    type: 2,
                    offset:'35px',
                    area: ['80%', '80%'],
                    title: ownObj.attr('data-title'),
                    maxmin: true,
                    content: ownObj.attr('data-url')+'?id='+ownObj.attr('data-id')
                });
            });

            $(document).on('click', '.confirm', function () {
                var ownObj = $(this);
                layer.confirm(ownObj.attr('data-info'), {
                    title: ownObj.attr('data-title'),
                    btn: ['确定','取消'] //按钮
                }, function(){
                    $.ajax({
                        type: "POST",
                        url: ownObj.attr('data-url'),
                        data: {id:ownObj.attr('data-id')},
                        success: function(msg){
                            if( msg.code == 1 ){
                                location.reload();
                            }else{
                                layer.msg(msg.msg, {
                                    icon: 5,
                                    shade: [0.6, '#393D49'],
                                    time:1500
                                });
                            }
                        }
                    });
                });
            });

        })
    </script>
</block>