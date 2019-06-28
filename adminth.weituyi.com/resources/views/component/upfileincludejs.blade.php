<script>
    {{--需要的js代码可以后面再这里再加--}}
    {{--var PIC_DEL_URL = '{{ url('api/upload/ajax_del') }}';--}}
</script>
<script src="{{ asset('/js/common/uploadpic.js') }}"  type="text/javascript"></script>
<link href="{{asset('dist/lib/uploader/zui.uploader.min.css') }}" rel="stylesheet">
<script src="{{asset('dist/lib/uploader/zui.uploader.min.js') }}"></script>{{--此文件引用一次就可以了--}}