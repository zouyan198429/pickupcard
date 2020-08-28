

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
  <style>
  	  .shuoming { padding: 25px 10%;  line-height: 2em; color: #333; background:#fafafa; }
  	  body {
  		  height: auto;
  	  }
  	  .wrap {
  		  height: auto;
  	  }
  </style>
</head>
<body >
    <p>{!! $info['product_info']['content'] or '' !!}</p>
    <p>{{ $info['activity_info']['tag_price'] or '0.00' }}</p>
    <input type="button" value="{{ $info['activity_info']['pay_price'] or '0.00' }}元提货" id="submitBtn">
    <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
  <script src="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/layui.all.js')}}"></script>
  @include('public.dynamic_list_foot')
  <script>
      var LOGIN_URL = "{{ url('site/login') }}/";///150/pro10010
      // var INDEX_URL = "{{url('site/index')}}";
      var CODE_ID = "{{ $code_id or '' }}";
      var CODE = "{{ $code or '' }}";
  </script>
  <script src="{{ asset('/js/site/product.js') }}?1"  type="text/javascript"></script>
</body>
</html>
