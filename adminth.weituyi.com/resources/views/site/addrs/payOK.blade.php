

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>在线提货</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  @include('admin.layout_public.pagehead')
  <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/css/layui.css')}}" media="all">
  <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/style/admin.css')}}" media="all">
  <link rel="stylesheet" href="{{asset('web/css/style.css')}}" media="all">
</head>
<body  class="layui-layout-body">

<div class="wrap">

        <p>恭喜您，订单提交成功！</p>
        <p>我们会尽快给您安排发货，请您耐心等待。</p>
    <input type="button"  id="submitBtn" lay-submit="" lay-filter="layuiadmin-app-form-submit" value="返回" class="layui-btn layui-btn-normal">


</div>
  <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
  <script src="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/layui.all.js')}}"></script>
  {{--<script src="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/layui.js')}}"></script>--}}
  @include('public.dynamic_list_foot')
</body>
</html>

<script type="text/javascript">
    // var SAVE_URL = "{{ url('api/site/addrs/ajax_save') }}";// ajax保存记录地址
    // var LIST_URL = "{{url('site/index')}}";// "{{url('http://www.sxmenglv.com/')}}";//保存成功后跳转到的地址

    var LOGIN_URL = "{{ url('site/search') }}/";///150/pro10010
    var CODE_ID = "{{ $code_id or '' }}";
    var CODE = "{{ $code or '' }}";

</script>
<script src="{{ asset('/js/site/lanmu/addrs_payOK.js') }}"  type="text/javascript"></script>
