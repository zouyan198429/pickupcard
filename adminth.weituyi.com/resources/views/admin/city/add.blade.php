

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>开启头部工具栏 - 数据表格</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    @include('admin.layout_public.pagehead')
    <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/style/admin.css')}}" media="all">
</head>
<body>

{{--<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> {{ $operate or '' }}员工</div>--}}
<div class="mm">
    <form class="am-form am-form-horizontal" method="post"  id="addForm">
        <input type="hidden" name="id" value="{{ $info['id'] or 0 }}"/>
        <table class="table1">
            <tr>
                <th>所属<span class="must"></span></th>
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
                </td>
            </tr>
            <tr>
                <th>名称<span class="must">*</span></th>
                <td>
                    <input type="text" class="inp wnormal"  name="city_name" value="{{ $info['city_name'] or '' }}" placeholder="请输入名称" />
                </td>
            </tr>
            <tr>
                <th>城市代码<span class="must">*</span></th>
                <td>
                    <input type="text" class="inp wnormal"  name="code" value="{{ $info['code'] or '' }}" placeholder="请输入城市代码" />
                </td>
            </tr>
            <tr>
                <th>是否城市分站<span class="must">*</span></th>
                <td>
                    @foreach ($isCitySite as $k=>$txt)
                        <label><input type="radio"  name="is_city_site"  value="{{ $k }}"  @if(isset($defaultIsCitySite) && $defaultIsCitySite == $k) checked="checked"  @endif />{{ $txt }} </label>
                    @endforeach
                </td>
            </tr>
            <tr>
                <th>类型<span class="must">*</span></th>
                <td>
                    @foreach ($cityType as $k=>$txt)
                        <label><input type="radio"  name="city_type"  value="{{ $k }}"  @if(isset($defaultCityType) && $defaultCityType == $k) checked="checked"  @endif />{{ $txt }} </label>
                    @endforeach
                </td>
            </tr>
            <tr>
                <th>经纬度<span class="must"></span></th>
                <td>
                    <span class="latlngtxt">{{ $info['latitude'] or '纬度' }}，{{ $info['longitude'] or '经度' }}</span>
                    <input type="hidden" name="latitude"  value="{{ $info['latitude'] or '' }}" />
                    <input type="hidden" name="longitude"  value="{{ $info['longitude'] or '' }}" />
                    <button  type="button"  class="btn btn-danger  btn-xs ace-icon fa fa-plus-circle bigger-60"  onclick="otheraction.selectLatLng(this)">选择经纬度</button>
                </td>
            </tr>
            <tr>
                <th>排序[降序]<span class="must"></span></th>
                <td>
                    <input type="text" class="inp wnormal"  name="sort_num" value="{{ $info['sort_num'] or '' }}" placeholder="请输入排序"  onkeyup="isnum(this) " onafterpaste="isnum(this)"  />
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
    var SAVE_URL = "{{ url('api/admin/city/ajax_save') }}";// ajax保存记录地址
    var LIST_URL = "{{url('admin/city')}}";//保存成功后跳转到的地址

    var SELECT_LATLNG_URL = "{{url('admin/qqMaps/latLngSelect')}}";//选择经纬度的地址

    var PROVINCE_CHILD_URL  = "{{url('api/admin/city/ajax_get_child')}}";// 获得地区子区域信息
    var CITY_CHILD_URL  = "{{url('api/admin/city/ajax_get_child')}}";// 获得地区子区域信息

    const PROVINCE_ID = "{{ $info['province_id'] or -1}}";// 省默认值
    const CITY_ID = "{{ $info['city_id'] or -1 }}";// 市默认值
    const AREA_ID = "{{ $info['area_id'] or -1 }}";// 区默认值

</script>
<script src="{{ asset('/js/admin/lanmu/city_edit.js') }}"  type="text/javascript"></script>
</body>
</html>