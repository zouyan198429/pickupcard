

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
<div class="mm">
  <div class="mmhead" id="mywork">

    @include('common.pageParams') 
    <form onsubmit="return false;" class="form-horizontal" role="form" method="post" id="search_frm" action="#">
      <div class="msearch fr" style="width:700px;">
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
 
        <select style="width:80px; height:28px;" name="field">
          <option value="code">提货码</option>
          <option value="real_name">收货人</option>
          <option value="tel">收货电话</option>
          <option value="addr">收货地址</option>
        </select>
        <input type="text" value=""    name="keyword"  placeholder="请输入关键字"/>
        <button class="btn btn-normal search_frm">搜索</button>
      </div>
    </form>
  </div>
  <div class="table-header"> 
    <button class="btn btn-success  btn-xs export_excel"  onclick="action.batchExportExcel(this)" >导出[按条件]</button>
    <button class="btn btn-success  btn-xs export_excel"  onclick="action.exportExcel(this)" >导出[勾选]</button>
      <button class="btn btn-success  btn-xs export_excel"  onclick="otheraction.batchExportExcel(this)" >导出即发货[按条件]</button>
      <button class="btn btn-success  btn-xs export_excel"  onclick="otheraction.exportExcel(this)" >导出即发货[勾选]</button>
      <button class="btn btn-success  btn-xs export_excel"  onclick="otheraction.sendSelected(this)" >发货[勾选]</button> 
  </div>
  <table lay-even class="layui-table"  lay-size="lg"  id="dynamic-table"  class="table2">
	  <colgroup>
	  		  <col width="40">
			  <col width="100">
	  		  <col >
	  		  <col >  
	  		  <col width="180">
	  		  <col width="120">
	  		  <col width="150">
	  		  <col >
			  <col > 
			  <col > 
			  <col > 
			  <col > 
	  	</colgroup> 
    <thead>
    <tr>
      <th>
        <label class="pos-rel">
          <input type="checkbox"  class="ace check_all"  value="" onclick="action.seledAll(this)"/>
       </label>
      </th>
      <th>提货码</th>
      <th>所属活动</th>
      <th>所提商品</th>
      <th>提货时间</th>
      <th>收货人</th>
      <th>收货电话</th>
      <th>收货地址</th>
      <th>当前状态</th>
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
  @include('public.dynamic_list_foot')

  <script type="text/javascript">
      var OPERATE_TYPE = <?php echo isset($operate_type)?$operate_type:0; ?>;
      var AUTO_READ_FIRST = false;//自动读取第一页 true:自动读取 false:指定地方读取
      var LIST_FUNCTION_NAME = "reset_list_self";// 列表刷新函数名称, 需要列表刷新同步时，使用自定义方法reset_list_self；异步时没有必要自定义
      var AJAX_URL = "{{ url('api/seller/addrs/ajax_alist') }}";//ajax请求的url
      var ADD_URL = "{{ url('seller/addrs/add/0') }}"; //添加url

      var IFRAME_MODIFY_URL = "{{url('seller/addrs/add/')}}/";//添加/修改页面地址前缀 + id
      var IFRAME_MODIFY_URL_TITLE = "提货记录" ;// 详情弹窗显示提示  [添加/修改] +  栏目/主题
      var IFRAME_MODIFY_CLOSE_OPERATE = 0 ;// 详情弹窗operate_num关闭时的操作0不做任何操作1刷新当前页面2刷新当前列表页面

      var SHOW_URL = "{{url('seller/addrs/info/')}}/";//显示页面地址前缀 + id
      var SHOW_URL_TITLE = "" ;// 详情弹窗显示提示
      var SHOW_CLOSE_OPERATE = 0 ;// 详情弹窗operate_num关闭时的操作0不做任何操作1刷新当前页面2刷新当前列表页面
      var EDIT_URL = "{{url('seller/addrs/add/')}}/";//修改页面地址前缀 + id
      var DEL_URL = "{{ url('api/seller/addrs/ajax_del') }}";//删除页面地址
      var BATCH_DEL_URL = "{{ url('api/seller/addrs/ajax_del') }}";//批量删除页面地址
      var EXPORT_EXCEL_URL = "{{ url('seller/addrs/export') }}";//导出EXCEL地址
      var IMPORT_EXCEL_TEMPLATE_URL = "{{ url('seller/addrs/import_template') }}";//导入EXCEL模版地址
      var IMPORT_EXCEL_URL = "{{ url('api/seller/addrs/import') }}";//导入EXCEL地址
      var IMPORT_EXCEL_CLASS = "import_file";// 导入EXCEL的file的class

      var PROVINCE_CHILD_URL  = "{{url('api/seller/city/ajax_get_child')}}";// 获得地区子区域信息
      var CITY_CHILD_URL  = "{{url('api/seller/city/ajax_get_child')}}";// 获得地区子区域信息

      const PROVINCE_ID = "{{ $info['province_id'] or -1}}";// 省默认值
      const CITY_ID = "{{ $info['city_id'] or -1 }}";// 市默认值
      const AREA_ID = "{{ $info['area_id'] or -1 }}";// 区默认值

      var AJAX_SEND_URL = "{{ url('api/seller/addrs/ajax_send') }}";//ajax请求发货的url

      // 列表数据每隔指定时间就去执行一次刷新【如果表有更新时】--定时执行
      var IFRAME_TAG_KEY = "";// "QualityControl\\CTAPIStaff";// 获得模型表更新时间的关键标签，可为空：不获取
      var IFRAME_TAG_TIMEOUT = 60000;// 获得模型表更新时间运行间隔 1000:1秒 ；可以不要此变量：默认一分钟


  </script>
  <script src="{{asset('js/common/list.js')}}"></script>
  <script src="{{ asset('js/seller/lanmu/addrs.js') }}"  type="text/javascript"></script>
</body>
</html>
