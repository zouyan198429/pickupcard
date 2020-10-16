<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>商户注册</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    @include('seller.layout_public.pagehead')
  <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/css/layui.css')}}" media="all">
  <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/style/admin.css')}}" media="all">
  <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/style/login.css')}}" media="all">
  <style>
	 body,html {
		 background-color: #4866FA;
		 position: relative;
	 }
	 
	 .layadmin-user-login {
		 padding-top:0px;
	 }
	 .layadmin-user-login-main {
		 width: 780px;
		 margin:0 auto;
		 background: #fff;
		 border-radius: 12px;
		 padding-top:30px;
	 }
	 .layadmin-user-login-other { 
	 }
	 .layadmin-user-login-other a {
		 color: #fff;
	 }
  </style>
</head>
<body>
 <div class="layui-trans layui-form-item layadmin-user-login-other"> 
          <a href="{{ url('seller/login') }}" class="layadmin-user-jump-change ">用已有帐号登入</a> 
	</div>
  <div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" >
    <div class="layadmin-user-login-main">
      <div class="layadmin-user-login-box layadmin-user-login-header">
        <h2>礼品卡/券营销系统</h2>
		<p>商家注册</p>
      </div>
	  
	  <hr>
      <div class="layadmin-user-login-box layadmin-user-login-body">

          <form class="am-form am-form-horizontal" method="post"  id="addForm">
              <input type="hidden" name="id" value="{{ $info['id'] or 0 }}"/>
              <table class="table1">
				  <tr>
				      <th><span class="must">*</span>用户名</th>
				      <td>
				          <input type="text" class="inp wnormal"  name="admin_username"   style="width:360px;"  value="{{ $info['admin_username'] or '' }}" placeholder="请输入用户名"/>
				      </td>
				  </tr>
				  <tr>
				      <th><span class="must">*</span>登录密码</th>
				      <td>
				          <input type="password"  class="inp wnormal"   style="width:360px;"   name="admin_password" placeholder="登录密码" />
				      </td>
				  </tr>
				  <tr>
				      <th><span class="must">*</span>确认密码</th>
				      <td>
				          <input type="password" class="inp wnormal"    style="width:360px;"    name="sure_password"  placeholder="确认密码"/>
				      </td>
				  </tr>
                  <tr>
					  <td></td>
					  <td></td>
				  </tr>
                  <tr>
                      <th><span class="must">*</span>商家名称</th>
                      <td>
                          <input type="text" class="inp wnormal"  name="seller_name"  style="width:360px;"  value="{{ $info['seller_name'] or '' }}" placeholder="请输入商家名称"/>
                      </td>
                  </tr>
                  <tr>
                      <th><span class="must">*</span>您的姓名</th>
                      <td>
                          <input type="text" class="inp wnormal"  name="real_name"  style="width:360px;"  value="{{ $info['real_name'] or '' }}" placeholder="请输入姓名"/>
                      </td>
                  </tr>
                  <tr>
                      <th><span class="must">*</span>性别</th>
                      <td  class="layui-input-block">
                          <label><input type="radio" name="sex" value="1" @if (isset($info['sex']) && $info['sex'] == 1 ) checked @endif>男</label>&nbsp;&nbsp;&nbsp;&nbsp;
                          <label><input type="radio" name="sex" value="2" @if (isset($info['sex']) && $info['sex'] == 2 ) checked @endif>女</label>
                      </td>
                  </tr>
                  <tr>
                      <th><span class="must">*</span>手机</th>
                      <td>
                          <input type="text" class="inp wnormal"  name="mobile"  style="width:360px;"  value="{{ $info['mobile'] or '' }}" placeholder="请输入手机"  onkeyup="isnum(this) " onafterpaste="isnum(this)"  />
                      </td>
                  </tr>
                 <!-- <tr>
                      <th>座机电话</th>
                      <td>
                          <input type="text" class="inp wnormal"  name="tel"  style="width:360px;"  value="{{ $info['tel'] or '' }}" placeholder="请输入座机电话"  />
                      </td>
                  </tr>
                  <tr>
                      <th>微信</th>
                      <td>
                          <input type="text" class="inp wnormal"  name="qq_number"  style="width:360px;"  value="{{ $info['qq_number'] or '' }}" placeholder="请输入微信" />
                      </td>
                  </tr> -->
                  <tr>
                      <th>地址<span class="must"></span></th>
                      <td>

                          <select class="wnormal" name="province_id" style="width: 100px;">
                              <option value="">请选择省</option>
                              @foreach ($province_kv as $k=>$txt)
                                  <option value="{{ $k }}"  @if(isset($info['province_id']) && $info['province_id'] == $k) selected @endif >{{ $txt }}</option>
                              @endforeach
                          </select>
                          <select class="wnormal" name="city_id" style="width: 100px;">
                              <option value="">请选择市</option>
                          </select>
                          <select class="wnormal" name="area_id" style="width: 100px;">
                              <option value="">请选择区县</option>
                          </select>
                          <br/><br/>
                          <input type="text" class="inp wnormal"  style="width:360px;" name="addr" value="{{ $info['addr'] or '' }}" placeholder="请输入详细地址"  />
                      </td>
                  </tr>
                  <tr style="display: none;">
                      <th>经纬度<span class="must"></span></th>
                      <td>
                          <span class="latlngtxt">{{ $info['latitude'] or '纬度' }}，{{ $info['longitude'] or '经度' }}</span>
                          <input type="hidden" name="latitude"  value="{{ $info['latitude'] or '' }}" />
                          <input type="hidden" name="longitude"  value="{{ $info['longitude'] or '' }}" />
                          <button  type="button"  class="btn btn-danger  btn-xs ace-icon fa fa-plus-circle bigger-60"  onclick="otheraction.selectLatLng(this)">选择经纬度</button>
                      </td>
                  </tr>
                
                  <tr>
                      <th> </th>
                      <td><button class="layui-btn layui-btn-fluid"  id="submitBtn" style="width:360px;" >提交</button></td>
                  </tr>

              </table>
          </form>

       
      </div>
    </div>
  
  </div>

  <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
  <script src="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/layui.all.js')}}"></script>
  {{--<script src="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/layui.js')}}"></script>--}}
  @include('public.dynamic_list_foot')
  <script type="text/javascript">
      var SAVE_URL = "{{ url('api/seller/ajax_reg') }}";// ajax保存记录地址
      var LIST_URL = "{{url('seller')}}";// "{ {url('seller/seller')}}";//保存成功后跳转到的地址

      var SELECT_LATLNG_URL = "{{url('seller/qqMaps/latLngSelect')}}";//选择经纬度的地址

      var PROVINCE_CHILD_URL  = "{{url('api/seller/city/ajax_get_child')}}";// 获得地区子区域信息
      var CITY_CHILD_URL  = "{{url('api/seller/city/ajax_get_child')}}";// 获得地区子区域信息

      const PROVINCE_ID = "{{ $info['province_id'] or -1}}";// 省默认值
      const CITY_ID = "{{ $info['city_id'] or -1 }}";// 市默认值
      const AREA_ID = "{{ $info['area_id'] or -1 }}";// 区默认值
  </script>
  <script src="{{ asset('/js/seller/lanmu/seller_reg.js') }}?3"  type="text/javascript"></script>
</body>
</html>
