layui.define(['layer', 'element', 'forTree'], function(exports) {
    var layer = layui.layer;
    var element = layui.element();
    var forTree = layui.forTree;
    var $ = layui.jquery;

    var nav = null;
    var tab = null;
    var topNav = null;
    var tabContent = null;
    var tabTitle = null;
    var navFilter = null;
    var tabFilter = null;
    var topNavFilter = null;
    var swich = false;
    //手机设备的简单适配
  var treeMobile = $('.site-tree-mobile')
  ,shadeMobile = $('.site-mobile-shade')

  treeMobile.on('click', function(){
    $('body').addClass('site-mobile');
  });

  shadeMobile.on('click', function(){
    $('body').removeClass('site-mobile');
  });
    /**
     * 添加导航
     */
    function addNav(data, topId, idName, pidName, nodeName, urlName) {
        topId = topId || 0;
        idName = idName || 'id';
        pidName = pidName || 'pid';
        nodeName = nodeName || 'node';
        urlName = urlName || 'url';

        var myTree = new forTree(data, idName, pidName, topId);
        var html = '';

        myTree.forBefore = function(v, k, hasChildren) {
            html += '<li class="layui-nav-item">';
        };

        myTree.forCurr = function(v, k, hasChildren) {
            html += '<a href="javascript:;"' + (v[urlName] ? ' data-url="' + v[urlName] + '" data-id="' + v[idName] + '"' : '') + '>';
            html += v[nodeName];
            html += '</a>';
        };

        myTree.callBefore = function(v, k) {
            html += '<ul class="layui-nav-child">';
        };

        myTree.callAfter = function(v, k) {
            html += '</ul>';
        };

        myTree.forAfter = function(v, k, hasChildren) {
            html += '</li>';
        };

        myTree.each();

        nav.append(html);

        element.init('nav(' + navFilter + ')');
    }

    /**
     * 将侧边栏与顶部切换卡进行绑定
     */
    function bind(height) {

        height = height || 60 + 29 + 44; //头部高度 顶部切换卡标题高度 底部高度
        /**
         * iframe自适应
         */
        $(window).resize(function() {
            //设置顶部切换卡容器度
            tabContent.height($(this).height() - height);
            //设置顶部切换卡容器内每个iframe高度
            tabContent.find('iframe').each(function() {
                $(this).height(tabContent.height());
                $(this).width('100%');
                $(this).css('border', 0);
            });
        }).resize();
        
        
        /**
         * 监听侧边栏导航点击事件
         */
        element.on('nav(' + navFilter + ')', function(elem) {
            $('#LAY_app_tabsheader').css("width", "" + $('.layui-tab-brief').width() - $('.switch').outerWidth(true) + "px");
            $('.clear-switch').css({
                'width': '' + $('.switch').outerWidth(true) + 'px',
                'height': '' + $('#LAY_app_tabsheader').outerHeight(true) - 1 + 'px'
            });
            var a = elem.children('a');
            var title = a.text();
            var src = elem.children('a').attr('data-url');
            var id = elem.children('a').attr('data-id');
            var frame = tabContent.find('iframe[src="' + src + '"]').eq(0);
            var tabIndex = (new Date()).getTime();
            var box = $('.layui-tab-content');
            
            bind(height);
            //高度自适应
            /*var $content = $('.layui-tab-content');
            $content.height($(this).height() - 100);
            $content.find('iframe').each(function() {
                $(this).height($content.height());
            });*/

            if (src != undefined && src != null && id != undefined && id != null) {
                if (frame.length) { //存在 iframe
                    //获取iframe身上的tab index
                    tabIndex = frame.attr('data-tabindex');
                    if(swich){
                       //显示加载层
                    var tmpIndex = layer.load();
                    //设置1秒后再次关闭loading
                    swich =false;
                    setTimeout(function() {
                        layer.close(tmpIndex);
                    }, 1000);
                    //拼接iframe
                    var newFrame = '<iframe class="J_iframe" onload="layui.layer.close(' + tmpIndex + ')"';
                    newFrame += ' src="' + src + '" data-id="' + id + '" data-tabindex="' + tabIndex + '"';
                    newFrame += ' style="width: 100%; height: ' + tabContent.height() + 'px; border: 0px;"';
                    newFrame += '></iframe>';
                    //顶部切换卡新增一个卡片
                    var tabNav = tab.children('.layui-tab-title').children('li');
                    var onoff = true;
                    for (var i = 0; i < tabNav.length; i++) {
                        if (src == tabNav[i].getAttribute('lay-id')) {
                            onoff = false
                        }
                    }
                    if (onoff) {
                        element.tabAdd(tabFilter, { title: title,content:newFrame,id: src });
                    } 
                    }
                } else { //不存在 iframe
                    //显示加载层
                    var tmpIndex = layer.load();
                    //设置1秒后再次关闭loading
                    setTimeout(function() {
                        layer.close(tmpIndex);
                    }, 1000);
                    //拼接iframe
                    var newFrame = '<iframe class="J_iframe" onload="layui.layer.close(' + tmpIndex + ')"';
                    newFrame += ' src="' + src + '" data-id="' + id + '" data-tabindex="' + tabIndex + '"';
                    newFrame += ' style="width: 100%; height: ' + tabContent.height() + 'px; border: 0px;"';
                    newFrame += '></iframe>';
                    //顶部切换卡新增一个卡片
                    var tabNav = tab.children('.layui-tab-title').children('li');
                    var onoff = true;
                    for (var i = 0; i < tabNav.length; i++) {
                        if (src == tabNav[i].getAttribute('lay-id')) {
                            onoff = false
                        }
                    }
                    if (onoff) {
                        element.tabAdd(tabFilter, { title: title,content:newFrame,id: src });
                    }
                }
                $(".switch").unbind("click");
                $('.switch').click(function(e) {
                    var ALI = $(this).parent().parent().find('#LAY_app_tabsheader').find('li').eq(0)
                    $(this).parent().parent().find('#LAY_app_tabsheader').find('li').remove()
                    $(this).parent().parent().find('#LAY_app_tabsheader').append(ALI);
                    $(this).parent().parent().children('.layui-tab-content').find('div').remove();
                    swich = true;
                    var navSrc = ALI.attr('lay-id');
                    //显示加载层
                    var tmpIndex = layer.load();
                    //设置1秒后再次关闭loading
                    setTimeout(function() {
                        layer.close(tmpIndex);
                    }, 1000);
                    var newFrame = '<iframe class="J_iframe" onload="layui.layer.close(' + tmpIndex + ')"';
                    newFrame += ' src="' + navSrc + '" data-id="' + id + '" data-tabindex="' + tabIndex + '"';
                    newFrame += ' style="width: 100%; height: ' + tabContent.height() + 'px; border: 0px;"';
                    newFrame += '></iframe>';
                    box.append('<div class="layui-tab-item">'+newFrame+'</div>')
                    element.tabChange(tabFilter, navSrc);
                })
                $(".layui-tab-title").unbind("click");
                $('.layui-tab-title').on('click',function(e) {
                        if (e.target.parentNode.nodeName == 'SPAN') {
                            $('.clear-switch').css({
                                'width': '' + $('.switch').outerWidth(true) + 'px',
                                'height': '' + $('#LAY_app_tabsheader').outerHeight(true) - 1 + 'px'
                            });
                            return false;
                        }
                        if (e.target.nodeName == 'LI') {
                            var navSrc = e.target.getAttribute('lay-id');
                            //显示加载层
                            var tmpIndex = layer.load();
                            //设置1秒后再次关闭loading
                            setTimeout(function() {
                                layer.close(tmpIndex);
                            }, 1000);
                            var newFrame = '<iframe class="J_iframe" onload="layui.layer.close(' + tmpIndex + ')"';
                            newFrame += ' src="' + navSrc + '" data-id="' + id + '" data-tabindex="' + tabIndex + '"';
                            newFrame += ' style="width: 100%; height: ' + tabContent.height() + 'px; border: 0px;"';
                            newFrame += '></iframe>';
                        }
                        if (e.target.nodeName == 'I') {
                            var navSrc = e.target.getAttribute('lay-id');
                            //显示加载层
                            var tmpIndex = layer.load();
                            //设置1秒后再次关闭loading
                            setTimeout(function() {
                                layer.close(tmpIndex);
                            }, 1000);
                            var preSrc = $('#LAY_app_tabsheader').children('li').last().attr('lay-id');
                            var newFrame = '<iframe class="J_iframe" onload="layui.layer.close(' + tmpIndex + ')"';
                            newFrame += ' src="' + preSrc + '" data-id="' + id + '" data-tabindex="' + tabIndex + '"';
                            newFrame += ' style="width: 100%; height: ' + tabContent.height() + 'px; border: 0px;"';
                            newFrame += '></iframe>';
                        }
                    })
                    //切换到指定索引的卡片
                element.tabChange(tabFilter, src);
                //隐藏第一个切换卡的删除按钮
                tabTitle.find('li').eq(0).find('i').hide();
            }
        });
    }


    /**
     * 根据索引点击导航栏的某个li
     */
    function clickLI(index) {
        nav.find('li').eq(index || 0).click();
        topNav.find('')
    }
    /**
     * 导出接口
     */
    exports('cms', function(navLayFilter, tabLayFilter, topNavLayFilter) {
        navFilter = navLayFilter;
        tabFilter = tabLayFilter;
        topNavFilter = topNavLayFilter;
        nav = $('.layui-nav[lay-filter=' + navFilter + ']').eq(0);
        tab = $('.layui-tab[lay-filter=' + tabFilter + ']').eq(0);
        tabContent = tab.children('.layui-tab-content').eq(0);
        tabTitle = tab.children('.layui-tab-title').eq(0);
        topNav = $('.layui-nav[lay-filter=' + topNavFilter + ']').eq(0);

        var error = '';
        if (nav.length == 0) {
            error += '没有找到导航栏<br>';
        }

        if (tab.length == 0) {
            error += '没有找到切换卡<br>';
        }

        if (topNav.length == 0) {
            error += '没有找到顶部导航栏<br>';
        }

        if (error) {
            layer.msg('cms模块初始化失败！<br>' + error);
            return false;
        }

        return { addNav: addNav, bind: bind, clickLI: clickLI };
    });
});