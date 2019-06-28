

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
            {{--<tr>--}}
                {{--<th>类型<span class="must">*</span></th>--}}
                {{--<td>--}}
                    {{--<select class="wnormal" name="admin_type">--}}
                        {{--<option value="">请选择类型</option>--}}
                        {{--@foreach ($adminType as $k=>$txt)--}}
                            {{--<option value="{{ $k }}"  @if(isset($defaultAdminType) && $defaultAdminType == $k) selected @endif >{{ $txt }}</option>--}}
                        {{--@endforeach--}}
                    {{--</select>--}}
                {{--</td>--}}
            {{--</tr>--}}
            <tr>
                <th>姓名<span class="must">*</span></th>
                <td>
                    <input type="text" class="inp wnormal"  name="real_name" value="{{ $info['real_name'] or '' }}" placeholder="请输入姓名"/>
                </td>
            </tr>
            <tr>
                <th>性别<span class="must">*</span></th>
                <td  class="layui-input-block">
                    <label><input type="radio" name="sex" value="1" @if (isset($info['sex']) && $info['sex'] == 1 ) checked @endif>男</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" name="sex" value="2" @if (isset($info['sex']) && $info['sex'] == 2 ) checked @endif>女</label>
                </td>
            </tr>
            <tr>
                <th>状态<span class="must">*</span></th>
                <td  class="layui-input-block">
                    <label><input type="radio" name="account_status" value="0" @if (isset($info['account_status']) && $info['account_status'] == 0 ) checked @endif>正常</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" name="account_status" value="1" @if (isset($info['account_status']) && $info['account_status'] == 1 ) checked @endif>冻结</label>
                </td>
            </tr>
            <tr>
                <th>手机<span class="must">*</span></th>
                <td>
                    <input type="text" class="inp wnormal"  name="mobile" value="{{ $info['mobile'] or '' }}" placeholder="请输入手机"  onkeyup="isnum(this) " onafterpaste="isnum(this)"  />
                </td>
            </tr>
            <tr>
            <th>座机电话</th>
            <td>
            <input type="text" class="inp wnormal"  name="tel" value="{{ $info['tel'] or '' }}" placeholder="请输入座机电话"  />
            </td>
            </tr>
            <tr>
            <th>QQ\email\微信</th>
            <td>
            <input type="text" class="inp wnormal"  name="qq_number" value="{{ $info['qq_number'] or '' }}" placeholder="请输入QQ\email\微信" />
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
                <th>经纬度<span class="must"></span></th>
                <td>
                    <span class="latlngtxt">{{ $info['latitude'] or '纬度' }}，{{ $info['longitude'] or '经度' }}</span>
                    <input type="hidden" name="latitude"  value="{{ $info['latitude'] or '' }}" />
                    <input type="hidden" name="longitude"  value="{{ $info['longitude'] or '' }}" />
                    <button  type="button"  class="btn btn-danger  btn-xs ace-icon fa fa-plus-circle bigger-60"  onclick="otheraction.selectLatLng(this)">选择经纬度</button>
                </td>
            </tr>
            <tr>
                <th>用户名<span class="must">*</span></th>
                <td>
                    <input type="text" class="inp wnormal"  name="admin_username" value="{{ $info['admin_username'] or '' }}" placeholder="请输入用户名"/>
                </td>
            </tr>
            <tr>
                <th>登录密码<span class="must">*</span></th>
                <td>
                    <input type="password"  class="inp wnormal"   name="admin_password" placeholder="登录密码" />修改时，可为空，不修改密码。
                </td>
            </tr>
            <tr>
                <th>确认密码<span class="must">*</span></th>
                <td>
                    <input type="password" class="inp wnormal"     name="sure_password"  placeholder="确认密码"/>修改时，可为空，不修改密码。
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
    var SAVE_URL = "{{ url('api/admin/staff/ajax_save') }}";// ajax保存记录地址
    var LIST_URL = "{{url('admin/staff')}}";//保存成功后跳转到的地址

    var SELECT_LATLNG_URL = "{{url('admin/qqMaps/latLngSelect')}}";//选择经纬度的地址

    var PROVINCE_CHILD_URL  = "{{url('api/admin/city/ajax_get_child')}}";// 获得地区子区域信息
    var CITY_CHILD_URL  = "{{url('api/admin/city/ajax_get_child')}}";// 获得地区子区域信息

    const PROVINCE_ID = "{{ $info['province_id'] or -1}}";// 省默认值
    const CITY_ID = "{{ $info['city_id'] or -1 }}";// 市默认值
    const AREA_ID = "{{ $info['area_id'] or -1 }}";// 区默认值
</script>
<script src="{{ asset('/js/admin/lanmu/staff_edit.js') }}"  type="text/javascript"></script>
</body>
</html>