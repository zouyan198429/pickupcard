
    <!-- BaiduTemplate -->
    @include('public.table_page_baidu_template')
    <!-- 模态框（Modal） -->
    @include('public.alert_layer')


            <!-- /.main-container -->

    <!-- basic scripts -->

    <!--[if !IE]> -->
    <script src="{{ asset('/assets/js/jquery-2.1.4.min.js') }}"></script>
    <!-- <![endif]-->

    <!--[if IE]>
    <script src="{{ asset('assets/js/jquery-1.11.3.min.js') }}"></script>
    <![endif]-->
    <script type="text/javascript">
        if('ontouchstart' in document.documentElement) document.write("<script src='/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
    </script>
    {{--
    <script src="{{ asset('/assets/js/bootstrap.min.js') }}"></script>
    --}}
    <!-- page specific plugin scripts -->
    <script src="{{ asset('/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('/assets/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('/assets/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('/assets/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('/assets/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('/assets/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('/assets/js/dataTables.select.min.js') }}"></script>

    {{--
    <!-- ace scripts -->
    <script src="{{ asset('/assets/js/ace-elements.min.js') }}"></script>
    <script src="{{ asset('/assets/js/ace.min.js') }}"></script>
    --}}
    <!-- 新加入 begin-->
    <script src="{{ asset('/assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('/assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('/static/js/data_tables.js') }}"></script>

    <!-- 数据验证-->
    <script src="{{ asset('/static/js/validation/1.15.0/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('/static/js/validation/1.15.0/localization/messages_zh.js') }}"></script>
    <!-- 弹出层-->
    <!-- BaiduTemplate-->
    <script src="{{ asset('/static/js/baiduTemplate.js') }}"></script>
    <!-- 弹出层-->
    <script src="{{ asset('/static/js/layer/layer.js') }}"></script>
    <!-- 公共方法-->
    <script src="{{ asset('/static/js/common.js') }}"></script>
    <!-- ajax翻页方法-->
    <script src="{{ asset('/static/js/ajaxpage.js') }}"></script>
    <!-- 新加入 end-->
