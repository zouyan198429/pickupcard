

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>登入 - layuiAdmin</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/css/layui.css')}}" media="all">
  <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/style/admin.css')}}" media="all">
  <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/style/login.css')}}" media="all">
  <style>
  .layadmin-user-login-main {
    background: #fff;
    box-shadow: 0 0 16px #ddd;
    border-radius: 4px;
    padding: 50px;
    width: 475px;
  }
  </style>
</head>
<body>

  <div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;">

    <div class="layadmin-user-login-main"  >
      <div class="layadmin-user-login-box layadmin-user-login-header">
        <h2>提货卡管理后台</h2>
        <p>  </p>
      </div>
      <form class="am-form" action="#"  method="post"  id="addForm">
      <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
        <div class="layui-form-item">
          <label class="layadmin-user-login-icon layui-icon layui-icon-username" for="LAY-user-login-username"></label>
          <input type="text" name="admin_username"  placeholder="用户名" class="layui-input">
        </div>
        <div class="layui-form-item">
          <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
          <input type="password" name="admin_password" placeholder="密码" class="layui-input">
        </div>
        {{--
        <div class="layui-form-item">
          <div class="layui-row">
            <div class="layui-col-xs7">
              <label class="layadmin-user-login-icon layui-icon layui-icon-vercode" for="LAY-user-login-vercode"></label>
              <input type="text" name="vercode" id="LAY-user-login-vercode" lay-verify="required" placeholder="图形验证码" class="layui-input">
            </div>
            <div class="layui-col-xs5">
              <div style="margin-left: 10px;">
                <img src="https://www.oschina.net/action/user/captcha" class="layadmin-user-login-codeimg" id="LAY-user-get-vercode">
              </div>
            </div>
          </div>
        </div>
        --}}
        {{--
        <div class="layui-form-item" style="margin-bottom: 20px;">
          <input type="checkbox" name="remember" lay-skin="primary" title="记住密码">
          <a href="{{ url('layui/user/forget') }}" class="layadmin-user-jump-change layadmin-link" style="margin-top: 7px;">忘记密码？</a>
        </div>
        --}}

        <div class="layui-form-item">
          <button class="layui-btn layui-btn-fluid"  id="submitBtn" >登 入</button>
        </div>
        <div class="layui-trans layui-form-item layadmin-user-login-other">
{{--          <label>社交账号登入</label>--}}
{{--          <a href="javascript:;"><i class="layui-icon layui-icon-login-qq"></i></a>--}}
{{--          <a href="javascript:;"><i class="layui-icon layui-icon-login-wechat"></i></a>--}}
{{--          <a href="javascript:;"><i class="layui-icon layui-icon-login-weibo"></i></a>--}}

          <a href="{{ url('company/reg') }}" class="layadmin-user-jump-change layadmin-link">注册帐号</a>
        </div>
      </div>
      </form>
    </div>
    <div class="layui-trans layadmin-user-login-footer">


  </div>

  <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
  <script src="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/layui.all.js')}}"></script>
  @include('public.dynamic_list_foot')
  <script>
      var LOGIN_URL = "{{ url('api/company/ajax_login') }}";
      var INDEX_URL = "{{url('company')}}";

  </script>
  <script src="{{ asset('/js/common/login.js') }}"  type="text/javascript"></script>
</body>
</html>
