<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:69:"E:\phpEnv\www\qisedu\public/../application/admin\view\Menu\index.html";i:1620814255;s:60:"E:\phpEnv\www\qisedu\application\admin\view\Public\base.html";i:1620814255;}*/ ?>
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
        <legend>菜单维护</legend>
        <div class="layui-field-box">
            <span class="layui-btn layui-btn-normal api-add" data-title="新增菜单"><i class="layui-icon">&#xe608;</i>
                新增</span>
            <table class="layui-table" lay-even>
                <thead>
                    <tr>
                        <th width="100">编号</th>
                        <th  width="250">菜单名称</th>
                        <th  width="100">排序</th>
                        <th  width="300">菜单URL</th>
                        <th  width="100">隐藏</th>
                        <th  >操作</th>
                    </tr>
                </thead>
                <tbody id="menu-list">

                </tbody>
            </table>
        </div>
    </fieldset>
</block>
<block name="myScript">
    <script>
        layui.use(['layer'], function () {
            var menulist = <?php echo json_encode($list);?>;
            function menuOpen(Pid) {
                var List;
                var ownObj;
                var newMenuList = [];
                for (var i = 0; i < menulist.length; i++) {
                    if (menulist[i].fid == Pid) {
                        newMenuList.push(menulist[i])
                    }
                }
                for (var j = 0; j < newMenuList.length; j++) {
                    var n = j;
                    List += `<tr data-fid="${newMenuList[j].idpath}" data-id="${Pid}" data-tent="${newMenuList[j].namePrefix}"> <td>${++n}</td><td>${newMenuList[j].showName}</td> <td>${newMenuList[j].sort}</td> <td>${newMenuList[j].url}</td><td> <span  style="color: #ff4d4f;font-size:16px;font-weight: bold;display:${newMenuList[j].hide == 1 ? 'inline-block' : 'none'}">是</span><span  style="font-size:16px;font-weight: bold;display:${newMenuList[j].hide == 1 ? 'none' : 'inline-block'}"> 否</span></td><td><span class="layui-btn layui-btn-small deployMent" style="margin-left:0;" data-id="${newMenuList[j].id}" data-info="你确定打开下一级菜单么？">展开菜单</span><span style="display:${newMenuList[j].hide == 1 ? 'inline-block' : 'none'}" class="layui-btn layui-btn-small confirm" data-title="显示菜单" data-info="你确定显示当前菜单么？" data-id="${newMenuList[j].id}" data-url="<?php echo url('open'); ?>"> 显示</span><span style="display:${newMenuList[j].hide == 1 ? 'none' : 'inline-block'};" class="layui-btn layui-btn-small layui-btn-danger confirm" data-title="隐藏菜单" data-info="你确定隐藏当前菜单么？"  data-id="${newMenuList[j].id}" data-url="<?php echo url('close'); ?>"> 隐藏</span><span data-url="<?php echo url('edit'); ?>" data-id="${newMenuList[j].id}" data-title="编辑菜单" class="layui-btn layui-btn-small edit layui-icon layui-btn-normal">&#xe642;</span> <span class="layui-btn layui-btn-small layui-icon layui-btn-danger confirm" data-id="${newMenuList[j].id}" data-info="你确定删除当前菜单么？"  data-url="<?php echo url('del'); ?>" >&#xe640;</span></td></tr>`
                }
                return List
            }
            var menu = menuOpen(0);
            var menuMun = [];
            var onoff = true;
            $('#menu-list').append(menu);
            $('#menu-list').click(function (event) {
                if (event.target.localName == 'span') {
                    //表格所有数据
                    var allMenus;
                    if (event.target.innerText == '展开菜单') {
                        //获取当前点击的id
                        menuMun.push($(event.target).attr('data-id'))
                        //生成表格
                        menu = menuOpen($(event.target).attr('data-id'));
                        $(event.target).parent().parent().after(menu);
                        $(event.target).text('收起菜单')
                        $(event.target).css('background-color', '#FF5722')
                        //表格所有数据
                        allMenus = $(event.target).parent().parent().parent().children();
                        for (var n = 0; n < allMenus.length; n++) {
                            //判断表格是级别
                            if (allMenus[n].getAttribute('data-tent') == '|---') {
                                allMenus[n].style.backgroundColor = '#e6f7ff'
                            } else if (allMenus[n].getAttribute('data-tent') == '|---|---') {
                                allMenus[n].style.backgroundColor = '#aee1f9'
                            }
                        }
                    } else if (event.target.innerText == '收起菜单') {
                        //表格所有数据
                        allMenus = $(event.target).parent().parent().parent().children();
                        var regText;
                        var noneMenu = [];
                        for (var n = 0; n < allMenus.length; n++) {
                            //当前点击表格下级清除
                            if (allMenus[n].getAttribute('data-id') == $(event.target).attr('data-id')) {
                                allMenus[n].parentNode.removeChild(allMenus[n]);
                                var regExText = allMenus[n].getAttribute('data-id')
                            }
                            //匹配点击表格的id
                            var regEx = new RegExp('' + regExText)
                            if (regEx.test(allMenus[n].getAttribute('data-fid')) && allMenus[n].getAttribute('data-tent') == '|---|---') {
                                regText = allMenus[n].getAttribute('data-id')
                            }
                            //匹配点击表格的下级的下级
                            var regular = new RegExp('' + regText)
                            if (regular.test(allMenus[n].getAttribute('data-fid'))) {
                                noneMenu.push(allMenus[n])
                            }
                        }
                        if ($(event.target).parent().parent().attr('data-tent') == '') {
                                for (var e = 0; e < noneMenu.length; e++) {
                                    noneMenu[e].parentNode.removeChild(noneMenu[e]);
                                }
                        }
                        $(event.target).text('展开菜单')
                        $(event.target).css('background-color', '#009688')
                    }
                }
            })
                $('.api-add').on('click', function () {
                    ownObj = $('.edit');
                    layer.open({
                        type: 2,
                        offset:'35px',
                        title: ownObj.attr('data-title'),
                        area: ['80%', '80%'],
                        maxmin: true,
                        content: '<?php echo url("add"); ?>'
                    });
                });
                $('#menu-list').on('click', function (e) {
                    if (event.target.className == 'layui-btn layui-btn-small edit layui-icon layui-btn-normal') {
                        ownObj = $(event.target)
                        layer.open({
                            type: 2,
                            offset:'35px',
                            title: ownObj.attr('data-title'),
                            area: ['80%', '80%'],
                            maxmin: true,
                            content: ownObj.attr('data-url') + '?id=' + ownObj.attr('data-id')
                        });
                    } else if (event.target.className == 'layui-btn layui-btn-small layui-btn-danger confirm' || event.target.className == 'layui-btn layui-btn-small layui-icon layui-btn-danger confirm' || event.target.className == 'layui-btn layui-btn-small confirm') {
                        ownObj = $(event.target)
                        layer.confirm(ownObj.attr('data-info'), {
                            title: ownObj.attr('data-title'),
                            btn: ['确定', '取消'] //按钮
                        }, function () {
                            $.ajax({
                                type: "POST",
                                url: ownObj.attr('data-url'),
                                data: { id: ownObj.attr('data-id') },
                                success: function (msg) {
                                    if (msg.code == 1) {
                                        location.reload();
                                    } else {
                                        layer.msg(msg.msg, {
                                            icon: 5,
                                            shade: [0.6, '#393D49'],
                                            time: 1500
                                        });
                                    }
                                }
                            });
                        });
                    }
                });
        });
    </script>
</block>