<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', app_name())</title>
    <meta name="description" content="@yield('meta_description', 'Admin Dashboard')">
    <meta name="author" content="@yield('meta_author', 'tokenio.cn')">
    @yield('meta')

    {{ style('static/layuiadmin/layui/css/layui.css') }}
    {{ style('static/layuiadmin/style/admin.css') }}
</head>
<body class="layui-layout-body">

    <div id="LAY_app">
        <div class="layui-layout layui-layout-admin">

            <div class="layui-header">
                <!-- 头部区域 -->
                <ul class="layui-nav layui-layout-left">
                    <li class="layui-nav-item layadmin-flexible" lay-unselect>
                        <a href="javascript:" layadmin-event="flexible" title="侧边伸缩">
                            <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
                        </a>
                    </li>

                    <li class="layui-nav-item" lay-unselect>
                        <a href="javascript:" layadmin-event="refresh" title="刷新">
                            <i class="layui-icon layui-icon-refresh-3"></i>
                        </a>
                    </li>
                </ul>
                <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">
                    <li class="layui-nav-item" lay-unselect>
                        <a href="javascript:;">
                            <i class="layui-icon layui-icon-website"></i>
                            <cite>{{ app()->getLocale() }}</cite>
                        </a>

                        <dl class="layui-nav-child" style="overflow-y: auto; max-height: 300px;">
                            <dd>
                                @foreach(array_keys(config('locale.languages')) as $lang)
                                    @if($lang != app()->getLocale())
                                        <a href="{{ '/lang/'.$lang }}" class="dropdown-item pt-1 pb-1">@lang('menus.language-picker.langs.'.$lang)</a>
                                    @endif
                                @endforeach
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item" lay-unselect>
                        <a lay-href="app/message/index.html" lay-text="消息中心" layadmin-event="message">
                            <i class="layui-icon layui-icon-notice"></i>

                            <!-- 如果有新消息，则显示小圆点 -->
                            <span class="layui-badge-dot"></span>
                        </a>
                    </li>
                    <li class="layui-nav-item layui-hide-xs" lay-unselect>
                        <a href="javascript:" layadmin-event="theme">
                            <i class="layui-icon layui-icon-theme"></i>
                        </a>
                    </li>

                    <li class="layui-nav-item" lay-unselect>
                        <a href="javascript:;">
                            <cite>{{ $logged_in_user->name }}</cite>
                        </a>
                        <dl class="layui-nav-child">
                            {{--<dd><a lay-href="/admin/admin_info"></a></dd>--}}
                            <hr>
                            <dd style="text-align: center;">
                                <!--                            <a lay-href="/admin/login_out">退出</a>-->
                                <a onclick="logout()">退出</a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item layui-hide-xs" lay-unselect>
                        <a href="javascript:" layadmin-event="fullscreen">
                            <i class="layui-icon layui-icon-screen-full"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- 侧边菜单 -->
            <div class="layui-side layui-side-menu">
                <div class="layui-side-scroll">
                    <div class="layui-logo" lay-href="home/console.html">
                        <span> {{ app_name() }}</span>
                    </div>

                    <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu"
                        lay-filter="layadmin-system-side-menu">
                        @foreach($menu as $item)
                            <li data-name="home" class="layui-nav-item layui-nav-itemed">
                                <a href="javascript:;"
                                   @if (empty($item['children']) && !empty($item['url'])) lay-href="{{ $item['url'] }}"@endif lay-tips="{{ $item['title'] }}" lay-direction="2">
                                    <i class="layui-icon layui-icon-template"></i>
                                    <cite>{{ $item['title'] }}</cite>
                                </a>
                                @if (!empty($item['children']))
                                    <dl class="layui-nav-child">
                                        @foreach($item['children'] as $_k => $_item)
                                            <dd data-name="{$_k}">
                                                <a href="javascript:;"
                                                   @if (empty($_item['children']) && !empty($_item['url'])) lay-href="{{ $_item['url'] }}"@endif>{{ $_item['title'] }}</a>
                                                @if (!empty($_item['children']))
                                                <dl class="layui-nav-child">
                                                    @foreach ($_item['children'] as $__k => $__item)
                                                        <dd data-name="{$_k}_{$__k}">
                                                            <a href="javascript:;"
                                                               @if (empty($__item['children']) && !empty($__item['url'])) lay-href="{{ $__item['url'] }}"@endif>{{ $__item['title'] }}</a>
                                                        </dd>
                                                    @endforeach
                                                </dl>
                                                @endif
                                            </dd>
                                        @endforeach
                                    </dl>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- 页面标签 -->
            <div class="layadmin-pagetabs" id="LAY_app_tabs">
                <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
                <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
                <div class="layui-icon layadmin-tabs-control layui-icon-down">
                    <ul class="layui-nav layadmin-tabs-select" lay-filter="layadmin-pagetabs-nav">
                        <li class="layui-nav-item" lay-unselect>
                            <a href="javascript:;"></a>
                            <dl class="layui-nav-child layui-anim-fadein">
                                <dd layadmin-event="closeThisTabs"><a href="javascript:">关闭当前标签页</a></dd>
                                <dd layadmin-event="closeOtherTabs"><a href="javascript:">关闭其它标签页</a></dd>
                                <dd layadmin-event="closeAllTabs"><a href="javascript:">关闭全部标签页</a></dd>
                            </dl>
                        </li>
                    </ul>
                </div>
                <div class="layui-tab" lay-allowClose="true" lay-filter="layadmin-layout-tabs" lay-unauto>
                    <ul class="layui-tab-title" id="LAY_app_tabsheader">
                        <li class="layui-this" lay-attr="{{ $defaultPage }}" lay-id="{{ $defaultPage }}"><i
                                class="layui-icon layui-icon-home"></i></li>
                    </ul>
                </div>
            </div>


            <!-- 主体内容 -->
            <div class="layui-body" id="LAY_app_body">
                <div class="layadmin-tabsbody-item layui-show">
                    <iframe class="layadmin-iframe" frameborder="0" src="{{ $defaultPage }}"></iframe>
                </div>
            </div>

            <!-- 辅助元素，一般用于移动设备下遮罩 -->
            <div class="layadmin-body-shade" layadmin-event="shade"></div>
        </div>
    </div>

    {!! script('static/layuiadmin/layui/layui.js') !!}
    <script>
        layui.config({
            base: '/static/layuiadmin/' //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use('index')

        function logout () {
            layer.confirm('真的退出不？', function (index) {
                window.location.href = '/admin/login_out'
            })
        }
    </script>
</body>
</html>


