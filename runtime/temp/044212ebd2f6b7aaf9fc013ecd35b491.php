<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:71:"/www/wwwroot/qisedu/public/../application/admin/view/Payment/index.html";i:1620814254;s:59:"/www/wwwroot/qisedu/application/admin/view/Public/base.html";i:1620814255;}*/ ?>
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
        <legend>支付记录</legend>
        <div class="layui-field-box">
            <form class="layui-form" id="form-admin-add" action="">
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <input type="text" name="name" value="" placeholder="用户昵称或ID或手机号码" maxlength="12" class="layui-input" onkeyup='clearSymbol(this)'>
                    </div>
                </div>
                <div class="layui-inline">
                    <span class="layui-btn sub">查询</span>
                    <span class="layui-btn resetform layui-btn-primary">重置</span>
                    <span class="layui-btn refresh">刷新</span>
                </div>
                <div class="layui-inline" style="float: right;">
                    <span class="layui-btn layui-btn-normal api-add" data-title="开通"><i class="layui-icon">&#xe608;</i> 开通</span>
                </div>
            </form>
            <table class="layui-table" id="list-admin" lay-even>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>用户名称</th>
                    <th>手机号</th>
                    <th>头像</th>
                    <th>类型</th>
                    <th>标题</th>
                    <th>支付金额</th>
                    <th>支付方式</th>
                    <th>支付时间</th>
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
                        "targets":3,
                        "render":function(data, type, row) {
                            var returnStr = '';
                            returnStr = '<img src="'+row.headimg+'" height="50">';
                            return returnStr;
                        }
                    },
                    {
                        "targets":4,
                        "render":function(data, type, row) {
                            if (row.type == 1){
                                return "直播";
                            }else if(row.type == 2){
                                return "点播";
                            }else{
                                return "积分";
                            }
                        }
                    },
                    {
                        "targets":7,
                        "render":function(data, type, row) {
                            if (row.pay_type == 1){
                                return "小程序";
                            }else if(row.pay_type == 2){
                                return "支付宝";
                            }else if(row.pay_type == 3){
                                return "app";
                            }else if(row.pay_type == 4){
                                return "H5";
                            }else{
                                return "后台开通";
                            }
                        }
                    }
                ],
                iDisplayLength : 20,
                aLengthMenu : [20, 30, 50],
                columns: [
                    {"data": "id"},
                    {"data": "nickname" },
                    {"data": "mobile" },
                    {"data": null},
                    {"data": "type"},
                    {"data": "title"},
                    {"data": "money" },
                    {"data": "pay_type" },
                    {"data": "add_time" },
                ],
                fnDrawCallback: function (table) {
                    var audio = document.getElementsByTagName("audio");
                    // 暂停函数
                    function pauseAll() {
                        var self = this;
                        [].forEach.call(audio, function (i) {
                            // 将audios中其他的audio全部暂停
                            i !== self && i.pause();
                        })
                    }
                    // 给play事件绑定暂停函数
                    [].forEach.call(audio, function (i) {
                        i.addEventListener("play", pauseAll.bind(i));
                    })
                }

            });
        };
        var myTable = myFun();
        $('.sub').on("click", function(){
            myTable.destroy();
            myTable = myFun('?'+ $('#form-admin-add').serialize());
        });
    </script>
</block>