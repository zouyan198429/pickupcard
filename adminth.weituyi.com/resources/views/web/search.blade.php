

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>在线提货</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/css/layui.css')}}" media="all">
  <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/style/admin.css')}}" media="all">
  <link rel="stylesheet" href="{{asset('web/css/style.css')}}" media="all">
</head>
<body  class="layui-layout-body">

  <div class="wrap">
    <div class="indpic">

      @foreach ($resource_list as $k => $v)
        <img src="{{ $v['resource_url'] }}" alt="">
      @endforeach
        {{--<img src="{ { asset('web/images/p' . $product_id . '.jpg') }}" alt="">--}}
    </div>
    <form class="am-form" action="#"  method="post"  id="addForm">
    <div class="indmain">
      <input type="hidden" name="code_id"  value="{{ $code_id or 0 }}" />
      <input type="text" name="code"  value="{{ $code or '' }}" lay-verify="required" placeholder="请输入卡号" autocomplete="off" class="layui-input" readonly>

      <input type="text" name="code_password" value=""  lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input" >
      <input type="button" lay-submit="" lay-filter="layuiadmin-app-form-submit" value="登录领取" class="layui-btn"  id="submitBtn">

    </div>
    </form>
  </div>


  <p class="copyright">陕西富县蒙驴生态科技有限公司 版权所有 </p>
  <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
  <script src="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/layui.all.js')}}"></script>
  @include('public.dynamic_list_foot')
  <script>
      var LOGIN_URL = "{{ url('api/web/ajax_login') }}";
      // var INDEX_URL = "{{url('web/search')}}";

  </script>
  <script src="{{ asset('/js/web/login.js') }}"  type="text/javascript"></script>
</body>
</html>