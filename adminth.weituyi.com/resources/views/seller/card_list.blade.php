

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
 
	<h2>定制礼品卡、券</h2>
	
	<!-- <hr>
	<h3>
		成品卡购买
	</h3>
	<br>
	<p>请联系客服人员：电话18992800832</p>
	<br>
	<br>
	<br> -->
	<hr>
	<h3>卡片定制免费设计</h3>
	<br>
	<h4>卡</h4>
	<p>	500张卡片  680元</p>
	<p>	1000张卡片  880元</p>
	<p>	3000张卡片  1880元</p>
	<p>	5000张卡片  2880元</p>
	<h4>券</h4>
	<p>	1000张券  680元</p>
	<p>	2000张券  880元</p>
	<p>	5000张券  1580元</p>
	<p>	10000张券  2480元</p>
	
	<p> 5000个卡密（不含卡片）   500元</p>
	<br>
	<p>请联系客服人员：电话18992800832</p>
	<br>
	
	 
	
	
	
			<div class="hd">
		 			<h4>提货卡样式参考</h4>
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
					<h4>提货券样式参考</h4>
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
