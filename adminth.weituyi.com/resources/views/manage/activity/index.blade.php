

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>开启头部工具栏 - 数据表格</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  @include('manage.layout_public.pagehead')
  <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/css/layui.css')}}" media="all">
  <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/style/admin.css')}}" media="all">
</head>
<body>

{{--<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 我的同事</div>--}}
<div class="mm">
  <div class="mmhead" id="mywork">

    @include('common.pageParams')
    <div class="tabbox" >
      <a href="javascript:void(0);" class="on" onclick="action.iframeModify(0)">添加提货活动</a>
    </div>
    <form onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm" action="#">
      <div class="msearch fr">

        {{--<select class="wmini" name="province_id">--}}
          {{--<option value="">全部</option>--}}
          {{--@foreach ($province_kv as $k=>$txt)--}}
            {{--<option value="{{ $k }}"  @if(isset($province_id) && $province_id == $k) selected @endif >{{ $txt }}</option>--}}
          {{--@endforeach--}}
        {{--</select>--}}

        <select class="wmini" name="product_id" style="width: 100px;">
          <option value="">请选择商品</option>
          @foreach ($product_kv as $k=>$txt)
            <option value="{{ $k }}"  @if(isset($defaultProduct) && $defaultProduct == $k) selected @endif >{{ $txt }}</option>
          @endforeach
        </select>
        <select class="wmini" name="status" style="width: 70px;">
          <option value="">请选择状态</option>
          @foreach ($status as $k=>$txt)
            <option value="{{ $k }}"  @if(isset($defaultStatus) && $defaultStatus == $k) selected @endif >{{ $txt }}</option>
          @endforeach
        </select>
        <select style="width:80px; height:28px;" name="field">
          <option value="activity_name">提货活动名称</option>
        </select>
        <input type="text" value=""    name="keyword"  placeholder="请输入关键字"/>
        <button class="btn btn-normal search_frm">搜索</button>
      </div>
    </form>
  </div>
  {{--
  <div class="table-header">
    { {--<button class="btn btn-danger  btn-xs batch_del"  onclick="action.batchDel(this)">批量删除</button>--} }
    <button class="btn btn-success  btn-xs export_excel"  onclick="action.batchExportExcel(this)" >导出[按条件]</button>
    <button class="btn btn-success  btn-xs export_excel"  onclick="action.exportExcel(this)" >导出[勾选]</button>
    <button class="btn btn-success  btn-xs import_excel"  onclick="action.importExcelTemplate(this)">导入模版[EXCEL]</button>
    <button class="btn btn-success  btn-xs import_excel"  onclick="action.importExcel(this)">导入城市</button>
    <div style="display:none;" ><input type="file" class="import_file img_input"></div>{ {--导入file对象--} }
  </div>
--}}
  <table lay-even class="layui-table"  lay-size="lg"  id="dynamic-table"  class="table2">
    <thead>
    <tr>
      {{--<th>--}}
        {{--<label class="pos-rel">--}}
          {{--<input type="checkbox"  class="ace check_all"  value="" onclick="action.seledAll(this)"/>--}}
          {{--<!-- <span class="lbl">全选</span> -->--}}
        {{--</label>--}}
      {{--</th>--}}
      {{--<th>ID</th>--}}
        <th>提货活动名称<hr/>兑换商品</th>
        <th>活动日期<hr/>活动提示</th>
        <th>图片</th>
        {{--      <th></th>--}}
        <th>供货商<hr/>商家广告语</th>
        <th>吊牌价<hr/>商品价</th>
        <th>快递费<hr/>保价费</th>
        <th>编码前缀</th>
        <th>起始编码<hr/>兑换码长度</th>
        {{--      <th></th>--}}
        <th>提货卡总量<hr/>已兑换数量</th>
        {{--      <th></th>--}}
        {{--      <th></th>--}}
        <th>状态</th>
        {{--<th>排序[降序]</th>--}}
        {{--<th>添加人</th>--}}
        {{--<th>添加日期</th>--}}
        <th>操作</th>
    </tr>
    </thead>
    <tbody id="data_list"  class=" baguetteBoxOne gallery">
    </tbody>
  </table>
  <div class="mmfoot">
    <div class="mmfleft"></div>
    <div class="pagination">
    </div>
  </div>

</div>

  <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
  <script src="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/layui.all.js')}}"></script>
  {{--<script src="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/layui.js')}}"></script>--}}
  @include('public.dynamic_list_foot')

  <script type="text/javascript">
      var OPERATE_TYPE = <?php echo isset($operate_type)?$operate_type:0; ?>;
      var AUTO_READ_FIRST = false;//自动读取第一页 true:自动读取 false:指定地方读取
      var LIST_FUNCTION_NAME = "reset_list_self";// 列表刷新函数名称, 需要列表刷新同步时，使用自定义方法reset_list_self；异步时没有必要自定义
      var AJAX_URL = "{{ url('api/manage/activity/ajax_alist') }}";//ajax请求的url
      var ADD_URL = "{{ url('manage/activity/add/0') }}"; //添加url

      var IFRAME_MODIFY_URL = "{{url('manage/activity/add/')}}/";//添加/修改页面地址前缀 + id
      var IFRAME_MODIFY_URL_TITLE = "提货活动" ;// 详情弹窗显示提示  [添加/修改] +  栏目/主题
      var IFRAME_MODIFY_CLOSE_OPERATE = 0 ;// 详情弹窗operate_num关闭时的操作0不做任何操作1刷新当前页面2刷新当前列表页面

      var SHOW_URL = "{{url('manage/activity/info/')}}/";//显示页面地址前缀 + id
      var SHOW_URL_TITLE = "" ;// 详情弹窗显示提示
      var SHOW_CLOSE_OPERATE = 0 ;// 详情弹窗operate_num关闭时的操作0不做任何操作1刷新当前页面2刷新当前列表页面
      var EDIT_URL = "{{url('manage/activity/add/')}}/";//修改页面地址前缀 + id
      var DEL_URL = "{{ url('api/manage/activity/ajax_del') }}";//删除页面地址
      var BATCH_DEL_URL = "{{ url('api/manage/activity/ajax_del') }}";//批量删除页面地址
      var EXPORT_EXCEL_URL = "{{ url('manage/activity/export') }}";//导出EXCEL地址
      var IMPORT_EXCEL_TEMPLATE_URL = "{{ url('manage/activity/import_template') }}";//导入EXCEL模版地址
      var IMPORT_EXCEL_URL = "{{ url('api/manage/activity/import') }}";//导入EXCEL地址
      var IMPORT_EXCEL_CLASS = "import_file";// 导入EXCEL的file的class

      var CODE_LIST_URL = "{{ url('manage/codes') }}"; //兑换码管理
  </script>
<link rel="stylesheet" href="{{asset('js/baguetteBox.js/baguetteBox.min.css')}}">
<script src="{{asset('js/baguetteBox.js/baguetteBox.min.js')}}" async></script>
{{--<script src="{{asset('js/baguetteBox.js/highlight.min.js')}}" async></script>--}}

<script src="{{asset('js/common/list.js')}}"></script>
  <script src="{{ asset('js/manage/lanmu/activity.js') }}?1"  type="text/javascript"></script>
</body>
</html>
