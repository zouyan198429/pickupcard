

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>开启头部工具栏 - 数据表格</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!-- zui css -->
    <link rel="stylesheet" href="{{asset('dist/css/zui.min.css') }}">
    @include('manage.layout_public.pagehead')
    <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/style/admin.css')}}" media="all">
</head>
<body>

{{--<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> {{ $operate or '' }}员工</div>--}}
<div class="mm">
    {{--<div class="alert alert-warning alert-dismissable">--}}
        {{--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>--}}
        {{--<p>一次最多上传1张图片。</p>--}}
    {{--</div>--}}
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
                    <input type="text"  name="begin_time" class="begin_time" id="begin_time"  value="{{ $info['begin_time'] or '' }}"  placeholder="开始日期" style="width:100px;" readonly />
                    --
                    <input type="text"  name="end_time" class="end_time" id="end_time"  value="{{ $info['end_time'] or '' }}"  placeholder="结束日期" style="width:100px;"  readonly/>
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
            <tr>
                <th>兑换码长度<span class="must">*</span></th>
                <td>
                    <input type="text" class="inp wnormal"  name="code_len" value="{{ $info['code_len'] or '' }}" placeholder="兑换码长度"  onkeyup="isnum(this) " onafterpaste="isnum(this)"  />
                    <span>指定生成的兑换码的长度。</span>
                </td>
            </tr>
            <tr>
                <th>生成兑换码时默认状态<span class="must">*</span></th>
                <td>
                    @foreach ($defaultOpenStatus as $k=>$txt)
                    <label><input type="radio"  name="default_open_status"  value="{{ $k }}"  @if(isset($defaultDefaultOpenStatus) && $defaultDefaultOpenStatus == $k) checked="checked"  @endif />{{ $txt }} </label>

                    @endforeach
                </td>
            </tr>
            {{--<tr>--}}
                {{--<th>排序[降序]<span class="must">*</span></th>--}}
                {{--<td>--}}
                    {{--<input type="text" class="inp wnormal"  name="sort_num" value="{{ $info['sort_num'] or '' }}" placeholder="请输入排序"  onkeyup="isnum(this) " onafterpaste="isnum(this)"  />--}}
                {{--</td>--}}
            {{--</tr>--}}

            <tr>
                <th>活动主图</th>
                <td>
                    <div class="row  baguetteBoxOne gallery ">
                        <div class="col-xs-6">
                            @component('component.upfileone.piconecode')
                                @slot('fileList')
                                    grid
                                @endslot
                                @slot('upload_url')
                                    {{ url('api/manage/upload') }}
                                @endslot
                            @endcomponent
                            {{--
                            <input type="file" class="form-control" value="">
                            --}}
                        </div>
                    </div>
                    最多上传1张图片。

                </td>
            </tr>
            <tr>
                <th>活动提示<span class="must">*</span></th>
                <td>
                    <input type="text" class="inp wnormal"  name="activity_tips" value="{{ $info['activity_tips'] or '' }}" placeholder="请输入活动提示"/>
                </td>
            </tr>
            <tr>
                <th>供货商<span class="must"></span></th>
                <td>
                    <input type="text" class="inp wnormal"  name="activity_theme" value="{{ $info['activity_theme'] or '' }}" placeholder="请输入供货商"/>
                </td>
            </tr>
            <tr>
                <th>商家广告语<span class="must"></span></th>
                <td>
                    <input type="text" class="inp wnormal"  name="activity_subtitle" value="{{ $info['activity_subtitle'] or '' }}" placeholder="请输入商家广告语"/>
                </td>
            </tr>
            <tr>
                <th>吊牌价<span class="must">*</span></th>
                <td>
                    <input type="text" class="inp wnormal"  name="tag_price" value="{{ $info['tag_price'] or '' }}" placeholder="请输入吊牌价"  onkeyup="numxs(this) " onafterpaste="numxs(this)"/>
                </td>
            </tr>
            <tr>
                <th>商品价[付款]<span class="must">*</span></th>
                <td>
                    <input type="text" class="inp wnormal"  name="price" value="{{ $info['price'] or '' }}" placeholder="请输入商品价"  onkeyup="numxs(this) " onafterpaste="numxs(this)"/>
                </td>
            </tr>
            <tr>
                <th>快递费<span class="must">*</span></th>
                <td>
                    <input type="text" class="inp wnormal"  name="freight_price" value="{{ $info['freight_price'] or '' }}" placeholder="请输入快递费"  onkeyup="numxs(this) " onafterpaste="numxs(this)"/>
                </td>
            </tr>
            <tr>
                <th>保价费<span class="must">*</span></th>
                <td>
                    <input type="text" class="inp wnormal"  name="insured_price" value="{{ $info['insured_price'] or '' }}" placeholder="请输入保价费"  onkeyup="numxs(this) " onafterpaste="numxs(this)"/>
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
    var SAVE_URL = "{{ url('api/manage/activity/ajax_save') }}";// ajax保存记录地址
    var LIST_URL = "{{url('manage/activity')}}";//保存成功后跳转到的地址

    var BEGIN_DATE = "{{ $info['begin_time'] or '' }}" ;//开始日期
    var END_DATE = "{{ $info['end_time'] or '' }}" ;//结束日期

    // 上传图片变量
    var FILE_UPLOAD_URL = "{{ url('api/manage/upload') }}";// 文件上传提交地址 'your/file/upload/url'
    var PIC_DEL_URL = "{{ url('api/manage/upload/ajax_del') }}";// 删除图片url
    var MULTIPART_PARAMS =  {};// {pro_unit_id:'0'};// 附加参数	函数或对象，默认 {}
    var LIMIT_FILES_COUNT = 1;//   限制文件上传数目	false（默认）或数字
    var MULTI_SELECTION = false;//  是否可用一次选取多个文件	默认 true false
    var FLASH_SWF_URL = "{{asset('dist/lib/uploader/Moxie.swf') }}";// flash 上传组件地址  默认为 lib/uploader/Moxie.swf
    var SILVERLIGHT_XAP_URL = "{{asset('dist/lib/uploader/Moxie.xap') }}";// silverlight_xap_url silverlight 上传组件地址  默认为 lib/uploader/Moxie.xap  请确保在文件上传页面能够通过此地址访问到此文件。
    var SELF_UPLOAD = true;//  是否自己触发上传 TRUE/1自己触发上传方法 FALSE/0控制上传按钮
    var FILE_UPLOAD_METHOD = 'initPic()';// 单个上传成功后执行方法 格式 aaa();  或  空白-没有
    var FILE_UPLOAD_COMPLETE = '';  // 所有上传成功后执行方法 格式 aaa();  或  空白-没有
    var FILE_RESIZE = {quuality: 40};
    // resize:{// 图片修改设置 使用一个对象来设置如果在上传图片之前对图片进行修改。该对象可以包含如下属性的一项或全部：
    //     // width: 128,// 图片压缩后的宽度，如果不指定此属性则保持图片的原始宽度；
    //     // height: 128,// 图片压缩后的高度，如果不指定此属性则保持图片的原始高度；
    //     // crop: true,// 是否对图片进行裁剪；
    //     quuality: 50,// 图片压缩质量，可取值为 0~100，数值越大，图片质量越高，压缩比例越小，文件体积也越大，默认为 90，只对 .jpg 图片有效；
    //     // preserve_headers: false // 是否保留图片的元数据，默认为 true 保留，如果为 false 不保留。
    // },
    var RESOURCE_LIST = @json($info['resource_list'] ?? []) ;
    var PIC_LIST_JSON =  {'data_list': RESOURCE_LIST };// piclistJson 数据列表json对象格式  {‘data_list’:[{'id':1,'resource_name':'aaa.jpg','resource_url':'picurl','created_at':'2018-07-05 23:00:06'}]}

</script>
<link rel="stylesheet" href="{{asset('js/baguetteBox.js/baguetteBox.min.css')}}">
<script src="{{asset('js/baguetteBox.js/baguetteBox.min.js')}}" async></script>
{{--<script src="{{asset('js/baguetteBox.js/highlight.min.js')}}" async></script>--}}
<!-- zui js -->
<script src="{{asset('dist/js/zui.min.js') }}"></script>
<script src="{{ asset('/js/manage/lanmu/activity_edit.js') }}?3"  type="text/javascript"></script>
@component('component.upfileincludejs')
@endcomponent
</body>
</html>
