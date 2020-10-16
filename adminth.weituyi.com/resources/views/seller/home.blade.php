

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>开启头部工具栏 - 数据表格</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    @include('seller.layout_public.pagehead')
    <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/style/admin.css')}}" media="all">
	<style>
		.mbpic-k img {
			width: 300px;
			height: auto;
		}
		.mbpic-q img {
			width: 300px;
		}
	</style>
</head>
<body> 
<div class="mm">
	<div class="layui-card">
		<h2>欢迎使用提货宝礼品营销平台！</h2>
	</div>
	<div class="layui-col-sm6 layui-col-md3">
	      <div class="layui-card">
	        <div class="layui-card-header">
	          访问量
	          <span class="layui-badge layui-bg-blue layuiadmin-badge">周</span>
	        </div>
	        <div class="layui-card-body layuiadmin-card-list">
	          <p class="layuiadmin-big-font">9,999,666</p>
	          <p>
	            总计访问量 
	            <span class="layuiadmin-span-color">88万 <i class="layui-icon"></i></span>
	          </p>
	        </div>
	      </div>
	    </div>
		<div class="layui-col-sm6 layui-col-md3">
		      <div class="layui-card">
		        <div class="layui-card-header">
		          访问量
		          <span class="layui-badge layui-bg-blue layuiadmin-badge">周</span>
		        </div>
		        <div class="layui-card-body layuiadmin-card-list">
		          <p class="layuiadmin-big-font">9,999,666</p>
		          <p>
		            总计访问量 
		            <span class="layuiadmin-span-color">88万 <i class="layui-icon"></i></span>
		          </p>
		        </div>
		      </div>
		    </div>
			<div class="layui-col-sm6 layui-col-md3">
			      <div class="layui-card">
			        <div class="layui-card-header">
			          访问量
			          <span class="layui-badge layui-bg-blue layuiadmin-badge">周</span>
			        </div>
			        <div class="layui-card-body layuiadmin-card-list">
			          <p class="layuiadmin-big-font">9,999,666</p>
			          <p>
			            总计访问量 
			            <span class="layuiadmin-span-color">88万 <i class="layui-icon"></i></span>
			          </p>
			        </div>
			      </div>
			    </div>
				<div class="layui-col-sm6 layui-col-md3">
				      <div class="layui-card">
				        <div class="layui-card-header">
				          访问量
				          <span class="layui-badge layui-bg-blue layuiadmin-badge">周</span>
				        </div>
				        <div class="layui-card-body layuiadmin-card-list">
				          <p class="layuiadmin-big-font">9,999,666</p>
				          <p>
				            总计访问量 
				            <span class="layuiadmin-span-color">88万 <i class="layui-icon"></i></span>
				          </p>
				        </div>
				      </div>
				    </div>
    卡商城
			<div class="hd">
		 			<h1>提货卡</h1>
		 		</div>
		 		<div class="main mbpic-k">
					<img src="http://www.0101jz.com/tihuo/upload/card1.jpg" alt="">
					<img src="http://www.0101jz.com/tihuo/upload/card2.jpg" alt="">
					<img src="http://www.0101jz.com/tihuo/upload/card3.jpg" alt="">
					<img src="http://www.0101jz.com/tihuo/upload/card4.jpg" alt="">
					<img src="http://www.0101jz.com/tihuo/upload/card5.jpg" alt="">
					<img src="http://www.0101jz.com/tihuo/upload/card6.jpg" alt="">
					<img src="http://www.0101jz.com/tihuo/upload/card7.jpg" alt="">
					<img src="http://www.0101jz.com/tihuo/upload/card8.jpg" alt="">	
					<div class="c"></div>
				</div>
				<div class="hd">
					<h1>提货券</h1>
				</div>
				<div class="main mbpic-q">
					<img src="http://www.0101jz.com/tihuo/upload/quan1.jpg" alt="">	
					<img src="http://www.0101jz.com/tihuo/upload/quan2.jpg" alt="">	
					<img src="http://www.0101jz.com/tihuo/upload/quan3.jpg" alt="">	
					<img src="http://www.0101jz.com/tihuo/upload/quan4.jpg" alt="">	
					<div class="c"></div>
				</div>

</div>

<script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
<script src="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/layui.all.js')}}"></script> 
@include('public.dynamic_list_foot')

</body>
</html>
