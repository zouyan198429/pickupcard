@extends('layouts.huawu')

@push('headscripts')
    {{--  本页单独使用 --}}
    <link href="{{asset('datetimepicker/bootstrap3/css/bootstrap.min.css')}}" rel="stylesheet" media="screen">
    <link href="{{asset('datetimepicker/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" media="screen">
@endpush

@section('content')
	<input type="text" name="created_at" value="{{ $created_at or '' }}"  class="form-control form-date" placeholder="选择或者输入一个日期：yyyy-MM">
@endsection


@push('footscripts')
    {{--<script type="text/javascript" src="{{asset('datetimepicker/jquery/jquery-1.8.3.min.js')}}" charset="UTF-8"></script>--}}
    <script type="text/javascript" src="{{asset('datetimepicker/bootstrap3/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('datetimepicker/js/bootstrap-datetimepicker.js')}}" charset="UTF-8"></script>
    <script type="text/javascript" src="{{asset('datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js')}}" charset="UTF-8"></script>
	<script>
        //var SAVE_URL = "{ { url('api/handles/' . $pro_unit_id . '/ajax_save') }}";
        //var LIST_URL = "{ {url('handles/' . $pro_unit_id)}}";
        // 仅选择日期
        $(".form-date").datetimepicker(
            {
                language:  "zh-CN",
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 0,
                minuteStep:1,
                forceParse: 0,
                format: "yyyy-mm-dd hh:ii:ss"
            });


        var SUBMIT_FORM = true;//防止多次点击提交
        $(function(){
            // 九张图片上传
			@include('component.upfileone.piconejsinitincludenine')
            //提交
            $(document).on("click","#submitBtn",function(){
                //var index_query = layer.confirm('您确定提交保存吗？', {
                //    btn: ['确定','取消'] //按钮
                //}, function(){
                ajax_form();
                //    layer.close(index_query);
                // }, function(){
                //});
                return false;
            })

        });

	</script>
@endpush

@push('footlast')
@endpush