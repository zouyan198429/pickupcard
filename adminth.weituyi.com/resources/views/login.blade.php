@extends('layouts.login')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('bodyclass') class="bg-primary" @endsection

@section('content')
    <div class="page page-login text-center">
        <div class="panel">
            <div class="panel-body">
                <div class="logo">
                    <a href="#">农产品质量可追溯营销系统</a>
                </div>
                <form action="#"  method="post"  id="addForm" >
                    <div class="form-group">
                        <input type="text" name="account_username" class="form-control" placeholder="帐号/手机号">
                    </div>
                    <div class="form-group">
                        <input type="password"  name="account_password"  class="form-control" placeholder="密码">
                    </div>
                    <div class="form-group" style="display:none;">
                        <input type="text" class="form-control" placeholder="验证码">
                    </div>
                    <button type="button"  id="submitBtn" {{--onclick="window.open('{{ url('/') }}')"--}}   class="btn btn-lg btn-primary btn-block">登录</button>
                </form>
                <div class="login-helpers text-left">
                    <br/> 没有账户？ <a href="{{ url('reg') }}" >马上注册</a>
                </div>
            </div>
        </div>
    <footer class="page-copyright page-copyright-inverse text-center">
        <p>杨凌沃太农业咨询有限公司</p>
        <p>© 2018. All RIGHT RESERVED.</p>
    </footer>
    </div>
@endsection


@push('footscripts')

<script>
    var LOGIN_URL = '{{ url('api/accounts/ajax_login') }}';
    var INDEX_URL = "{{url('/')}}";
</script>
<script src="{{ asset('/js/lanmu/login.js') }}"  type="text/javascript"></script>
@endpush