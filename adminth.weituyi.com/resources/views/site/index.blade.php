

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
	  body {
	    		  height: auto;
				  background-color: #FFD18E;
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
	  #qingdan  {
	  		  
	  }
	  .title2 {width:100%; text-align: center; line-height: 2rem;  padding: 0.5rem 0; font-size: 1rem; color: #F24040; background-color: #FFF2DB;}
	  #demo {overflow:hidden; height:8rem; width: 100%; padding:0.5rem 0;  background-color: #FDF2EC; }
	  #rollbox { width:90%; margin:0 auto; padding:0.5rem 0;  }
	  #rollbox ul li { color: #333; list-style: none; padding:8px 0; margin:0;}
	  #rollbox2 { width:90%; margin:0 auto; padding:0.5rem 0;  }
	  #rollbox2 ul li { color: #333; list-style: none; padding:8px 0; margin:0;}
	  .shr { display: inline-block; width: 4rem;}
	  .ddh { display: inline-block; padding:0 1rem;}
	  .fhzt { display: inline-block; width: 4rem; text-align: right;}
	  .box1 { 
	  		  padding: 15px;
	  		  margin-top:15px;
	  			  background-color: #FFE7C3;
	  }
	  .shuoming { 
	  		  line-height: 2em; 
	  		  color: #333;
	  		  padding:10px 20px;  		  
	  		  background-color: #FDF2EC;
	  		}
	  		#shuoming2 {
	  			padding:20px 0;  
	  			margin-bottom: 3rem;
	  			margin-top:1rem;	
	  		}
  </style>
</head>
<body >

<div class="wrap">
    <div class="indpic">
		<img src="/images/top00.jpg" alt="">
         
		<!-- <img src="{ { asset('web/images/p' . $product_id . '.jpg') }}" alt=""> -->
		
    </div>
    <form class="am-form" action="#"  method="post"  id="addForm">
		<div class="indmain">

			<select class="layui-input layui-unselect" name="activity_id" >
				<option value="">请选择活动</option>
				@foreach ($activity_kv as $k=>$txt)
					<option value="{{ $k }}"  @if(isset($defaultActivity) && $defaultActivity == $k) selected @endif >{{ $txt }}</option>
				@endforeach
			</select>
	{{--        @foreach ($activity_kv as $k=>$txt)--}}
	{{--            <label><input type="radio"  name="activity_id"  value="{{ $k }}"  @if(isset($defaultActivity) && $defaultActivity == $k) checked="checked"  @endif />{{ $txt }} </label>--}}
	{{--        @endforeach--}}
		  <input type="text" name="code"  value="{{ $code or '' }}" lay-verify="required" placeholder="请输入卡号" autocomplete="off" class="layui-input">

		  <input type="text" name="code_password" value=""  lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input" >
		  <input type="button" lay-submit="" lay-filter="layuiadmin-app-form-submit" value="登录领取" class="layui-btn layui-btn-normal"  id="submitBtn">

		</div>
    </form>
	
	<div class="box1">
			<div  id="qingdan">
				<div class="title2">
						最新发货清单，请注意查收
				</div>
				<div id="demo" >
					<div id="rollbox">
						<ul>
							<li>
								<span class="shr">梁*平</span> <span class="ddh">订单号：20351****0234</span> <span class="fhzt"> 已发货</span>
							</li>
							<li>
								<span class="shr">郭*娟</span> <span class="ddh">订单号：20351****0233</span> <span class="fhzt"> 已发货</span>
							</li>
							<li>
								<span class="shr">王*</span> <span class="ddh">订单号：20349****0232</span> <span class="fhzt"> 已发货</span>
							</li>
							<li>
								<span class="shr">柯*丽</span> <span class="ddh">订单号：20348****0230</span> <span class="fhzt"> 已发货</span>
							</li>
							<li>
								<span class="shr">甘**萍</span> <span class="ddh">订单号：20348****0229</span> <span class="fhzt"> 已发货</span>
							</li>
							<li>
								<span class="shr">纪*生</span> <span class="ddh">订单号：20348****0228</span> <span class="fhzt"> 已发货</span>
							</li>
							<li>
								<span class="shr">张*杏</span> <span class="ddh">订单号：20347****0226</span> <span class="fhzt"> 已发货</span>
							</li>
							<li>
								<span class="shr">刘*勇</span> <span class="ddh">订单号：20347****0225</span> <span class="fhzt"> 已发货</span>
							</li>
							<li>
								<span class="shr">吴*良</span> <span class="ddh">订单号：20347****0223</span> <span class="fhzt"> 已发货</span>
							</li>			
						</ul>
					</div>
				<div id="rollbox2"></div>
				</div>	
			</div>
			<div id="shuoming2">
				<div class="title2">兑换须知</div>
				<div class="shuoming">
					<p>1.提货卡上印制的提货码及密码，为唯一提货标识，提货密码为一次性使用，请在未提货前勿将涂层刮开。</p>
					<p>2.提货卡不兑现、不找零、遗失不补，敬请妥善保管。</p>
					<p>3.赠送人购机时已经开具发票，受赠人提货时不提供发票。</p>
					<p>4.提货卡请在有效期内使用，逾期无法保证指定商品兑换有效性。</p>
					<p>5.全国大部分地区支持配送，新疆、西藏、内蒙古、青海、海南、宁夏、甘肃、香港、澳门、台湾等区域不配送。</p>
					<p>6.本活动最终解释权归属本公司所有。</p>
				</div>
			</div>
	</div>

<!--  <p class="copyright">{{ $copyright or '' }} 版权所有 </p> -->

 
  </div>
  
  
  
  <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
  <script src="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/layui.all.js')}}"></script>
  @include('public.dynamic_list_foot')
  <script>
      var LOGIN_URL = "{{ url('api/site/ajax_save') }}";
      // var INDEX_URL = "{{url('site/index')}}";

  </script>
  <script src="{{ asset('/js/site/index.js') }}"  type="text/javascript"></script>
  <script>
     var speed=50
     rollbox2.innerHTML=rollbox.innerHTML
     function Marquee(){
     if(rollbox2.offsetTop-demo.scrollTop<=0)
     demo.scrollTop-=demo1.offsetHeight
     else{
     demo.scrollTop++
     }
     }
     var MyMar=setInterval(Marquee,speed)
     demo.onmouseover=function() {clearInterval(MyMar)}
     demo.onmouseout=function() {MyMar=setInterval(Marquee,speed)}
  </script> 
</body>
</html>
