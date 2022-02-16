<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:77:"/www/wwwroot/peigongtang/public/../application/admin/view/Question/index.html";i:1617007764;s:64:"/www/wwwroot/peigongtang/application/admin/view/Public/base.html";i:1610075700;}*/ ?>
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
    <script type="text/javascript" src="/static/admin/plugins/dataTable/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="/static/admin/plugins/dataTable/dataTable.css">
    <link href="/static/admin/plugins/bootstrap/css/edit_bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/static/admin/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <script src="/static/admin/plugins/daterangepicker/moment.min.js" type="text/javascript"></script>
    <script src="/static/admin/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
    <fieldset class="layui-elem-field">
        <legend>问答列表</legend>
        <div class="layui-field-box">
            <form class="layui-form" id="form-admin-add" action="">
                <div class="layui-inline">
                    <span class="layui-btn sub" data-q_type="1">必答题</span>
                    <span class="layui-btn sub" data-q_type="2">选答题</span>
                    <span class="layui-btn sub" data-q_type="3">挑战题</span>
                </div>
                <div class="layui-inline" style="float: right;">
                    <span class="layui-btn layui-btn-normal api-add" data-title="新增"><i class="layui-icon">&#xe608;</i> 新增</span>
                </div>
            </form>
            <table class="layui-table" id="list-admin" lay-even>
                <thead>
                <tr>
                    <th>题号</th>
                    <th>难度</th>
                    <th>题型</th>
                    <th class="title">标题</th>
                    <th>添加时间</th>
                    <th style="width: 120px">操作</th>
                </tr>
                </thead>
            </table>
        </div>
    </fieldset>
</block>
<style>
/*    .asd p{
        width: 500px;display: inline-block;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;
    }
    .even:nth-child(3){
        width: 50px;
    }
    .odd:nth-child(3){
        width: 50%;
    }*/
</style>
<block name="myScript">
    <script>
        $(".resetform").click(function(){
            $('#form-admin-add')[0].reset();
        })
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
            })
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
        var myFun = function (query,start = 0) {
            query = query || '';
            return $('#list-admin').DataTable({
                dom: 'rt<"bottom"ifpl><"clear">',
                ordering: false,
                autoWidth: false,
                searching:false,
                serverSide: true,
                ajax: {
                    url:'<?php echo url("ajaxGetIndex"); ?>' + query,
                    type: 'POST',
                    dataSrc: function ( json ) {
                        if( json.code == 0 ){
                            parent.layer.msg(json.msg, {
                                icon: 5,
                                shade: [0.6, '#393D49'],
                                time:1500
                            });
                        }else{
                            return json.data;
                        }
                    }
                },
                columnDefs:[
                    {
                        "targets":-5,
                        "render":function(data, type, row) {
                            $html = '';
                            if (row.type==1){
                                $html = '难度一';
                            } else if(row.type==2){
                                $html = '难度二';
                            }else{
                                $html = '难度三';
                            }
                            return $html;

                        }
                    },
                    {
                        "targets":-4,
                        "render":function(data, type, row) {
                            $html = '';
                            if (row.q_type==1){
                                $html = '必选题';
                            } else if(row.q_type==2){
                                $html = '选答题';
                            }else{
                                $html = '挑战题';
                            }
                            return $html;

                        }
                    },
                    {
                        "targets":-3,
                        "render":function(data, type, row) {
                            return row.title;

                        }
                    },
                    {
                        "targets":-1,
                        "render":function(data, type, row) {
                            var returnStr = '';
                            returnStr += '<span class="layui-btn layui-btn-small edit layui-btn-normal" data-id="' + row.id +'" data-title="编辑" data-url="<?php echo url('edit'); ?>"><i class="layui-icon">&#xe642;</i></span>';
                            returnStr += '<span class="layui-btn layui-btn-small confirm layui-btn-danger"  data-id="' + row.id +'" data-title="删除" data-info="你确定要删除吗" data-url="<?php echo url('del'); ?>" confirm><i class="layui-icon">&#xe640;</i></span>';
                            return returnStr;
                        }
                    }
                ],
                iDisplayStart:start,
                iDisplayLength : 20,
                aLengthMenu : [20, 30, 50],
                columns: [
                    {"data": "number" },
                    {"data": "type" },
                    {"data": "q_type" },
                    {"data": "title" },
                    {"data": "create_time" },
                    {"data": null }
                ],
            });
        };
        var myTable = myFun();
        $('.sub').on("click", function(){
            myTable.destroy();
            myTable = myFun('?'+ 'q_type='+$(this).data('q_type')+$('#form-admin-add').serialize());
        });
    </script>
</block>