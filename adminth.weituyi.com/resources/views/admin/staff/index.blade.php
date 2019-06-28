

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

{{--<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 我的同事</div>--}}
<div class="mm">
  <div class="mmhead" id="mywork">

    @include('common.pageParams')
    <div class="tabbox" >
      <a href="javascript:void(0);" class="on" onclick="action.iframeModify(0)">添加后台管理员</a>
    </div>
    <form onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm" action="#">
      <div class="msearch fr" style="width:700px;">
        <input type="hidden" name="admin_type" value="{{ $admin_type or 0 }}" />
        <input type="hidden" name="city_site_id" value="{{ $city_site_id or 0 }}" />
        <input type="hidden" name="city_partner_id" value="{{ $city_partner_id or 0 }}" />
        <input type="hidden" name="seller_id" value="{{ $seller_id or 0 }}" />
        <input type="hidden" name="shop_id" value="{{ $shop_id or 0 }}" />
        <select class="wmini" name="account_status" style="width: 55px;">
          <option value="">请选择状态</option>
          @foreach ($accountStatus as $k=>$txt)
            <option value="{{ $k }}"  @if(isset($defaultAccountStatus) && $defaultAccountStatus == $k) selected @endif >{{ $txt }}</option>
          @endforeach
        </select>
        <select class="wnormal" name="province_id" style="width: 80px;">
          <option value="">请选择省</option>
          @foreach ($province_kv as $k=>$txt)
            <option value="{{ $k }}"  @if(isset($defaultProvince) && $defaultProvince == $k) selected @endif >{{ $txt }}</option>
          @endforeach
        </select>
        <select class="wnormal" name="city_id" style="width: 80px;">
          <option value="">请选择市</option>
        </select>
        <select class="wnormal" name="area_id" style="width: 80px;">
          <option value="">请选择区县</option>
        </select>
        {{--<select class="wmini" name="province_id" style="width: 55px;">--}}
          {{--<option value="">全部</option>--}}
          {{--@foreach ($adminType as $k=>$txt)--}}
            {{--<option value="{{ $k }}"  @if(isset($defaultAdminType) && $defaultAdminType == $k) selected @endif >{{ $txt }}</option>--}}
          {{--@endforeach--}}
        {{--</select>--}}
        <select style="width:80px; height:28px;" name="field">
          <option value="admin_username">用户名</option>
          <option value="real_name">真实姓名</option>
          <option value="tel">电话</option>
          <option value="mobile">手机</option>
          <option value="qq_number">QQ\email\微信</option>
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
     <colgroup>
            <col width="30">
            <col width="">
            <col width="100">
            <col width="120">
            <col width="120">
            <col width="120">
            <col width="120">
            <col width="120">
            <col width="220">
            <col width="160">
            <col width="120">
            <col width="120">
            <col width="120">
            <col>
      </colgroup>
    <thead>
    <tr>
      <th>
        <label class="pos-rel">
          <input type="checkbox"  class="ace check_all"  value="" onclick="action.seledAll(this)"/>
<!--           <span class="lbl">全选</span>
 -->        </label>
      </th> 
      <th>用户名</th>
      <th>真实姓名</th>
      <th>类型</th>
      <th>性别</th>
      <th>电话</th>
      <th>手机</th>
      <th>QQ\email\微信</th>
      <th>地址</th>
      <th>是否超级帐户</th>
      <th>状态</th>
      <th>最近登陆</th>
      <th>操作</th>
    </tr>
    </thead>
    <tbody id="data_list">
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
      var AJAX_URL = "{{ url('api/admin/staff/ajax_alist') }}";//ajax请求的url
      var ADD_URL = "{{ url('admin/staff/add/0') }}"; //添加url

      var IFRAME_MODIFY_URL = "{{url('admin/staff/add/')}}/";//添加/修改页面地址前缀 + id
      var IFRAME_MODIFY_URL_TITLE = "后台管理员" ;// 详情弹窗显示提示  [添加/修改] +  栏目/主题
      var IFRAME_MODIFY_CLOSE_OPERATE = 0 ;// 详情弹窗operate_num关闭时的操作0不做任何操作1刷新当前页面2刷新当前列表页面

      var SHOW_URL = "{{url('admin/staff/info/')}}/";//显示页面地址前缀 + id
      var SHOW_URL_TITLE = "" ;// 详情弹窗显示提示
      var SHOW_CLOSE_OPERATE = 0 ;// 详情弹窗operate_num关闭时的操作0不做任何操作1刷新当前页面2刷新当前列表页面
      var EDIT_URL = "{{url('admin/staff/add/')}}/";//修改页面地址前缀 + id
      var DEL_URL = "{{ url('api/admin/staff/ajax_del') }}";//删除页面地址
      var BATCH_DEL_URL = "{{ url('api/admin/staff/ajax_del') }}";//批量删除页面地址
      var EXPORT_EXCEL_URL = "{{ url('admin/staff/export') }}";//导出EXCEL地址
      var IMPORT_EXCEL_TEMPLATE_URL = "{{ url('admin/staff/import_template') }}";//导入EXCEL模版地址
      var IMPORT_EXCEL_URL = "{{ url('api/admin/staff/import') }}";//导入EXCEL地址
      var IMPORT_EXCEL_CLASS = "import_file";// 导入EXCEL的file的class

      var PROVINCE_CHILD_URL  = "{{url('api/admin/city/ajax_get_child')}}";// 获得地区子区域信息
      var CITY_CHILD_URL  = "{{url('api/admin/city/ajax_get_child')}}";// 获得地区子区域信息

      const PROVINCE_ID = "{{ $info['province_id'] or -1}}";// 省默认值
      const CITY_ID = "{{ $info['city_id'] or -1 }}";// 市默认值
      const AREA_ID = "{{ $info['area_id'] or -1 }}";// 区默认值

  </script>
  <script src="{{asset('js/common/list.js')}}"></script>
  <script src="{{ asset('js/admin/lanmu/staff.js') }}"  type="text/javascript"></script>
</body>
</html>