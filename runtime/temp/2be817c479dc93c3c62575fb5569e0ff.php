<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:73:"/www/wwwroot/peigongtang/public/../application/admin/view/User/index.html";i:1611131081;s:64:"/www/wwwroot/peigongtang/application/admin/view/Public/base.html";i:1610075700;}*/ ?>
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
        <legend>用户列表</legend>
        <div class="layui-field-box">
            <form class="layui-form" id="form-admin-add" action="outputExcel">   
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <input type="text" name="nickname" value="" maxlength="50" placeholder="请填写用户昵称\手机号" class="layui-input" onkeyup='clearSymbol(this)'>
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <input type="text" name="uid" value="" maxlength="50" placeholder="请填写用户ID" class="layui-input" onkeyup='clearSymbol(this)'>
                    </div>
                </div>

                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <input type="text" name="refer_name" value="" maxlength="50" placeholder="请填写邀请人" class="layui-input" onkeyup='clearSymbol(this)'>
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <input type="text" style="width:300px" readonly id="create_time" name="create_time" value="" placeholder="请填写注册时间" class="layui-input" onkeyup='clearSymbol(this)'>
                    </div>
                </div>
                <div class="layui-inline">
                    <span class="layui-btn sub">查询</span>
                    <span class="layui-btn resetform layui-btn-primary">重置</span>
                    <span class="layui-btn refresh">刷新</span>
                    <button type="button" class="layui-btn layui-btn-normal export" >Excel导出</button>
                </div>
            </form>

            <table class="layui-table" id="list-admin" lay-even>
                <thead>
                <tr>
                    <th>
                        <input class="layui-form-checkbox" type="checkbox" lay-skin="primary" lay-filter="allcheck" onclick="$('input[name*=\'id\']').prop('checked', this.checked);">
                    </th>
                    <th>编号</th>
                    <th>头像</th>
                    <th>姓名</th>
					<th>手机号码</th>
                    <th>推荐人</th>
                    <th>首次访问日期</th>
                    <th>最近访问日期</th>
                    <th>是否关注</th>
                    <th>关注或取关时间</th>
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

        //导出列表
        $(document).on('click', '.export', function () {
            window.location.href = '<?php echo url("User/ajaxGetIndex"); ?>'+'?excel=1&'+ $('#form-admin-add').serialize();
        });

		/**
		 * 
		 * @param {Object} obj
		 * 
		 */
        function clearSymbol(obj) {
            obj.value = obj.value.replace(/[%]/g,""); //清除"%"特殊字符
        }
		
        layui.use(['layer', 'form'], function() {
            $(document).on('click', '.confirm', function () {
                var ownObj = $(this);
                layer.confirm(ownObj.attr('data-info'), {
					title: ownObj.attr('data-title'),
                    btn: ['确定','取消'] //按钮
                }, function(index){
                    $.ajax({
                        type: "POST",
                        url: ownObj.attr('data-url'),
                        data: {id:ownObj.attr('data-id')},
                        success: function(msg){
                            if( msg.code == 1 ){
                                layer.close(index);
                                queryClick(true);
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

            $(document).on('click', '.edit', function () {
                var ownObj = $(this);
                layer.open({
                    type: 2,
                    offset:'35px',
                    area: ['80%', '66%'],
                    title: ownObj.attr('data-title'),
                    maxmin: true,
                    content: ownObj.attr('data-url')+'?uid='+ownObj.attr('data-id'),
                    end:function () {
                        queryClick(true);
                    }
                });
            });
            $(document).on('click', '.see', function () {
                var ownObj = $(this);
                layer.open({
                    type: 2,
                    area: ['80%', '90%'],
                    offset:'35px',
                    title: ownObj.attr('data-title'),
                    maxmin: true,
                    content: ownObj.attr('data-url')+'?uid='+ownObj.attr('data-id')
                });
            });
            $(document).on('click', '.get_v3invest', function () {
                var ownObj = $(this);
                layer.open({
                    type: 2,
                    offset:'35px',
                    area: ['80%', '66%'],
                    title: ownObj.attr('data-title'),
                    maxmin: true,
                    content: ownObj.attr('data-url')+'?uid='+ownObj.attr('data-id'),
                    end:function () {
                        queryClick(true);
                    }
                });
            });
            $(document).on('click', '.get_v3service', function () {
                var ownObj = $(this);
                layer.open({
                    type: 2,
                    area: ['80%', '66%'],
                    title: ownObj.attr('data-title'),
                    maxmin: true,
                    content: ownObj.attr('data-url')+'?uid='+ownObj.attr('data-id'),
                    end:function () {
                        queryClick(true);
                    }
                });
            });
            $(document).on('click', '.get_v3member', function () {
                var ownObj = $(this);
                layer.open({
                    type: 2,
                    area: ['80%', '66%'],
                    offset:'35px',
                    title: ownObj.attr('data-title'),
                    maxmin: true,
                    content: ownObj.attr('data-url')+'?uid='+ownObj.attr('data-id'),
                    end:function () {
                        queryClick(true);
                    }
                });
            });
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
                        type: 'GET',
                        dataSrc: function ( json ) {
                            if( json.code == 0 ){
                                parent.layer.msg(json.msg, {
                                    icon: 5,
                                    shade: [0.6, '#393D49'],
                                    time:1500
                                });
                            }else{
                                $('#total .cardBodyP').html(json.recordsTotal);
                                return json.data;
                            }
                        }
                    },
                    columnDefs:[
                        {
                            "targets":0,
                            "render":function(data, type, row) {
                                var returnStr = '';
                                returnStr = '<input type="checkbox" name="id[]" lay-skin="primary" value="' + row.id+ '">';
                                return returnStr;
                            }
                        },
                        {
                            "targets":2,
                            "render":function(data, type, row) {
                                var returnStr = '';
                                returnStr = '<img class="goodsImg" src="'+ row.headimg +'" height="100px">';
                                return returnStr;
                            }
                        }
                    ],
                    iDisplayStart:start,
                    iDisplayLength : 20,
                    aLengthMenu : [20, 30, 50, 100],
                    columns: [
                        {"data": null},
                        {"data": "id"},
                        {"data": null},
                        {"data": "nickname"},
                        {"data": "mobile"},
						{"data": "refer_name"},
						{"data": "add_time"},
                        {"data": "last_time"},
                        {"data": "is_sub"},
                        {"data": "sub_time"},
                    ]
                });
            };
            var myTable = myFun();
            $('.refresh').on('click',function(){
                myTable.destroy();
                myTable = myFun();
            })
            $('tbody').on("click",function(e){
                if($(e.target).attr('data-hide') == 0){
                    $(e.target).parent().children('ul').css('display','block')
                    $(e.target).attr('data-hide',1)
                }else if($(e.target).parent().attr('data-hide') == 0){
                    $(e.target).parent().parent().children('ul').css('display','block')
                    $(e.target).parent().attr('data-hide',1)
                }else if($(e.target).attr('data-hide') == 1){
                    $(e.target).parent().children('ul').css('display','none')
                    $(e.target).attr('data-hide',0)
                }else if($(e.target).parent().attr('data-hide') == 1){
                   $(e.target).parent().parent().children('ul').css('display','none')
                   $(e.target).parent().attr('data-hide',0)
                }else{
                    $('ul').parent().children('ul').css('display','none')
                    $('.Hide').attr('data-hide',0)
                }
            })
            $('.sub').on("click", function(){
                queryClick();
            });
            $('tbody').on('click','.goodsImg',function(){
                var imgSrc=$(this).attr('src')
                layer.open({
                    type:1,
                    title:false,
                    closeBtn:0,
                    shadeClose:true,
                    content:"<img alt=name title=name src=" + imgSrc + " height=300px; width=300px;" + "/>",
                    scrollbar:false,
                    id:'imgSrc',
                })
            })
            function queryClick(jump = false){

                var start = 0;
                if (jump){
                    //当前表格页面信息
                    var page = myTable.page.info().page;
                    var count = myTable.page.len();

                    start = page*count;
                }

                myTable.destroy();
                myTable = myFun('?' + $('#form-admin-add').serialize(),start);
            }
        });
    </script>
</block>