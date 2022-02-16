<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:75:"/www/wwwroot/qisedu/public/../application/admin/view/Course_node/index.html";i:1620814255;s:59:"/www/wwwroot/qisedu/application/admin/view/Public/base.html";i:1620814255;}*/ ?>
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
        <legend>课节列表</legend>
        <div class="layui-field-box">
            <form class="layui-form" id="form-admin-add" action="">
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <input type="text" name="title" value="" placeholder="请填写课节标题" maxlength="12" class="layui-input" onkeyup='clearSymbol(this)'>
                    </div>
                </div>
                <div class="layui-input-inline">
                    <select name="course_id">
                        <option value=" ">课程</option>
                        <?php if(is_array($course) || $course instanceof \think\Collection || $course instanceof \think\Paginator): if( count($course)==0 ) : echo "" ;else: foreach($course as $key=>$vo): ?>
                        <option value="<?php echo $vo['id']; ?>"><?php echo $vo['title']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
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
                    <th>课节标题</th>
                    <th>开课时间</th>
                    <th>直播</th>
                    <th>课程标题</th>
                    <th>服务端地址</th>
                    <th>网页地址</th>
                    <th>OBS推流地址</th>
                    <th>密码</th>
                    <th>通知</th>
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
                    content: '<?php echo url("Course_node_add"); ?>'
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
                    url:'<?php echo url("ajaxGetCourse_node"); ?>' + query,
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
                        "targets": 3,
                        "render": function (data, type, row) {
                            if (row.type == 1){
                                return '直播';
                            }else if(row.type == 2){
                                return '回放';
                            }else if(row.type == 3){
                                return '视频';
                            }else if(row.type == 4){
                                return '音频';
                            }else{
                                return '图文';
                            }
                        }
                    },
                    {
                        "targets":-2,
                        "render":function(data, type, row) {
                            return '<span class="layui-btn layui-btn-small confirm layui-btn-danger"  data-id="' + row.id +'" data-title="通知" data-info="你确定要发送通知吗" data-url="<?php echo url('notice'); ?>" confirm><i class="layui-icon">&#xe640;</i></span>';
                        }
                    },
                    {
                        "targets":-1,
                        "render":function(data, type, row) {
                            var returnStr = '';
                            returnStr += '<span class="layui-btn layui-btn-small edit layui-btn-normal" data-id="' + row.id +'" data-title="编辑" data-url="<?php echo url('Course_node_edit'); ?>"><i class="layui-icon">&#xe642;</i></span>';
                            returnStr += '<span class="layui-btn layui-btn-small confirm layui-btn-danger"  data-id="' + row.id +'" data-title="删除" data-info="你确定要删除吗" data-url="<?php echo url('Course_node_del'); ?>" confirm><i class="layui-icon">&#xe640;</i></span>';
                            return returnStr;
                        }
                    }
                ],
                iDisplayLength : 20,
                aLengthMenu : [20, 30, 50],
                columns: [
                    {"data": "id"},
                    {"data": "title" },
                    {"data": "broadcast_time" },
                    {"data": "type" },
                    {"data": "course_title" },
                    {"data": "client" },
                    {"data": "web" },
                    {"data": "url" },
                    {"data": "channelPasswd" },
                    {"data": null },
                    {"data": null }
                ],

            });
            $('.notice').click(function () {
                alert(1)
            });
        };
        var myTable = myFun();
        $('.sub').on("click", function(){
            myTable.destroy();
            myTable = myFun('?'+ $('#form-admin-add').serialize());
        });
    </script>
</block>