<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title', app_name())</title>
    <meta content="webkit" name="renderer">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    {{ style('static/layuiadmin/layui/css/layui.css') }}
    {{ style('static/layuiadmin/style/admin.css') }}
    {{ style('static/layuiadmin/style/login.css') }}
    {{ style(mix('build/css/admin.css')) }}
</head>
<body>

<div id="LAY_app" class="layadmin-user-login layadmin-user-display-show" style="display: none;">

    <div class="layadmin-user-login-main">
        <div class="layadmin-user-login-box layadmin-user-login-header">
            <h2>{{ app_name() }}</h2>
            <p>后台管理系统</p>
        </div>
        <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
            @csrf
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-username"
                       for="LAY-user-login-username"></label>
                <input class="layui-input" id="LAY-user-login-username" lay-verify="required" name="username"
                       placeholder="用户名"
                       type="text">
            </div>
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-password"
                       for="LAY-user-login-password"></label>
                <input class="layui-input" id="LAY-user-login-password" lay-verify="required" name="password"
                       placeholder="密码" type="password">
            </div>
            <div class="layui-form-item">
                <div class="layui-row">
                    <div class="layui-col-xs7">
                        <label class="layadmin-user-login-icon layui-icon layui-icon-vercode"
                               for="LAY-user-login-vercode"></label>
                        <input class="layui-input" id="LAY-user-login-vercode" lay-verify="required" name="captcha"
                               placeholder="图形验证码" type="text">
                    </div>
                    <div class="layui-col-xs5">
                        <div style="margin-left: 10px;">
                            <img class="layadmin-user-login-codeimg" id="LAY-user-get-vercode"
                                 src="{{ Captcha::src() }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn layui-btn-fluid" lay-filter="LAY-user-login-submit" lay-submit>登 入</button>
            </div>
        </div>
    </div>

</div>

{!! script('static/layuiadmin/layui/layui.js') !!}
{!! script(mix('build/js/manifest.js')) !!}
{!! script(mix('build/js/vendor.js')) !!}
{!! script(mix('build/js/admin.js')) !!}
<script>
    layui.use(['index', 'user'], function () {
        // 强制父窗口跳转登录界面
        if (window != top) {
            top.location.href = location.href;
        }


        var $ = layui.$
            , $body = $('body')
            , form = layui.form
        var captchaUrl = '{{ Captcha::src() }}'
        var $captcha = $('#LAY-user-get-vercode')

        $(document).keydown(function(event){
            if(event.keyCode == 13){
                $("[lay-filter=LAY-user-login-submit]").click();
            }
        });

        form.render()

        //提交
        form.on('submit(LAY-user-login-submit)', function (obj) {
            $.ajax({
                url: '{{ route('admin.auth.login.post') }}',
                type: 'post',
                data: obj.field,
                success: function(res) {
                    layer.msg('登录成功, 跳转中...', {
                        offset: '15px',
                        time: 2000,
                        end: function() {
                            window.location.href = '{{ route('admin.dashboard') }}';
                        }
                    })
                },
                error: function(res) {
                    updateCaptcha()
                    layer.msg(res.responseJSON.message || '登录失败', {
                        offset: '15px',
                        time: 2000
                    })
                }
            })
        })

        $body.on('click', '#LAY-user-get-vercode', updateCaptcha)

        function updateCaptcha () {
            $captcha.attr('src', captchaUrl + '?t=' + new Date().getTime())
        }
    })
</script>
</body>
</html>
