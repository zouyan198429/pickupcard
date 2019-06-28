<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>农场管理后台</title>
    @include('public.dynamic_list_head')
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