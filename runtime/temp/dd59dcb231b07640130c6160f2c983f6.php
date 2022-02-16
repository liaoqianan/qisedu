<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:79:"/www/wwwroot/peigongtang/public/../application/admin/view/Activity/details.html";i:1611717548;s:64:"/www/wwwroot/peigongtang/application/admin/view/Public/base.html";i:1610075700;}*/ ?>
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
        <legend>活动列表</legend>
        <div class="layui-field-box">
            <form class="layui-form" id="form-admin-add" action="">
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <input type="text" name="name" value="" placeholder="请填写报名人姓名或手机号码" maxlength="20" class="layui-input" onkeyup='clearSymbol(this)'>
                    </div>
                </div>
                <div class="layui-inline">
                    <span class="layui-btn sub">查询</span>
                    <span class="layui-btn resetform layui-btn-primary">重置</span>
                    <span class="layui-btn refresh">刷新</span>
                </div>
            </form>
            <table class="layui-table" id="list-admin" lay-even>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>姓名</th>
                    <th>手机号码</th>
                    <th>年龄</th>
                    <th>性别</th>
                    <th>留言</th>
                    <th>报名时间</th>
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
                    url:'<?php echo url("ajaxGetdetails?id=$id"); ?>' + query,
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
                        "targets":-3,
                        "render":function(data, type, row) {
                            if (row.sex==1){
                                return '男';
                            }else {
                                return '女';
                            }
                        }
                    }
                ],
                iDisplayLength : 20,
                aLengthMenu : [20, 30, 50],
                columns: [
                    {"data": "id"},
                    {"data": "name" },
                    {"data": "mobile" },
                    {"data": "age" },
                    {"data": "sex" },
                    {"data": "comment" },
                    {"data": "partake_time" },
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