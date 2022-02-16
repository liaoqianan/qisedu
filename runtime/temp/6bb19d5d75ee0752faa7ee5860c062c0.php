<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:67:"/www/wwwroot/qisedu/public/../application/admin/view/Log/index.html";i:1620814255;s:59:"/www/wwwroot/qisedu/application/admin/view/Public/base.html";i:1620814255;}*/ ?>
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
        <legend>操作日志</legend>
        <div class="layui-field-box">
            <form class="layui-form" id="form-admin-add" action="">
                <div class="layui-form-item">
                <div class="layui-inline">
                    <select name="type" class="type">
                        <option value="">请选择查询方式</option>
                        <option value="1">操作URL</option>
                        <?php if($is_admin == 1): ?><option value="2">用户昵称</option><?php endif; if($is_admin == 1): ?><option value="3">用户ID</option><?php endif; ?>
                    </select>
                </div>
                <div class="layui-inline">
                    <div class="layui-input-inline" style="width: 300px;">
                        <input type="text" name="keyword" maxlength="12" placeholder="请输入关键词" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="layui-input-inline" style="width: 300px;">
                        <input type="text" class="layui-input"  name="add_time" placeholder="请选择时间"  id="create_time" >
                    </div>
                </div>
                <div class="layui-inline">
                    <select name="error" class="type">
                        <option value="">操作状态</option>
                        <option value="0">正常</option>
                        <option value="1">非法访问</option>
                    </select>
                </div>
                <div class="layui-inline">
                    <span class="layui-btn sub">查询</span>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    <span class="layui-btn refresh">刷新</span>
                    <button type="button" class="layui-btn layui-btn-normal export" >Excel导出</button>
                </div>

            </div>
            </form>
            <table class="layui-table" id="list-admin" lay-even>
                <thead>
                <tr>
                   <!--  <td style="width: 1px;" class="text-center">
                        <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
                    </td> -->
                    <th>编号</th>
                    <th>用户ID</th>
                    <th>用户昵称</th>
                    <th>操作内容</th>
                    <th>操作URL</th>
                    <th>操作时间</th>
                    <th>状态</th>
                </tr>
                </thead>
            </table>
        </div>
    </fieldset>
</block>
<block name="myScript">
    <script>

        laydate.render({
            elem: '#create_time',
            range:true
        });
    	
      	$(".resetform").click(function(){
	    	$('#form-admin-add')[0].reset();
    	})
        //导出日志
        $(document).on('click', '.export', function () {
            window.location.href = '/Log/export?'+ $('#form-admin-add').serialize();
        });
        /**
         * 格式化时间戳
         * @param fmt
         * @returns {*}
         * @constructor
         */
        Date.prototype.Format = function (fmt) {
            var o = {
                "M+": this.getMonth() + 1, //月份
                "d+": this.getDate(), //日
                "h+": this.getHours(), //小时
                "m+": this.getMinutes(), //分
                "s+": this.getSeconds(), //秒
                "q+": Math.floor((this.getMonth() + 3) / 3), //季度
                "S": this.getMilliseconds() //毫秒
            };
            if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
            for (var k in o)
                if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
            return fmt;
        };


        layui.use(['layer', 'form'], function() {
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
                        type: 'GET',
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
                        // {
                        //     "targets":0,
                        //     "render": function(data){
                        //         return  '<td class="text-center"><input type="checkbox" name="selected[]" value="'+data.id+'"> </td>';
                        //     }
                        // },
                        {
                            "targets":5,
                            "render": function(data){
                                return new Date(data*1000).Format("yyyy-MM-dd hh:mm:ss");
                            }
                        },
                        
                    ],
                    iDisplayLength : 20,
                    aLengthMenu : [20, 30, 50],
                    columns: [
                        // {"data": null },
                        {"data": "id"},
                        {"data": "uid"},
                        {"data": "nickname" },
                        {"data": "actionName"},
                        {"data": "url" },
                        {"data": "addTime" },
                        {"data": "error" },
                    ]
                });
            };
            var myTable = myFun();
            $('.refresh').on('click',function(){
                myTable.destroy();
                myTable = myFun();
            })
            $('.sub').on("click", function(){
                myTable.destroy();
                myTable = myFun('?'+ $('#form-admin-add').serialize());
            });
        });

    </script>
</block>