<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:59:"D:\pgt\public/../application/admin\view\training\index.html";i:1608105768;s:46:"D:\pgt\application\admin\view\Public\base.html";i:1599547052;}*/ ?>
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
        <legend>课程列表</legend>
        <div class="layui-field-box">
            <form class="layui-form" id="form-admin-add" action="">
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <input type="text" name="title" value="" placeholder="请填写课程名称" maxlength="12" class="layui-input" onkeyup='clearSymbol(this)'>
                    </div>
                </div>
                <div class="layui-inline">
                    <span class="layui-btn sub">查询</span>
                    <span class="layui-btn resetform layui-btn-primary">重置</span>
                    <span class="layui-btn refresh">刷新</span>
                </div>
                <div class="layui-inline" style="float: right;">
                    <span class="layui-btn layui-btn-normal api-add" data-title="新增专辑"><i class="layui-icon">&#xe608;</i> 新增</span>
                </div>
            </form>
            <table class="layui-table" id="list-admin" lay-even>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>SDK_ID</th>
                    <th>直播主题</th>
                    <th>老师口令</th>
                    <th>助教口令</th>
                    <th>学员web口令</th>
                    <th>学员pc口令</th>
                    <th>开始时间</th>
                    <th>失效时间</th>
                    <th>老师链接</th>
                    <th>学员链接</th>
                    <th>类型</th>
                    <th>操作</th>
                </tr>
                </thead>
            </table>
        </div>
    </fieldset>
</block>
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
        var myFun = function (query) {
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
                        //   指定第四列，从0开始，0表示第一列，1表示第二列……
                        "targets": -2,
                        "render": function (data, type, row) {
                            if (row.scene == 0){
                                return '大讲堂';
                            }else if(row.scene == 1){
                                return '小课堂';
                            }
                        }
                    },
                    {
                        "targets":-1,
                        "render":function(data, type, row) {
                            var returnStr = '';
                            returnStr += '<span class="layui-btn layui-btn-small edit layui-btn-normal" data-id="' + row.id +'" data-title="编辑" data-url="<?php echo url('edit'); ?>"><i class="layui-icon">&#xe642;</i></span>';
                            returnStr += '<span class="layui-btn layui-btn-small confirm layui-btn-danger"  data-id="' + row.sdk_id +'" data-title="删除" data-info="你确定要删除吗" data-url="<?php echo url('del'); ?>" confirm><i class="layui-icon">&#xe640;</i></span>';
                            return returnStr;
                        }
                    }
                ],
                iDisplayLength : 20,
                aLengthMenu : [20, 30, 50],
                columns: [
                    {"data": "id"},
                    {"data": "sdk_id"},
                    {"data": "subject" },
                    {"data": "teacherToken" },
                    {"data": "assistantToken" },
                    {"data": "studentToken" },
                    {"data": "studentClientToken" },
                    {"data": "startDate" },
                    {"data": "invalidDate" },
                    {"data": "teacherJoinUrl" },
                    {"data": "studentJoinUrl" },
                    {"data": "scene" },
                    {"data": null }
                ],
            });
        };
        var myTable = myFun();
        $('.sub').on("click", function(){
            myTable.destroy();
            myTable = myFun('?'+ $('#form-admin-add').serialize());
        });
    </script>
</block>