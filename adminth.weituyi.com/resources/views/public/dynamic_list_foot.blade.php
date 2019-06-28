
    <!-- BaiduTemplate -->
    {{--111  @include('public.table_page_baidu_template')--}}

    <!-- 模态框（Modal）还需要开 /bootstrap.min.css  和  bootstrap.min.js -->
    {{--@include('public.alert_layer')--}}
    <!-- /.main-container --> 
    <!-- basic scripts -->
    {{--111
    <!--[if !IE]> -->

    <script src="{{ asset('/static/js/jquery-2.1.4.min.js') }}"></script>
    <!-- <![endif]-->

    <!--[if IE]>
    <script src="{{ asset('/static/js/jquery-1.11.3.min.js') }}"></script>
    <![endif]-->
    <script type="text/javascript">
        if('ontouchstart' in document.documentElement) document.write("<script src='/static/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
    </script>
     --}}
    {{-- <script src="{{ asset('/static/js/bootstrap.min.js') }}"></script>--}}

    <!-- page specific plugin scripts -->
    <script src="{{ asset('/static/js/jquery.dataTables.min.js') }}"></script>
    {{--111
    <script src="{{ asset('/static/js/jquery.dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('/static/js/dataTables.buttons.min.js') }}"></script>

    <script src="{{ asset('/static/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('/static/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('/static/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('/static/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('/static/js/dataTables.select.min.js') }}"></script>
     --}}

    {{--
    <!-- ace scripts -->
    <script src="{{ asset('/static/js/ace-elements.min.js') }}"></script>
    <script src="{{ asset('/static/js/ace.min.js') }}"></script>
    --}}
    <!-- 新加入 begin-->
    <script src="{{ asset('/static/js/moment.min.js') }}"></script>
    {{--111
    <script src="{{ asset('/static/js/bootstrap-datetimepicker.min.js') }}"></script>
    --}}
    <script src="{{ asset('/static/js/jquery-ui.min.js') }}"></script>

    <script src="{{ asset('/static/js/custom/data_tables.js') }}"></script>

    <!-- 数据验证-->
    {{--111
    <script src="{{ asset('/static/js/custom/validation/1.15.0/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('/static/js/custom/validation/1.15.0/localization/messages_zh.js') }}"></script>
     --}}
    <!-- 弹出层-->
    <!-- BaiduTemplate-->
    <script src="{{ asset('/static/js/custom/baiduTemplate.js') }}"></script>
    <!-- 弹出层-->
    <script src="{{ asset('/static/js/custom/layer/layer.js') }}"></script>
    <!-- 公共方法-->
    <script src="{{ asset('/static/js/custom/common.js') }}"></script>
    <!-- ajax翻页方法-->
    <script src="{{ asset('/static/js/custom/ajaxpage.js') }}"></script>
    <!-- 新加入 end-->