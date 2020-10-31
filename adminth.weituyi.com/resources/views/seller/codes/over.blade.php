

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
</head>
<body>

{{--<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> {{ $operate or '' }}员工</div>--}}
<div class="mm">
    <form class="am-form am-form-horizontal" method="post"  id="addForm">
        <input type="hidden" name="id" value="{{ $info['id'] or 0 }}"/>
        <input type="hidden" name="code_id" value="{{ $info['code_id'] or 0 }}"/>
        <table class="table1">
            <tr>
                <th>收货人<span class="must"></span></th>
                <td>
                    <input type="text" class="inp wnormal"  name="real_name" value="{{ $info['real_name'] or '' }}" placeholder="请输入收货人"/>
                </td>
            </tr>
            <tr>
            <th>收货电话<span class="must"></span></th>
            <td>
            <input type="text" class="inp wnormal"  name="tel" value="{{ $info['tel'] or '' }}" placeholder="请输入收货电话"  />
            </td>
            </tr>
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
                    <input type="text" class="inp wnormal"  style="width:600px;" name="addr" value="{{ $info['addr'] or '' }}" placeholder="请输入详细地址"  />
                </td>
            </tr>
            <tr>
                <th>状态<span class="must">*</span></th>
                <td>
                    <select class="wmini" name="status" style="width: 70px;">
                        <option value="">请选择状态</option>
                        @foreach ($status as $k=>$txt)
                            <option value="{{ $k }}"  @if(isset($defaultStatus) && $defaultStatus == $k) selected @endif >{{ $txt }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <th> </th>
                <td><button class="btn btn-l wnormal"  id="submitBtn" >提交</button></td>
            </tr>

        </table>
    </form>
</div>
<script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
<script src="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/layui.all.js')}}"></script>
{{--<script src="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/layui.js')}}"></script>--}}
@include('public.dynamic_list_foot')

<script type="text/javascript">
    var SAVE_URL = "{{ url('api/seller/addrs/ajax_save') }}";// ajax保存记录地址
    var LIST_URL = "{{url('seller/addrs')}}";//保存成功后跳转到的地址

    var SELECT_LATLNG_URL = "{{url('seller/qqMaps/latLngSelect')}}";//选择经纬度的地址

    var PROVINCE_CHILD_URL  = "{{url('api/seller/city/ajax_get_child')}}";// 获得地区子区域信息
    var CITY_CHILD_URL  = "{{url('api/seller/city/ajax_get_child')}}";// 获得地区子区域信息

    const PROVINCE_ID = "{{ $info['province_id'] or -1}}";// 省默认值
    const CITY_ID = "{{ $info['city_id'] or -1 }}";// 市默认值
    const AREA_ID = "{{ $info['area_id'] or -1 }}";// 区默认值
</script>
<script src="{{ asset('/js/seller/lanmu/codes_over.js') }}?1"  type="text/javascript"></script>
</body>
</html>
