<section class="sidebar">
    <ul class="sidebar-menu" data-widget="tree">
<!--         <li class="header">主要菜单</li>
 -->        <li class="active">
            <a href="{{ url('/') }}">
                <i class="icon icon-home"></i>
                <span>首页</span>
                            <span class="pull-right-container">
                            </span>
            </a>
        </li>
        <?php
        // $userInfo = $_SESSION['userInfo']?? [];
        $userInfo =  \App\Services\Tool::getSession(null, true, config('public.sessionKey'), config('public.sessionRedisTye'));

        $proUnits = $userInfo['proUnits'] ?? [];
        foreach($proUnits as $proUnit){
            $tem_unit_id = $proUnit['unit_id'] ?? 0;
            if((!is_numeric($tem_unit_id)) || $tem_unit_id < 0){ continue;}
            ?>
            <li class="treeview" >
                <a href="javascript:;">
                    <i class="icon icon-bars"></i>
                    <span><?php echo $proUnit['pro_input_name'];?></span>
                                <span class="pull-right-container">
                                    <i class="icon icon-angle-left"></i>
                                </span>
                </a>
                <ul class="treeview-menu" style="display:block;">
                    <li><a href="{{ url('handles/' . $tem_unit_id) }}"><i class="icon icon-circle-blank"></i>农事记录</a></li>
                    <li><a href="{{ url('inputs/' . $tem_unit_id) }}"><i class="icon icon-circle-blank"></i>生产投入品</a></li>
                    <li><a href="{{ url('report/' . $tem_unit_id) }}"><i class="icon icon-circle-blank"></i>检测报告</a></li>
<!--                     <li><a href="{{ url('tinyweb/' . $tem_unit_id) }}"><i class="icon icon-circle-blank"></i>微站设置</a></li>
 -->
                   {{-- <li><a href="{{ url('security_label/' . $tem_unit_id) }}"><i class="icon icon-circle-blank"></i>防伪标签</a></li>--}}
                    <li><a href="{{ url('comment/' . $tem_unit_id) }}"><i class="icon icon-circle-blank"></i>用户反馈</a></li>
                    <li><a href="<?php echo config('public.tinyWebURL') . $tem_unit_id;?>" target="_blank"><i class="icon icon-circle-blank"></i>前端预览</a></li>
                </ul>
            </li>

            <hr style="border-color:#444; height:1px; padding:0; margin:0; " />

            <?php
        }
        ?>
        <li>
            <a href="{{ url('productunit/') }}">
                <i class="icon icon-list-ul"></i>
                <span>生产单元管理</span>
                            <span class="pull-right-container">
                            </span>
            </a>
        </li>
        <li>
            <a href="{{ url('company/') }}">
                <i class="icon icon-credit"></i>
                <span>企业信息</span>
                            <span class="pull-right-container">
                            </span>
            </a>
        </li>
        <li>
            <a href="{{ url('photo/') }}">
                <i class="icon icon-picture"></i>
                <span>企业相册</span>
                            <span class="pull-right-container">
                            </span>
            </a>
        </li>
        <li>
            <a href="{{ url('accounts/') }}">
                <i class="icon icon-group"></i>
                <span>子帐号管理</span>
                            <span class="pull-right-container">
                            </span>
            </a>
        </li>
        {{--
        <li>
            <a href="{{ url('count/') }}">
                <i class="icon icon-area-chart"></i>
                <span>数据统计</span>
                            <span class="pull-right-container">
                            </span>
            </a>
        </li>
        --}}
    </ul>
</section>