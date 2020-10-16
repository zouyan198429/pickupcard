

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
    {{--  本页单独使用 --}}
    <script src="{{asset('dist/lib/kindeditor/kindeditor.min.js')}}"></script>
</head>
<body>

{{--<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> {{ $operate or '' }}员工</div>--}}
<div class="mm">
    <form class="am-form am-form-horizontal" method="post"  id="addForm">
        <input type="hidden" name="id" value="{{ $info['id'] or 0 }}"/>
        <table class="table1">
            <tr>
                <th>商品名称<span class="must">*</span></th>
                <td>
                    <input type="text" class="inp wnormal"  name="product_name" value="{{ $info['product_name'] or '' }}" placeholder="请输入商品名称"/>
                </td>
            </tr>
           <!-- <tr>
                <th>编码前缀<span class="must"></span></th>
                <td>
                    <input type="text" class="inp wnormal"  name="pre_code" value="{{ $info['pre_code'] or '' }}" placeholder="请输入编码前缀"/>
                </td>
            </tr> -->
            <tr>
                <th>内容<span class="must">*</span></th>
                <td>
                    <textarea class="kindeditor" name="content" rows="15" id="doc-ta-1" style=" width:770px;height:400px;">{!!  htmlspecialchars($info['content'] ?? '' )   !!}</textarea>
                    {{--<textarea type="text" class="inptext wlong layui-textarea"  style=" height:500px" /></textarea>
                    <p class="tip">根据客户描述，进行记录或备注。</p>--}}
                </td>
            </tr>
            <tr>
                <th>排序[降序]<span class="must">*</span></th>
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
    var SAVE_URL = "{{ url('api/seller/products/ajax_save') }}";// ajax保存记录地址
    var LIST_URL = "{{url('seller/products')}}";//保存成功后跳转到的地址
</script>
<script src="{{ asset('/js/seller/lanmu/products_edit.js') }}"  type="text/javascript"></script>
</body>
</html>
