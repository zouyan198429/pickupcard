@extends('layouts.login')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('bodyclass') @endsection

@section('content')
    <div class="page page-error text-center">
        <header>
            <h1>404</h1>
            <p>您要的页面找不到了！</p>
        </header>
        <a class="btn btn-primary btn-round" href="{{ url('/') }}" style="margin-right:10px;">回到首页</a>
        <a class="btn btn-round" href="javascript:history.back();">返回上页</a>
        <footer class="page-copyright">
            <p>WEBSITE BY 莫非</p>
            <p>© 2018. All RIGHT RESERVED.</p>
        </footer>
    </div>
@endsection

@push('footscripts')
@endpush