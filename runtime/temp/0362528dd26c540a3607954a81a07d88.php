<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:66:"D:\vhost\yundong\public/../application/admin\view\Index\index.html";i:1587437340;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?php echo config('APP_NAME'); ?>管理系统</title>
    <script src="/static/admin/js/jquery.min.js"></script>
    <link rel="stylesheet" href="/static/admin/plugins/layui/css/layui.css">
    <link rel="stylesheet" href="/static/admin/css/global.css">
    <style>
        .layui-nav-child .layui-nav-item {padding-left: 25px;}
        .layui-nav-child li:hover {background: #009688;}
        .layui-nav-child a:hover {color: #fff;}
        #top-admin .layui-nav-more {border-color: #fff transparent transparent}
        #top-admin .layui-nav-mored {border-color: transparent transparent #fff}
        .v-transfer-dom {height: 100%;}
        .switch {font-size: 14px;cursor: pointer;padding: 0 10px;line-height: 40px;color: #fff;border-radius: 4px;background-color: #1890ff;border-color: #1890ff;text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.12)}
        .switch:hover {color: #fff;background-color: #40a9ff;border-color: #40a9ff;}
        .clear-switch {position: fixed; right: 10px;border-bottom: 1px solid #e2e2e2;background-color: rgb(255, 255, 255);}
        .layui-tab-more {padding-right: 0 !important;}
    </style>
</head>

<body>
    <!-- 布局容器 -->
    <div class="layui-layout layui-layout-admin">
        <!-- 头部 -->
        <div class="layui-header">
            <div class="layui-main" id="admin-navbar-side" lay-filter="side">
                <!-- logo -->
                <a href="" style="color: #fff; font-size: 18px; line-height: 60px;"><?php echo config('APP_NAME'); ?>管理系统</a>
                <!-- 水平导航 -->
                <ul class="layui-nav" lay-filter="top-nav" style="position: absolute; top: 0; right: 0; background: none;">
                    <li class="layui-nav-item" id="top-admin">
                        <a href="javascript:;" style="color: #fff;">
                            <i class="layui-icon">&#xe612;</i> <?php echo $username; if($nickname): ?>[<?php echo $nickname; ?>]<?php endif; ?> <?php echo $auth_name; ?>
                        </a>
                        <dl class="layui-nav-child">
                            <dd class="api-add">
                                <a href="javascript:;">
                                个人信息
                            </a>
                            </dd>
                            <dd>
                                <a href="<?php echo url('Login/logOut'); ?>">
                                退出登录
                            </a>
                            </dd>
                        </dl>
                    </li>
                </ul>
            </div>
        </div>

        <!-- 侧边栏 -->
        <div class="layui-side layui-bg-black">
            <div class="layui-side-scroll">
                <ul class="layui-nav layui-nav-tree" lay-filter="left-nav" style="border-radius: 0;">
                </ul>
            </div>
        </div>

        <!-- 主体 -->
        <div class="layui-body">
            <!-- 顶部切换卡 -->
            <div class="layui-tab layui-tab-brief" lay-filter="top-tab" lay-allowClose="true" style="margin: 0;">
                <ul class="layui-tab-title" id="LAY_app_tabsheader" style="position: fixed;top: 60px;margin-left:200px;background-color: #fff;">
                </ul>
                <div class="clear-switch">
                    <p class='switch'>关闭全部导航</p>
                </div>
                <div class="layui-tab-content" style="padding-top: 44px;">
                    
                </div>
            </div>
        </div>

        <!-- 底部 -->
        <div class="layui-footer" style="text-align: center; line-height: 44px;">
            <strong>Copyright &copy; 2014-<?php echo date('Y'); ?> <a href=""><?php echo config('COMPANY_NAME'); ?></a>.</strong> All rights reserved.
        </div>
    </div>
    <div class="site-tree-mobile layui-hide">
        <i class="layui-icon"></i>
    
    </div>
    <div class="site-mobile-shade"></div>
    <script type="text/javascript" src="/static/admin/plugins/layui/layui.js"></script>
    <script type="text/javascript">
        layui.config({
            base: '/static/admin/js/'
        });
        $('#LAY_app_tabsheader').css("width", "" + $('.layui-tab-brief').width() - $('.switch').outerWidth(true) - 10 + "px");
        $('.clear-switch').css({
            'width': '' + $('.switch').outerWidth(true) + 'px',
            'height': '' + $('#LAY_app_tabsheader').outerHeight(true) - 1 + 'px'
        });
        layui.use(['cms'], function() {
            var cms = layui.cms('left-nav', 'top-tab', 'top-nav');
            cms.addNav(JSON.parse('<?php echo json_encode($list); ?>'), 0, 'id', 'fid', 'name', 'url');
            cms.bind(60 + 41 + 20 + 44); //头部高度 + 顶部切换卡标题高度 + 顶部切换卡内容padding + 底部高度
            cms.clickLI(0);
        });
        layui.use(['layer'], function() {
            $('.api-add').on('click', function() {
                layer.open({
                    type: 2,
                    offset:'35px',
                    area: ['80%', '80%'],
                    title: '个人信息',
                    maxmin: true,
                    content: '<?php echo url("Login/changeUser"); ?>'
                });
            });
            var updateTime = '<?php echo $userInfo["updateTime"]; ?>';
            if (updateTime == 0) {
                layer.open({
                    offset:'35px',
                    title: '初次登陆请重置密码！',
                    type: 2,
                    area: ['80%', '80%'],
                    maxmin: true,
                    closeBtn: 0,
                    content: '<?php echo url("Login/changeUser"); ?>'
                });
            } else {
                var nickname = '<?php echo $userInfo["nickname"]; ?>';
                if (!nickname) {
                    layer.open({
                        title: '初次登陆请补充真实姓名！',
                        type: 2,
                        offset:'35px',
                        area: ['80%', '80%'],
                        maxmin: true,
                        closeBtn: 0,
                        content: '<?php echo url("Login/changeUser"); ?>'
                    });
                }
            }
        });
    
        //处理键盘事件 禁止后退键（Backspace）密码或单行、多行文本框除外
        function banBackSpace(e) {
            var ev = e || window.event; //获取event对象 
            var obj = ev.target || ev.srcElement; //获取事件源 
            var t = obj.type || obj.getAttribute('type'); //获取事件源类型 
            //获取作为判断条件的事件类型
            var vReadOnly = obj.getAttribute('readonly');
            //处理null值情况
            vReadOnly = (vReadOnly == "") ? false : vReadOnly;
            //当敲Backspace键时，事件源类型为密码或单行、多行文本的，
            //并且readonly属性为true或enabled属性为false的，则退格键失效
            var flag1 = (ev.keyCode == 8 && (t == "password" || t == "text" || t == "textarea") &&
                vReadOnly == "readonly") ? true : false;
            //当敲Backspace键时，事件源类型非密码或单行、多行文本的，则退格键失效
            var flag2 = (ev.keyCode == 8 && t != "password" && t != "text" && t != "textarea") ?
                true : false;

            //判断
            if (flag2) {
                return false;
            }
            if (flag1) {
                return false;
            }
        }

        window.onload = function() {
            //禁止后退键 作用于Firefox、Opera
            document.onkeypress = banBackSpace;
            //禁止后退键 作用于IE、Chrome
            document.onkeydown = banBackSpace;
        }
    </script>
</body>

</html>