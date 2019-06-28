<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>layuiAdmin std - 通用后台管理模板系统（iframe标准版）</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/css/layui.css')}}" media="all">
  <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/style/admin.css')}}" media="all">
  
  <script>
  /^http(s*):\/\//.test(location.href) || alert('请先部署到 localhost 下再访问');
  </script>
</head>
<body class="layui-layout-body">
  
  <div id="LAY_app">
    <div class="layui-layout layui-layout-admin">
      <div class="layui-header">
        <!-- 头部区域 -->
        <ul class="layui-nav layui-layout-left">
          <li class="layui-nav-item layadmin-flexible" lay-unselect>
            <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
              <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
            </a>
          </li>
          <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="http://www.layui.com/admin/" target="_blank" title="前台">
              <i class="layui-icon layui-icon-website"></i>
            </a>
          </li>
          <li class="layui-nav-item" lay-unselect>
            <a href="javascript:;" layadmin-event="refresh" title="刷新">
              <i class="layui-icon layui-icon-refresh-3"></i>
            </a>
          </li>
          <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <input type="text" placeholder="搜索..." autocomplete="off" class="layui-input layui-input-search" layadmin-event="serach" lay-action="{{ url('layui/template/search') }}?keywords=">
          </li>
        </ul>
        <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">
          
          <li class="layui-nav-item" lay-unselect>
            <a lay-href="{{ url('layui/app/message/index') }}" layadmin-event="message" lay-text="消息中心">
              <i class="layui-icon layui-icon-notice"></i>  
              
              <!-- 如果有新消息，则显示小圆点 -->
              <span class="layui-badge-dot"></span>
            </a>
          </li>
          <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;" layadmin-event="theme">
              <i class="layui-icon layui-icon-theme"></i>
            </a>
          </li>
          <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;" layadmin-event="note">
              <i class="layui-icon layui-icon-note"></i>
            </a>
          </li>
          <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;" layadmin-event="fullscreen">
              <i class="layui-icon layui-icon-screen-full"></i>
            </a>
          </li>
          <li class="layui-nav-item" lay-unselect>
            <a href="javascript:;">
              <cite>贤心</cite>
            </a>
            <dl class="layui-nav-child">
              <dd><a lay-href="{{ url('layui/set/user/info') }}">基本资料</a></dd>
              <dd><a lay-href="{{ url('layui/set/user/password') }}">修改密码</a></dd>
              <hr>
              <dd layadmin-event="logout" style="text-align: center;"><a>退出</a></dd>
            </dl>
          </li>
          
          <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;" layadmin-event="about"><i class="layui-icon layui-icon-more-vertical"></i></a>
          </li>
          <li class="layui-nav-item layui-show-xs-inline-block layui-hide-sm" lay-unselect>
            <a href="javascript:;" layadmin-event="more"><i class="layui-icon layui-icon-more-vertical"></i></a>
          </li>
        </ul>
      </div>
      
      <!-- 侧边菜单 -->
      <div class="layui-side layui-side-menu">
        <div class="layui-side-scroll">
          <div class="layui-logo" lay-href="{{ url('layui/home/console') }}">
            <span>layuiAdmin</span>
          </div>
          
          <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu" lay-filter="layadmin-system-side-menu">
            <li data-name="home" class="layui-nav-item layui-nav-itemed">
              <a href="javascript:;" lay-tips="主页" lay-direction="2">
                <i class="layui-icon layui-icon-home"></i>
                <cite>主页</cite>
              </a>
              <dl class="layui-nav-child">
                <dd data-name="console" class="layui-this">
                  <a lay-href="{{ url('layui/home/console') }}">控制台</a>
                </dd>
                <dd data-name="console">
                  <a lay-href="{{ url('layui/home/homepage1') }}">主页一</a>
                </dd>
                <dd data-name="console">
                  <a lay-href="{{ url('layui/home/homepage2') }}">主页二</a>
                </dd>
              </dl>
            </li>
            <li data-name="component" class="layui-nav-item">
              <a href="javascript:;" lay-tips="组件" lay-direction="2">
                <i class="layui-icon layui-icon-component"></i>
                <cite>组件</cite>
              </a>
              <dl class="layui-nav-child">
                <dd data-name="grid">
                  <a href="javascript:;">栅格</a>
                  <dl class="layui-nav-child">
                    <dd data-name="list"><a lay-href="{{ url('layui/component/grid/list') }}">等比例列表排列</a></dd>
                    <dd data-name="mobile"><a lay-href="{{ url('layui/component/grid/mobile') }}">按移动端排列</a></dd>
                    <dd data-name="mobile-pc"><a lay-href="{{ url('layui/component/grid/mobile-pc') }}">移动桌面端组合</a></dd>
                    <dd data-name="all"><a lay-href="{{ url('layui/component/grid/all') }}">全端复杂组合</a></dd>
                    <dd data-name="stack"><a lay-href="{{ url('layui/component/grid/stack') }}">低于桌面堆叠排列</a></dd>
                    <dd data-name="speed-dial"><a lay-href="{{ url('layui/component/grid/speed-dial') }}">九宫格</a></dd>
                  </dl>
                </dd>
                <dd data-name="button">
                  <a lay-href="{{ url('layui/component/button/index') }}">按钮</a>
                </dd>
                <dd data-name="form">
                  <a href="javascript:;">表单</a>
                  <dl class="layui-nav-child">
                    <dd><a lay-href="{{ url('layui/component/form/element') }}">表单元素</a></dd>
                    <dd><a lay-href="{{ url('layui/component/form/group') }}">表单组合</a></dd>
                  </dl>
                </dd>
                <dd data-name="nav">
                  <a lay-href="{{ url('layui/component/nav/index') }}">导航</a>
                </dd>
                <dd data-name="tabs">
                  <a lay-href="{{ url('layui/component/tabs/index') }}">选项卡</a>
                </dd>
                <dd data-name="progress">
                  <a lay-href="{{ url('layui/component/progress/index') }}">进度条</a>
                </dd>
                <dd data-name="panel"> 
                  <a lay-href="{{ url('layui/component/panel/index') }}">面板</a>
                </dd>
                <dd data-name="badge"> 
                  <a lay-href="{{ url('layui/component/badge/index') }}">徽章</a>
                </dd>
                <dd data-name="timeline"> 
                  <a lay-href="{{ url('layui/component/timeline/index') }}">时间线</a>
                </dd>
                <dd data-name="anim"> 
                  <a lay-href="{{ url('layui/component/anim/index') }}">动画</a>
                </dd>
                <dd data-name="auxiliar"> 
                  <a lay-href="{{ url('layui/component/auxiliar/index') }}">辅助</a>
                </dd>
                <dd data-name="layer"> 
                  <a href="javascript:;">通用弹层<span class="layui-nav-more"></span></a>  
                  <dl class="layui-nav-child">  
                    <dd data-name="list"> 
                      <a lay-href="{{ url('layui/component/layer/list') }}" lay-text="layer 功能演示">功能演示</a>
                    </dd>  
                    <dd data-name="special-demo"> 
                      <a lay-href="{{ url('layui/component/layer/special-demo') }}" lay-text="layer 特殊示例">特殊示例</a>
                    </dd>  
                    <dd data-name="theme"> 
                      <a lay-href="{{ url('layui/component/layer/theme') }}" lay-text="layer 风格定制">风格定制</a>
                    </dd>  
                  </dl>  
                </dd>
                <dd data-name="laydate"> 
                  <a href="javascript:;">日期时间</a>
                  <dl class="layui-nav-child">  
                    <dd data-name="demo1"> 
                      <a lay-href="{{ url('layui/component/laydate/demo1') }}" lay-text="layDate 功能演示一">功能演示一</a>
                    </dd>
                    <dd data-name="demo2"> 
                      <a lay-href="{{ url('layui/component/laydate/demo2') }}" lay-text="layDate 功能演示二">功能演示二</a>
                    </dd>
                    <dd data-name="theme"> 
                      <a lay-href="{{ url('layui/component/laydate/theme') }}" lay-text="layDate 设定主题">设定主题</a>
                    </dd>
                    <dd data-name="special-demo"> 
                      <a lay-href="{{ url('layui/component/laydate/special-demo') }}" lay-text="layDate 特殊示例">特殊示例</a>
                    </dd>  
                  </dl>  
                </dd>
                <dd data-name="table-static"> 
                  <a lay-href="{{ url('layui/component/table/static') }}">静态表格</a>
                </dd>
                <dd data-name="table"> 
                  <a href="javascript:;">数据表格</a>
                  <dl class="layui-nav-child">  
                    <dd data-name="simple"> 
                      <a lay-href="{{ url('layui/component/table/simple') }}" lay-text="">简单数据表格</a>
                    </dd>
                    <dd data-name="auto"> 
                      <a lay-href="{{ url('layui/component/table/auto') }}" lay-text="">列宽自动分配</a>
                    </dd>
                    <dd data-name="data"> 
                      <a lay-href="{{ url('layui/component/table/data') }}" lay-text="">赋值已知数据</a>
                    </dd>
                    <dd data-name="tostatic"> 
                      <a lay-href="{{ url('layui/component/table/tostatic') }}" lay-text="">转化静态表格</a>
                    </dd>
                    <dd data-name="page"> 
                      <a lay-href="{{ url('layui/component/table/page') }}" lay-text="">开启分页</a>
                    </dd>
                    <dd data-name="resetPage"> 
                      <a lay-href="{{ url('layui/component/table/resetPage') }}" lay-text="">自定义分页</a>
                    </dd>
                    <dd data-name="toolbar"> 
                      <a lay-href="{{ url('layui/component/table/toolbar') }}" lay-text="">开启头部工具栏</a>
                    </dd>
                    <dd data-name="totalRow"> 
                      <a lay-href="{{ url('layui/component/table/totalRow') }}" lay-text="">开启合计行</a>
                    </dd>
                    <dd data-name="height"> 
                      <a lay-href="{{ url('layui/component/table/height') }}" lay-text="">高度最大适应</a>
                    </dd>
                    <dd data-name="checkbox"> 
                      <a lay-href="{{ url('layui/component/table/checkbox') }}" lay-text="">开启复选框</a>
                    </dd>
                    <dd data-name="radio"> 
                      <a lay-href="{{ url('layui/component/table/radio') }}" lay-text="">开启单选框</a>
                    </dd>
                    <dd data-name="cellEdit"> 
                      <a lay-href="{{ url('layui/component/table/cellEdit') }}" lay-text="">开启单元格编辑</a>
                    </dd>
                    <dd data-name="form"> 
                      <a lay-href="{{ url('layui/component/table/form') }}" lay-text="">加入表单元素</a>
                    </dd>
                    <dd data-name="style"> 
                      <a lay-href="{{ url('layui/component/table/style') }}" lay-text="">设置单元格样式</a>
                    </dd>
                    <dd data-name="fixed"> 
                      <a lay-href="{{ url('layui/component/table/fixed') }}" lay-text="">固定列</a>
                    </dd>
                    <dd data-name="operate"> 
                      <a lay-href="{{ url('layui/component/table/operate') }}" lay-text="">数据操作</a>
                    </dd>
                    <dd data-name="parseData"> 
                      <a lay-href="{{ url('layui/component/table/parseData') }}" lay-text="">解析任意数据格式</a>
                    </dd>
                    <dd data-name="onrow"> 
                      <a lay-href="{{ url('layui/component/table/onrow') }}" lay-text="">监听行事件</a>
                    </dd>
                    <dd data-name="reload">
                      <a lay-href="{{ url('layui/component/table/reload') }}" lay-text="">数据表格的重载</a>
                    </dd>
                    <dd data-name="initSort"> 
                      <a lay-href="{{ url('layui/component/table/initSort') }}" lay-text="">设置初始排序</a>
                    </dd>
                    <dd data-name="cellEvent"> 
                      <a lay-href="{{ url('layui/component/table/cellEvent') }}" lay-text="">监听单元格事件</a>
                    </dd>
                    <dd data-name="thead"> 
                      <a lay-href="{{ url('layui/component/table/thead') }}" lay-text="">复杂表头</a>
                    </dd>
                  </dl>
                </dd>
                <dd data-name="laypage"> 
                  <a href="javascript:;">分页</a>  
                  <dl class="layui-nav-child">  
                    <dd data-name="demo1"> 
                      <a lay-href="{{ url('layui/component/laypage/demo1') }}" lay-text="layPage 功能演示一">功能演示一</a>
                    </dd>
                    <dd data-name="demo2"> 
                      <a lay-href="{{ url('layui/component/laypage/demo2') }}" lay-text="layPage 功能演示二">功能演示二</a>
                    </dd> 
                  </dl>  
                </dd>
                <dd data-name="upload"> 
                  <a href="javascript:;">上传</a>  
                  <dl class="layui-nav-child">  
                    <dd data-name="demo1"> 
                      <a lay-href="{{ url('layui/component/upload/demo1') }}" lay-text="上传功能演示一">功能演示一</a>
                    </dd>
                    <dd data-name="demo2"> 
                      <a lay-href="{{ url('layui/component/upload/demo2') }}" lay-text="上传功能演示二">功能演示二</a>
                    </dd> 
                  </dl>  
                </dd>
                <dd data-name="colorpicker">
                  <a lay-href="{{ url('layui/component/colorpicker/index') }}">颜色选择器</a>
                </dd>
                <dd data-name="slider">
                  <a lay-href="{{ url('layui/component/slider/index') }}">滑块组件</a>
                </dd>
                <dd data-name="rate">
                  <a lay-href="{{ url('layui/component/rate/index') }}">评分</a>
                </dd>
                <dd data-name="carousel"> 
                  <a lay-href="{{ url('layui/component/carousel/index') }}">轮播</a>
                </dd>
                <dd data-name="flow"> 
                  <a lay-href="{{ url('layui/component/flow/index') }}">流加载</a>
                </dd>
                <dd data-name="util"> 
                  <a lay-href="{{ url('layui/component/util/index') }}">工具</a>
                </dd>
                <dd data-name="code"> 
                  <a lay-href="{{ url('layui/component/code/index') }}">代码修饰</a>
                </dd>
              </dl>
            </li>
            <li data-name="template" class="layui-nav-item">
              <a href="javascript:;" lay-tips="页面" lay-direction="2">
                <i class="layui-icon layui-icon-template"></i>
                <cite>页面</cite>
              </a>
              <dl class="layui-nav-child">
                <dd><a lay-href="{{ url('layui/template/personalpage') }}">个人主页</a></dd>
                <dd><a lay-href="{{ url('layui/template/addresslist') }}">通讯录</a></dd>
                <dd><a lay-href="{{ url('layui/template/caller') }}">客户列表</a></dd>
                <dd><a lay-href="{{ url('layui/template/goodslist') }}">商品列表</a></dd>
                <dd><a lay-href="{{ url('layui/template/msgboard') }}">留言板</a></dd>
                <dd><a lay-href="{{ url('layui/template/search') }}">搜索结果</a></dd>
                <dd><a href="{{ url('layui/user/reg') }}" target="_blank">注册</a></dd>
                <dd><a href="{{ url('layui/user/login') }}" target="_blank">登入</a></dd>
                <dd><a href="{{ url('layui/user/forget') }}" target="_blank">忘记密码</a></dd>
                <dd><a lay-href="{{ url('layui/template/tips/404') }}">404页面不存在</a></dd>
                <dd><a lay-href="{{ url('layui/template/tips/error') }}">错误提示</a></dd>
                <dd><a lay-href="//www.baidu.com/">百度一下</a></dd>
                <dd><a lay-href="//www.layui.com/">layui官网</a></dd>
                <dd><a lay-href="//www.layui.com/admin/">layuiAdmin官网</a></dd>
              </dl>
            </li>
            <li data-name="app" class="layui-nav-item">
              <a href="javascript:;" lay-tips="应用" lay-direction="2">
                <i class="layui-icon layui-icon-app"></i>
                <cite>应用</cite>
              </a>
              <dl class="layui-nav-child">
                
                <dd data-name="content">
                  <a href="javascript:;">内容系统</a>
                  <dl class="layui-nav-child">
                    <dd data-name="list"><a lay-href="{{ url('layui/app/content/list') }}">文章列表</a></dd>
                    <dd data-name="tags"><a lay-href="{{ url('layui/app/content/tags') }}">分类管理</a></dd>
                    <dd data-name="comment"><a lay-href="{{ url('layui/app/content/comment') }}">评论管理</a></dd>
                  </dl>
                </dd>
                <dd data-name="forum">
                  <a href="javascript:;">社区系统</a>
                  <dl class="layui-nav-child">
                    <dd data-name="list"><a lay-href="{{ url('layui/app/forum/list') }}">帖子列表</a></dd>
                    <dd data-name="replys"><a lay-href="{{ url('layui/app/forum/replys') }}">回帖列表</a></dd>
                  </dl>
                </dd>
                <dd>
                  <a lay-href="{{ url('layui/app/message/index') }}">消息中心</a>
                </dd>
                <dd data-name="workorder">
                  <a lay-href="{{ url('layui/app/workorder/list') }}">工单系统</a>
                </dd>
              </dl>
            </li>
            <li data-name="senior" class="layui-nav-item">
              <a href="javascript:;" lay-tips="高级" lay-direction="2">
                <i class="layui-icon layui-icon-senior"></i>
                <cite>高级</cite>
              </a>
              <dl class="layui-nav-child">
                <dd>
                  <a layadmin-event="im">LayIM 通讯系统</a>  
                </dd>
                <dd data-name="echarts">
                  <a href="javascript:;">Echarts集成</a>
                  <dl class="layui-nav-child">
                    <dd><a lay-href="{{ url('layui/senior/echarts/line') }}">折线图</a></dd>
                    <dd><a lay-href="{{ url('layui/senior/echarts/bar') }}">柱状图</a></dd>
                    <dd><a lay-href="{{ url('layui/senior/echarts/map') }}">地图</a></dd>
                  </dl>
                </dd>
              </dl>
            </li>
            <li data-name="user" class="layui-nav-item">
              <a href="javascript:;" lay-tips="用户" lay-direction="2">
                <i class="layui-icon layui-icon-user"></i>
                <cite>用户</cite>
              </a>
              <dl class="layui-nav-child">
                <dd>
                  <a lay-href="{{ url('layui/user/user/list') }}">网站用户</a>
                </dd>
                <dd>
                  <a lay-href="{{ url('layui/user/administrators/list') }}">后台管理员</a>
                </dd>
                <dd>
                  <a lay-href="{{ url('layui/user/administrators/role') }}">角色管理</a>
                </dd>
              </dl>
            </li>
            <li data-name="set" class="layui-nav-item">
              <a href="javascript:;" lay-tips="设置" lay-direction="2">
                <i class="layui-icon layui-icon-set"></i>
                <cite>设置</cite>
              </a>
              <dl class="layui-nav-child">
                <dd class="layui-nav-itemed">
                  <a href="javascript:;">系统设置</a>
                  <dl class="layui-nav-child">
                    <dd><a lay-href="{{ url('layui/set/system/website') }}">网站设置</a></dd>
                    <dd><a lay-href="{{ url('layui/set/system/email') }}">邮件服务</a></dd>
                  </dl>
                </dd>
                <dd class="layui-nav-itemed">
                  <a href="javascript:;">我的设置</a>
                  <dl class="layui-nav-child">
                    <dd><a lay-href="{{ url('layui/set/user/info') }}">基本资料</a></dd>
                    <dd><a lay-href="{{ url('layui/set/user/password') }}">修改密码</a></dd>
                  </dl>
                </dd>
              </dl>
            </li>
            <li data-name="get" class="layui-nav-item">
              <a href="javascript:;" lay-href="//www.layui.com/admin/#get" lay-tips="授权" lay-direction="2">
                <i class="layui-icon layui-icon-auz"></i>
                <cite>授权</cite>
              </a>
            </li>
          </ul>
        </div>
      </div>

      <!-- 页面标签 -->
      <div class="layadmin-pagetabs" id="LAY_app_tabs">
        <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
        <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
        <div class="layui-icon layadmin-tabs-control layui-icon-down">
          <ul class="layui-nav layadmin-tabs-select" lay-filter="layadmin-pagetabs-nav">
            <li class="layui-nav-item" lay-unselect>
              <a href="javascript:;"></a>
              <dl class="layui-nav-child layui-anim-fadein">
                <dd layadmin-event="closeThisTabs"><a href="javascript:;">关闭当前标签页</a></dd>
                <dd layadmin-event="closeOtherTabs"><a href="javascript:;">关闭其它标签页</a></dd>
                <dd layadmin-event="closeAllTabs"><a href="javascript:;">关闭全部标签页</a></dd>
              </dl>
            </li>
          </ul>
        </div>
        <div class="layui-tab" lay-unauto lay-allowClose="true" lay-filter="layadmin-layout-tabs">
          <ul class="layui-tab-title" id="LAY_app_tabsheader">
            <li lay-id="{{ url('layui/home/console') }}" lay-attr="{{ url('layui/home/console') }}" class="layui-this"><i class="layui-icon layui-icon-home"></i></li>
          </ul>
        </div>
      </div>
      
      
      <!-- 主体内容 -->
      <div class="layui-body" id="LAY_app_body">
        <div class="layadmin-tabsbody-item layui-show">
          <iframe src="{{ url('layui/home/console') }}" frameborder="0" class="layadmin-iframe"></iframe>
        </div>
      </div>
      
      <!-- 辅助元素，一般用于移动设备下遮罩 -->
      <div class="layadmin-body-shade" layadmin-event="shade"></div>
    </div>
  </div>

  <script src="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/layui.js')}}"></script>
  <script>
  layui.config({
    base: '/layui-admin-v1.2.1/src/layuiadmin/' //静态资源所在路径
  }).extend({
    index: 'lib/index' //主入口模块
  }).use('index');
  </script>
  
  <!-- 百度统计 -->
  <script>
  var _hmt = _hmt || [];
  (function() {
    var hm = document.createElement("script");
    hm.src = "https://hm.baidu.com/hm.js?d214947968792b839fd669a4decaaffc";
    var s = document.getElementsByTagName("script")[0]; 
    s.parentNode.insertBefore(hm, s);
  })();
  </script>
</body>
</html>


