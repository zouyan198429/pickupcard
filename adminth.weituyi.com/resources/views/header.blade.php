
<nav class="navbar navbar-fixed-top bg-primary">
    <div class="navbar-header">
        <a class="navbar-toggle" href="javascript:;" data-toggle="collapse" data-target=".navbar-collapse"><i class="icon icon-th-large"></i></a>
        <a class="sidebar-toggle" href="javascript:;" data-toggle="push-menu"><i class="icon icon-bars"></i></a>
        <a class="navbar-brand" href="#">
            <span class="logo"> 农产品质量可追溯管理</span>
            <span class="logo-mini">后台</span>
        </a>
    </div>
    <div class="collapse navbar-collapse">
        <div class="container-fluid">
            <ul class="nav navbar-nav">
                <li><a href="javascript:;" data-toggle="push-menu"><i class="icon icon-bars"></i></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                {{--
                <li>
                    <a href="{{ url('sys/pay') }}">
                                    <span>
                                        <i class="icon icon-yen"></i> 在线支付
                                    </span>
                    </a>
                </li>
                --}}
                <li>
                    <a href="{{ url('sys/help') }}">
                                    <span>
                                        <i class="icon icon-location-arrow"></i> 帮助中心
                                    </span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="javascript:;" data-toggle="dropdown"><i class="icon icon-user"></i> 管理员 <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ url('accounts/set') }}">资料设置</a></li>
                        <li><a href="{{ url('accounts/mypass') }}">修改密码</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ url('logout') }}">退出</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>