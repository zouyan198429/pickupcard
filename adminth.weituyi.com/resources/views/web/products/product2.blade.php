

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
  <div class="title pro-title">
    配方驴奶粉
  </div>
  <div class="propic">
    <img src="{{asset('web/images/p2/p201.jpg')}}" alt="">
    <img src="{{asset('web/images/p2/p202.jpg')}}" alt="">
    <img src="{{asset('web/images/p2/p203.jpg')}}" alt="">
    <img src="{{asset('web/images/p2/p204.jpg')}}" alt="">
    <img src="{{asset('web/images/p2/p205.jpg')}}" alt="">
    <img src="{{asset('web/images/p2/p206.jpg')}}" alt="">
  </div>
  <div class="probtnbox">
    <input type="button"  id="submitBtn" lay-submit="" lay-filter="layuiadmin-app-form-submit" value="点击提货" class="layui-btn">
  </div>
</div>
  <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
  <script src="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/layui.all.js')}}"></script>
  {{--<script src="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/layui.js')}}"></script>--}}
  @include('public.dynamic_list_foot')
</body>
</html>
<script>
    $(function(){
        //提交
        $(document).on("click","#submitBtn",function(){
            goTop("{{ url('web/addrs/add')}}");
        });
    });
</script>