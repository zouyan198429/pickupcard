

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
                <th>所属商品<span class="must">*</span></th>
                <td>

                    <select class="wmini" name="product_id" style="width: 200px;">
                        <option value="">请选择商品</option>
                        @foreach ($product_kv as $k=>$txt)
                            <option value="{{ $k }}"  @if(isset($defaultProduct) && $defaultProduct == $k) selected @endif >{{ $txt }}</option>
                        @endforeach
                    </select>
                    {{--@foreach ($product_kv as $k=>$txt)--}}
                        {{--<label><input type="radio"  name="product_id"  value="{{ $k }}"  @if(isset($defaultProduct) && $defaultProduct == $k) checked="checked"  @endif />{{ $txt }} </label>--}}
                    {{----}}
                    {{--@endforeach--}}
                </td>
            </tr>
            <tr>
                <th>活动标题<span class="must">*</span></th>
                <td>
                    <input type="text" class="inp wnormal"  name="activity_name" value="{{ $info['activity_name'] or '' }}" placeholder="请输入活动标题"/>
                </td>
            </tr>
            <tr>
                <th>活动日期<span class="must">*</span></th>
                <td>
                    <input type="text" id="yuyuetime" name="begin_time" class="begin_time" value="{{ $info['begin_time'] or '' }}"  placeholder="开始日期" style="width:100px;" />
                    --
                    <input type="text" id="yuyuetime" name="end_time" class="end_time" value="{{ $info['end_time'] or '' }}"  placeholder="结束日期" style="width:100px;" />
                </td>
            </tr>
            <tr>
                <th>起始编号<span class="must">*</span></th>
                <td>
                    <input type="text" class="inp wnormal"  name="begin_num" value="{{ $info['begin_num'] or '' }}" placeholder="开始号码"  onkeyup="isnum(this) " onafterpaste="isnum(this)"  />
                </td>
            </tr>
            <tr>
                <th>编号数量<span class="must">*</span></th>
                <td>
                    <input type="text" class="inp wnormal"  name="total_num" value="{{ $info['total_num'] or '' }}" placeholder="生成数量"  onkeyup="isnum(this) " onafterpaste="isnum(this)"  />
                </td>
            </tr>
            {{--<tr>--}}
                {{--<th>排序[降序]<span class="must">*</span></th>--}}
                {{--<td>--}}
                    {{--<input type="text" class="inp wnormal"  name="sort_num" value="{{ $info['sort_num'] or '' }}" placeholder="请输入排序"  onkeyup="isnum(this) " onafterpaste="isnum(this)"  />--}}
                {{--</td>--}}
            {{--</tr>--}}

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
    var SAVE_URL = "{{ url('api/admin/activity/ajax_save') }}";// ajax保存记录地址
    var LIST_URL = "{{url('admin/activity')}}";//保存成功后跳转到的地址

    var BEGIN_DATE = "{{ $info['begin_time'] or '' }}" ;//开始日期
    var END_DATE = "{{ $info['end_time'] or '' }}" ;//结束日期
</script>
<script src="{{ asset('/js/admin/lanmu/activity_edit.js') }}"  type="text/javascript"></script>
</body>
</html>