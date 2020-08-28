

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
		  max-width: 800px;
	  }
  </style>
</head>
<body >
<p id="pay">我要支付 </p>
  <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
  <script src="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/layui.all.js')}}"></script>
  @include('public.dynamic_list_foot')
  <script>
      var LOGIN_URL = "{{ url('api/web/ajax_save') }}";
      // var INDEX_URL = "{{url('web/index')}}";
      $(function(){
          //提交
          $(document).on("click","#pay",function(){
             alert('支付');
              return false;
          });
      });

  </script>
  <script src="{{ asset('/js/web/index.js') }}"  type="text/javascript"></script>
</body>
</html>
