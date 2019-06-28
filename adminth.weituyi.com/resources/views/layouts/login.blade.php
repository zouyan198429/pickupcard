<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>农场管理后台</title>
{{--111 @include('public.dynamic_list_head') --}}
<!-- zui css -->
    <link rel="stylesheet" href="{{asset('dist/css/zui.min.css')}}">
    <link rel="stylesheet" href="{{asset('dist/theme/blue.css')}}">
    <!-- app css -->
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <!-- jquery js -->
    <script src="{{asset('dist/lib/jquery/jquery.js')}}"></script>
    {{-- 本页单独head使用 --}}
    @stack('headscripts')
    @include('piwik')
</head>
<body @yield('bodyclass')>

{{-- 主操作区域内容 --}}
@yield('content')
</body>
</html>
<!-- 弹出层-->
<script src="{{ asset('/static/js/custom/layer/layer.js') }}"></script>
<!-- 公共方法-->
<script src="{{ asset('/static/js/custom/common.js') }}"></script>
<!-- ajax翻页方法-->
<script src="{{ asset('/static/js/custom/ajaxpage.js') }}"></script>
<!-- zui js -->
<script src="{{asset('dist/js/zui.min.js')}}"></script>
<!-- app js -->
<script src="{{asset('js/app.js')}}"></script>
{{-- 本页单独foot使用,可以</html>结尾后可以写的内容，如js引入或操作 --}}
@stack('footscripts')