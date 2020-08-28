

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
	  .layui-btn-normal {
		  background-color: #fff;
		  color: #333;
		  border:1px solid #ccc;
	  }
  </style>
</head>
<body >
<div class="wrap">
    <div class="indpic">

<!-- {{--        {{ $info['activity_info']['activity_theme'] or '' }}--}}
{{--        {{ $info['activity_info']['activity_subtitle'] or '' }}--}} -->
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
      <input type="button" lay-submit="" lay-filter="layuiadmin-app-form-submit" value="登录领取" class="layui-btn layui-btn-normal"  id="submitBtn">

    </div>
    </form>
	<div class="shuoming"> 		
		<h4>兑换须知：</h4>
		<p>1.提货卡上印制的提货码及密码，为唯一提货标识，提货密码为一次性使用，请在未提货前勿将涂层刮开。</p>
		<p>2.提货卡不兑现、不找零、遗失不补，敬请妥善保管。</p>
		<p>3.赠送人购机时已经开具发票，受赠人提货时不提供发票。</p>
		<p>4.提货卡请在有效期内使用，逾期无法保证指定商品兑换有效性。</p>
		<p>5.全国大部分地区支持配送，新疆、西藏、内蒙古、青海、海南、宁夏、甘肃、香港、澳门、台湾等区域不配送。</p>
		<p>6.本活动最终解释权归属本公司所有。</p>
	</div>


</div>

<!--  <p class="copyright">{{ $copyright or '' }} 版权所有 </p> -->
  <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
  <script src="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/layui.all.js')}}"></script>
  @include('public.dynamic_list_foot')
  <script>
      var LOGIN_URL = "{{ url('api/site/ajax_login') }}";
      // var INDEX_URL = "{{url('site/index')}}";

  </script>
  <script src="{{ asset('/js/site/login.js') }}"  type="text/javascript"></script>
</body>
</html>
