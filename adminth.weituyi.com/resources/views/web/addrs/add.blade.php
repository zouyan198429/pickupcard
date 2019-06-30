

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

  <form class="am-form am-form-horizontal" method="post"  id="addForm">
    <input type="hidden" name="id" value="{{ $info['id'] or 0 }}"/>
    <div class="title th-title">
      填写收货地址
    </div>
    <div class="th-main">
    <div class="layui-form-item">
      <label class="layui-form-label">收货人</label>
      <input type="text" name="real_name" value="" lay-verify="required" placeholder="请输入收货人" autocomplete="off" class="layui-input">
    </div>

    <div class="layui-form-item">
      <label class="layui-form-label">收货电话</label>
      <input type="text" name="tel" value="" lay-verify="required" placeholder="请输入收货电话" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">收货地址</label>
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
          <select name="area_id" style="width:90px;">
            <option value="">请选择县/区</option>
          </select>
        </div>
      </div>
    </div>

    <input type="text" name="addr" value="" lay-verify="required" placeholder="请输入详细地址" autocomplete="off" class="layui-input">
    <input type="button"  id="submitBtn" lay-submit="" lay-filter="layuiadmin-app-form-submit" value="提交" class="layui-btn">
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
    var SAVE_URL = "{{ url('api/web/addrs/ajax_save') }}";// ajax保存记录地址
    var LIST_URL = "{{url('web/addrs/add')}}";//保存成功后跳转到的地址

    {{--var SELECT_LATLNG_URL = "{{url('web/qqMaps/latLngSelect')}}";//选择经纬度的地址--}}

    var PROVINCE_CHILD_URL  = "{{url('api/web/city/ajax_get_child')}}";// 获得地区子区域信息
    var CITY_CHILD_URL  = "{{url('api/web/city/ajax_get_child')}}";// 获得地区子区域信息

    const PROVINCE_ID = "-1";// 省默认值
    const CITY_ID = "-1";// 市默认值
    const AREA_ID = "-1";// 区默认值
</script>
<script src="{{ asset('/js/web/lanmu/addrs_edit.js') }}"  type="text/javascript"></script>