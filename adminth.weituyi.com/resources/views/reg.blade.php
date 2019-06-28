@extends('layouts.login')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('bodyclass') class="bg-primary" @endsection
@section('content')
    <div class="page page-reg text-center">
        <div class="panel">
            <div class="panel-body">
                <div class="logo">
                    <a href="#">企业用户注册</a>
                </div>
                <div class="login-helpers text-left">
                    以下信息均为必填项,帐号必须是手机号
                </div>
                <form action="#" method="post"  id="addForm">
                    <div class="form-group">
                        <input type="text" name="company_mobile" class="form-control" placeholder="手机号">
                    </div>
                    <div class="form-group">
                        <input type="password" name="account_password" class="form-control" placeholder="密码">
                    </div>
                    <div class="form-group">
                        <input type="password" name="sure_password"  class="form-control" placeholder="确认密码">
                    </div>
                    <div class="form-group">
                        <input type="text" name="real_name"  class="form-control" placeholder="真实姓名">
                    </div>
                    <div class="form-group">
                        <input type="text" name="company_name" class="form-control" placeholder="企业名称">
                    </div>
                    <div class="form-group">
                        <div class="row">
                            @include('public.area_select.area_select', ['province_id' => 'province_id','city_id' => 'city_id','area_id' => 'area_id'])

                        </div>
                    </div>
                    <div class="form-group">
                        <input name="company_addr" type="text" class="form-control" placeholder="详细地址">
                    </div>
                    <button type="button"   id="submitBtn" {{--onclick="window.open('{{ url('login') }}')" --}}   class="btn btn-lg btn-primary btn-block">注册</button>
                </form>
                <div class="login-helpers text-left">
                    <br> 已经有账户？ <a href="{{ url('login') }}" >马上登录</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footscripts')
    <!-- BaiduTemplate -->
    {{--111 @include('public.table_page_baidu_template') --}}
<!-- BaiduTemplate-->
<script src="{{ asset('/static/js/custom/baiduTemplate.js') }}"></script>
<script>
    var REG_URL = '{{ url('api/accounts/ajax_reg') }}';
    var LOGIN_URL = "{{url('login')}}";

</script>
<script src="{{ asset('/js/lanmu/reg.js') }}"  type="text/javascript"></script>
@endpush