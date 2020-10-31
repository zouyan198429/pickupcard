

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

  <style>
	  body {
		  background-color: #f1f1f1;
		  min-height: 100%;
	  }
	  .wrap {
	    	  height: auto;
			  min-height: 100%;
	  		  width: 100%;
			  max-width: 800px;
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
	  .title {
		  float: left;
		  width:40%;
		  text-align: left;
		  font-size: 16px;
	  }
	  .con {
		  float: right;
		  text-align: right;
		  line-height: 48px;
	  }
	  .c { clear: both;}
	  .line {
		  border-top:1px solid #fafafa;
		  height: 1px;
	  }
	  .box1 {
		  background-color: #fff;
		  padding: 15px;
		  margin-top:15px;
	  }
	  .th-title {
		  color: #999999;
		  font-size: 16px;
		  text-indent: 10px;
		  text-align: left;
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
	  .btn-normal {
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
	  .th-main input {
		  width: 96%;
		  margin:10px 2%;
	  }
	   .layui-form-label {
		  padding-left:8px;
	   }
	   .layui-input-block {
		   width: 96%;
		   margin:10px 2%;
	   }
	.timebox2 { padding-bottom: 0.5rem;}
	.timecounter {display:inline;color: #888 !important; font-size: 0.75rem;}
	.timecounter span { color: #EE0000; font-size: 1.25rem; display: inline-block; border:2px solid #ddd; border-radius: 8px; width: 2rem; text-align:center; }

  </style>

</head>
<body   >

<div class="wrap">

	<img src="http://qqgy.weituyi.com/resource/company/3/images/2020/08/28/top01.jpg" alt="">
	<img src="http://qqgy.weituyi.com/resource/company/3/images/2020/08/28/top02.jpg" alt="">
	<!--
    <p>{{ $info['activity_info']['activity_theme'] or '' }}</p>
    <p>{{ $info['activity_info']['activity_subtitle'] or '' }}</p> -->
    @if(false)
    <div class="indpic">
        @foreach ($resource_list as $k => $v)
            <img src="{{ $v['resource_url'] }}" alt="">
        @endforeach
        {{--<img src="{ { asset('web/images/p' . $product_id . '.jpg') }}" alt="">--}}
    </div>
    @endif
	<div class="box1">

		<div class="timebox2"> 目前距离活动结束还有：
				<div class="timecounter">
					<span id="t_d">7 </span>天
					<span id="t_h">10 </span>时
					<span id="t_m">24 </span>分
					<span id="t_s">8 </span>秒
				</div>
		</div>
		<p>温馨提示：本商品由{{ $info['activity_theme'] or '' }}商户于2020年8月28日批量支付成功，您只需要填写收货信息，即可直接送到您家。</p>
	</div>

{{--        <p>{!! $info['product_info']['content'] or '' !!}</p>--}}
	<div class="box1">

			<!-- <div class="title">
				吊牌价
			</div>
			<div class="con">
				{{ $info['activity_info']['tag_price'] or '0.00' }}
			</div>
			<div class="c line"></div> -->
		<div class="formbox">
			<div class="title">
				商品费用:
			</div>
			<div class="con">
				{{ $info['activity_info']['price'] or '0.00' }}元
			</div>
			<div class="c line"></div>
			<div class="title">
				快递费:
			</div>
			<div class="con">
				{{ $info['activity_info']['freight_price'] or '0.00' }}元
			</div>
			<div class="c line"></div>
			<div class="title">
				保价费:
			</div>
			<div class="con">
				{{ $info['activity_info']['insured_price'] or '0.00' }}元
			</div>
			<div class="c line"></div>
		</div>

	</div>

  <form class="am-form am-form-horizontal" method="post"  id="addForm">
    <input type="hidden" name="id" value="{{ $info['id'] or 0 }}"/>
    <input type="hidden" name="redisKey" value="{{ $redisKey or '' }}"/>
	<div class="box1">
		<div class="th-title">
			  受赠人信息
		</div>
		<div class="th-main">
			<div class="layui-form-item">
			  <label class="layui-form-label">受赠人：</label>
			  <input type="text" name="real_name" value="" lay-verify="required" placeholder="请输入收货人" autocomplete="off" class="layui-input">
			</div>

			<div class="layui-form-item">
			  <label class="layui-form-label">受赠人电话：</label>
			  <input type="text" name="tel" value="" lay-verify="required" placeholder="请输入收货电话" autocomplete="off" class="layui-input">
			</div>
			<div class="layui-form-item">
			  <label class="layui-form-label">收货地址：</label>
				<div class="layui-input-block">
					<div class="layui-inline">
					  <select name="province_id" style="width:90px;">
						<option value="">请选择省</option>
						@foreach ($province_kv as $k=>$txt)
						  <option value="{{ $k }}"  @if(isset($info['province_id']) && $info['province_id'] == $k) selected @endif >{{ $txt }}</option>
						@endforeach
					  </select>
					  <select name="city_id" style="width:90px;">
						<option value="">请选择市</option>
					  </select>
					  <select name="area_id" style="width:120px;">
						<option value="">请选择县/区</option>
					  </select>
					</div>
				</div>
				<input type="text" name="addr" value="" lay-verify="required" placeholder="请输入详细地址" autocomplete="off" class="layui-input">

			</div>
		</div>

	</div>
	<div style="height: 5em; width: 100%; clear: both;"></div>
	<div class="foot">
        <div class="price-sc">
			<span>合计支付</span> <strong> {{ $info['activity_info']['pay_price'] or '0.00' }}</strong><span>元</span>
		</div>
		<input type="button"  id="submitBtn" lay-submit="" lay-filter="layuiadmin-app-form-submit" value="支付领取" class="btn-normal">
	</div>
  </form>

</div>
  <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
  <script src="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/layui.all.js')}}"></script>
  {{--<script src="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/layui.js')}}"></script>--}}
  @include('public.dynamic_list_foot')
</body>
</html>
<script type="text/javascript">
function getRTime() {
	var EndTime = new Date("2020/09/11 00:00:00"); //截止时间
	var NowTime = new Date();
	var t = EndTime.getTime() - NowTime.getTime();
	var d = Math.floor(t / 1000 / 60 / 60 / 24);
	var h = Math.floor(t / 1000 / 60 / 60 % 24);
	var m = Math.floor(t / 1000 / 60 % 60);
	var s = Math.floor(t / 1000 % 60);

	document.getElementById("t_d").innerHTML = d + "";
	document.getElementById("t_h").innerHTML = h + "";
	document.getElementById("t_m").innerHTML = m + "";
	document.getElementById("t_s").innerHTML = s + "";
}
setInterval(getRTime, 1000);
</script>
<script type="text/javascript">
    var SAVE_URL = "{{ url('api/site/addrs/ajax_save') }}";// ajax保存记录地址
    var LIST_URL = "{{url('site/addrs/payOK')}}/";//  "{ {url('site/index')}}";// "{ {url('http://www.sxmenglv.com/')}}";//保存成功后跳转到的地址

    {{--var SELECT_LATLNG_URL = "{{url('site/qqMaps/latLngSelect')}}";//选择经纬度的地址--}}

    var PROVINCE_CHILD_URL  = "{{url('api/site/city/ajax_get_child')}}";// 获得地区子区域信息
    var CITY_CHILD_URL  = "{{url('api/site/city/ajax_get_child')}}";// 获得地区子区域信息

    const PROVINCE_ID = "-1";// 省默认值
    const CITY_ID = "-1";// 市默认值
    const AREA_ID = "-1";// 区默认值

    var REDIS_KEY = "{{ $redisKey or '' }}";
</script>
<script src="{{ asset('/js/site/lanmu/addrs_edit.js') }}?2"  type="text/javascript"></script>
