

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
  	  }
  	  .wrap {
  		  height: auto;
		  width: 100%;
		  text-align: center;
  	  }
	  .wrap img {
		  max-width: 100%;
	  }
	  .foot {
		  position: fixed;
		  left:0;
		  bottom: 0;
		  width:100%;
		  height: 60px;
		  background-color: #fff;
		  box-shadow: 0 0 3px #888;
	  }
	  .price-sc {
		  width:45%;
		  float: left;
		  margin-left:10px;
		  text-align: left;
	  }
	  .price-sc span {
		  font-size: 12px;
		  color: #999;
		  display: inline;
		  line-height: 48px;
	  }
	  .price-sc strong {
		  font-size: 22px;
		  color: #e00;
		  line-height: 48px;
		  display: inline;
	  }
	  #submitBtn {
		  float:right;
		  margin-right:15px;
		  height: 44px;
		  max-width: 45%;
		  line-height: 32px;
		  font-size: 18px;
		  background:#F24040;
		  color: #fff;
		  padding:0 28px;
		  margin-top:6px;
		  border-radius: 40px;
		  border:0;
	  }
  </style>
</head>
<body > 
	<div class="wrap">
		{!! $info['product_info']['content'] or '' !!}
	</div> 
	
	<div class="foot">
		<div class="price-sc"> <span>市场价：</span> <strong class="red">{{ $info['activity_info']['tag_price'] or '0.00' }}</strong></div>
		<input type="button" value="0元提货" id="submitBtn"> 
		<!-- {{ $info['activity_info']['pay_price'] or '0.00' }} -->
	</div>
	 
    
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
