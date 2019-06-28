<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});


// Route::get('/test', 'IndexController@test');// 测试
//Route::get('/test2', 'IndexController@test2');// 测试
Route::get('/', 'IndexController@index');// 首页
//Route::get('reg', 'IndexController@reg');// 注册
//Route::get('login', 'IndexController@login');// 登陆
//Route::get('logout', 'IndexController@logout');// 注销
//Route::get('404', 'IndexController@err404');// 404错误

// layuiAdmin
Route::get('layui/index', 'Layui\IndexController@index');// index.html
Route::get('layui/iframe/layer/iframe', 'Layui\Iframe\LayerController@iframe');// iframe/layer/iframe.html layer iframe 示例
Route::get('layui/system/about', 'Layui\SystemController@about');// system/about.html 版本信息 --***
Route::get('layui/system/get', 'Layui\SystemController@get');// system/get.html 授权获得 layuiAdmin --***
Route::get('layui/system/more', 'Layui\SystemController@more');// system/more.html 更多面板的模板 --***
Route::get('layui/system/theme', 'Layui\SystemController@theme');// system/theme.html 主题设置模板 --***
// 主页
Route::get('layui/home/console', 'Layui\HomeController@console');// 控制台 home/console.html
Route::get('layui/home/homepage1', 'Layui\HomeController@homepage1');// 主页一 home/homepage1.html
Route::get('layui/home/homepage2', 'Layui\HomeController@homepage2');// 主页二 home/homepage2.html
// 组件
Route::get('layui/component/laytpl/index', 'Layui\Component\LaytplController@index');// component/laytpl/index.html  模板引擎  --***
// 栅格
Route::get('layui/component/grid/list', 'Layui\Component\GridController@list');// 等比例列表排列 component/grid/list.html
Route::get('layui/component/grid/mobile', 'Layui\Component\GridController@mobile');// 按移动端排列 component/grid/mobile.html
Route::get('layui/component/grid/mobile-pc', 'Layui\Component\GridController@mobilePc');// 移动桌面端组合 component/grid/mobile-pc.html
Route::get('layui/component/grid/all', 'Layui\Component\GridController@all');// 全端复杂组合 component/grid/all.html
Route::get('layui/component/grid/stack', 'Layui\Component\GridController@stack');// 低于桌面堆叠排列 component/grid/stack.html
Route::get('layui/component/grid/speed-dial', 'Layui\Component\GridController@speedDial');// 九宫格 component/grid/speed-dial.html

Route::get('layui/component/button/index', 'Layui\Component\ButtonController@index');// 按钮  component/button/index.html
// 表单
Route::get('layui/component/form/element', 'Layui\Component\FormController@element');// 表单元素 component/form/element.html
Route::get('layui/component/form/group', 'Layui\Component\FormController@group');// 表单组合 component/form/group.html

Route::get('layui/component/nav/index', 'Layui\Component\NavController@index');// 导航  component/nav/index.html
Route::get('layui/component/tabs/index', 'Layui\Component\TabsController@index');// 选项卡 component/tabs/index.html
Route::get('layui/component/progress/index', 'Layui\Component\ProgressController@index');// 进度条 component/progress/index.html
Route::get('layui/component/panel/index', 'Layui\Component\PanelController@index');// 面板 component/panel/index.html
Route::get('layui/component/badge/index', 'Layui\Component\BadgeController@index');// 徽章 component/badge/index.html
Route::get('layui/component/timeline/index', 'Layui\Component\TimelineController@index');// 时间线 component/timeline/index.html
Route::get('layui/component/anim/index', 'Layui\Component\AnimController@index');// 动画 component/anim/index.html
Route::get('layui/component/auxiliar/index', 'Layui\Component\AuxiliarController@index');// 辅助 component/auxiliar/index.html
// 通用弹层
Route::get('layui/component/layer/list', 'Layui\Component\LayerController@list');// 功能演示 component/layer/list.html
Route::get('layui/component/layer/special-demo', 'Layui\Component\LayerController@specialDemo');// 特殊示例 component/layer/special-demo.html
Route::get('layui/component/layer/theme', 'Layui\Component\LayerController@theme');// 风格定制 component/layer/theme.html
// 日期时间
Route::get('layui/component/laydate/index', 'Layui\Component\LaydateController@index');// component/laydate/index.html  日期组件 --***
Route::get('layui/component/laydate/demo1', 'Layui\Component\LaydateController@demo1');// 功能演示一 component/laydate/demo1.html
Route::get('layui/component/laydate/demo2', 'Layui\Component\LaydateController@demo2');// 功能演示二 component/laydate/demo2.html
Route::get('layui/component/laydate/theme', 'Layui\Component\LaydateController@theme');// 设定主题 component/laydate/theme.html
Route::get('layui/component/laydate/special-demo', 'Layui\Component\LaydateController@specialDemo');// 特殊示例 component/laydate/special-demo.html

Route::get('layui/component/table/static', 'Layui\Component\TableController@static');// 静态表格 component/table/static.html
// 数据表格
Route::get('layui/component/table/index', 'Layui\Component\TableController@index');// component/table/index.html  表格 --***
Route::get('layui/component/temp', 'Layui\Component\TableController@temp');// component/temp.html  简单用法 - 数据表格 --***
Route::get('layui/component/table/simple', 'Layui\Component\TableController@simple');// 简单数据表格 component/table/simple.html
Route::get('layui/component/table/auto', 'Layui\Component\TableController@auto');// 列宽自动分配 component/table/auto.html
Route::get('layui/component/table/data', 'Layui\Component\TableController@data');// 赋值已知数据 component/table/data.html
Route::get('layui/component/table/tostatic', 'Layui\Component\TableController@tostatic');// 转化静态表格 component/table/tostatic.html
Route::get('layui/component/table/page', 'Layui\Component\TableController@page');// 开启分页 component/table/page.html
Route::get('layui/component/table/resetPage', 'Layui\Component\TableController@resetPage');// 自定义分页 component/table/resetPage.html
Route::get('layui/component/table/toolbar', 'Layui\Component\TableController@toolbar');// 开启头部工具栏 component/table/toolbar.html
Route::get('layui/component/table/totalRow', 'Layui\Component\TableController@totalRow');// 开启合计行 component/table/totalRow.html
Route::get('layui/component/table/height', 'Layui\Component\TableController@height');// 高度最大适应 component/table/height.html
Route::get('layui/component/table/checkbox', 'Layui\Component\TableController@checkbox');// 开启复选框 component/table/checkbox.html
Route::get('layui/component/table/radio', 'Layui\Component\TableController@radio');// 开启单选框 component/table/radio.html
Route::get('layui/component/table/cellEdit', 'Layui\Component\TableController@cellEdit');// 开启单元格编辑 component/table/cellEdit.html
Route::get('layui/component/table/form', 'Layui\Component\TableController@form');// 加入表单元素 component/table/form.html
Route::get('layui/component/table/style', 'Layui\Component\TableController@style');// 设置单元格样式 component/table/style.html
Route::get('layui/component/table/fixed', 'Layui\Component\TableController@fixed');// 固定列 component/table/fixed.html
Route::get('layui/component/table/operate', 'Layui\Component\TableController@operate');// 数据操作 component/table/operate.html
Route::get('layui/component/table/parseData', 'Layui\Component\TableController@parseData');// 解析任意数据格式 component/table/parseData.html
Route::get('layui/component/table/onrow', 'Layui\Component\TableController@onrow');// 监听行事件 component/table/onrow.html
Route::get('layui/component/table/reload', 'Layui\Component\TableController@reload');// 数据表格的重载 component/table/reload.html
Route::get('layui/component/table/initSort', 'Layui\Component\TableController@initSort');// 设置初始排序 component/table/initSort.html
Route::get('layui/component/table/cellEvent', 'Layui\Component\TableController@cellEvent');// 监听单元格事件 component/table/cellEvent.html
Route::get('layui/component/table/thead', 'Layui\Component\TableController@thead');// 复杂表头 component/table/thead.html
// 分页
Route::get('layui/component/laypage/index', 'Layui\Component\LaypageController@index');// component/laypage/index.html  通用分页组件 --***
Route::get('layui/component/laypage/demo1', 'Layui\Component\LaypageController@demo1');// 功能演示一 component/laypage/demo1.html
Route::get('layui/component/laypage/demo2', 'Layui\Component\LaypageController@demo2');// 功能演示二 component/laypage/demo2.html
// 上传
Route::get('layui/component/upload/index', 'Layui\Component\UploadController@index');// component/upload/index.html 上传 --***
Route::get('layui/component/upload/demo1', 'Layui\Component\UploadController@demo1');// 功能演示一 component/upload/demo1.html
Route::get('layui/component/upload/demo2', 'Layui\Component\UploadController@demo2');// 功能演示二 component/upload/demo2.html

Route::get('layui/component/colorpicker/index', 'Layui\Component\ColorpickerController@index');// 颜色选择器 component/colorpicker/index.html
Route::get('layui/component/slider/index', 'Layui\Component\SliderController@index');// 滑块组件 component/slider/index.html
Route::get('layui/component/rate/index', 'Layui\Component\RateController@index');// 评分 component/rate/index.html
Route::get('layui/component/carousel/index', 'Layui\Component\CarouselController@index');// 轮播 component/carousel/index.html
Route::get('layui/component/flow/index', 'Layui\Component\FlowController@index');// 流加载 component/flow/index.html
Route::get('layui/component/util/index', 'Layui\Component\UtilController@index');// 工具 component/util/index.html
Route::get('layui/component/code/index', 'Layui\Component\CodeController@index');// 代码修饰 component/code/index.html

// 页面
Route::get('layui/template/personalpage', 'Layui\TemplateController@personalpage');// 个人主页 template/personalpage.html
Route::get('layui/template/addresslist', 'Layui\TemplateController@addresslist');// 通讯录 template/addresslist.html
Route::get('layui/template/caller', 'Layui\TemplateController@caller');// 客户列表 template/caller.html
Route::get('layui/template/goodslist', 'Layui\TemplateController@goodslist');// 商品列表 template/goodslist.html
Route::get('layui/template/msgboard', 'Layui\TemplateController@msgboard');// 留言板 template/msgboard.html
Route::get('layui/template/search', 'Layui\TemplateController@search');// 搜索结果 template/search.html
Route::get('layui/template/temp', 'Layui\TemplateController@temp');// template/temp.html --***


Route::get('layui/user/reg', 'Layui\UserController@reg');// 注册 user/reg.html
Route::get('layui/user/login', 'Layui\UserController@login');// 登入 user/login.html
Route::get('layui/user/forget', 'Layui\UserController@forget');// 忘记密码 user/forget.html

Route::get('layui/template/tips/404', 'Layui\Template\TipsController@err404');// 404页面不存在 template/tips/404.html
Route::get('layui/template/tips/error', 'Layui\Template\TipsController@error');// 错误提示 template/tips/error.html
// 百度一下 //www.baidu.com/
// layui官网 //www.layui.com/
// layuiAdmin官网 //www.layui.com/admin/
// 应用
//    内容系统
Route::get('layui/app/content/list', 'Layui\App\ContentController@list');// 文章列表 app/content/list.html
Route::get('layui/app/content/tags', 'Layui\App\ContentController@tags');// 分类管理 app/content/tags.html
Route::get('layui/app/content/comment', 'Layui\App\ContentController@comment');// 评论管理 app/content/comment.html
Route::get('layui/app/content/contform', 'Layui\App\ContentController@contform');// app/content/contform.html  评论管理 iframe 框 --***
Route::get('layui/app/content/listform', 'Layui\App\ContentController@listform');// app/content/listform.html  文章管理 iframe 框 --***
Route::get('layui/app/content/tagsform', 'Layui\App\ContentController@tagsform');// app/content/tagsform.html  分类管理 iframe 框
//    社区系统
Route::get('layui/app/forum/list', 'Layui\App\ForumController@list');// 帖子列表 app/forum/list.html
Route::get('layui/app/forum/replys', 'Layui\App\ForumController@replys');// 回帖列表 app/forum/replys.html
Route::get('layui/app/forum/listform', 'Layui\App\ForumController@listform');// app/forum/listform.html  帖子管理 iframe 框 --***
Route::get('layui/app/forum/replysform', 'Layui\App\ForumController@replysform');// app/forum/replysform.html  回帖管理 iframe 框 --***

Route::get('layui/app/message/index', 'Layui\App\MessageController@index');// 消息中心 app/message/index.html
Route::get('layui/app/message/detail', 'Layui\App\MessageController@detail');// app/message/detail.html  消息详情标题 --***

Route::get('layui/app/workorder/list', 'Layui\App\WorkorderController@list');// 工单系统 app/workorder/list.html
Route::get('layui/app/workorder/listform', 'Layui\App\WorkorderController@listform');// app/workorder/listform.html 工单管理 iframe 框

Route::get('layui/app/mall/category', 'Layui\App\MallController@category');// app/mall/category.html  分类管理 --***
Route::get('layui/app/mall/list', 'Layui\App\MallController@list');// app/mall/list.html  商品列表 --***
Route::get('layui/app/mall/specs', 'Layui\App\MallController@specs');// app/mall/specs.html  规格管理 --***
//  高级
//    LayIM 通讯系统
Route::get('layui/senior/im/index', 'Layui\Senior\ImController@index');// senior/im/index.html  LayIM 社交聊天 --***
//    Echarts集成
Route::get('layui/senior/echarts/line', 'Layui\Senior\EchartsController@line');// 折线图 senior/echarts/line.html
Route::get('layui/senior/echarts/bar', 'Layui\Senior\EchartsController@bar');// 柱状图 senior/echarts/bar.html
Route::get('layui/senior/echarts/map', 'Layui\Senior\EchartsController@map');// 地图  senior/echarts/map.html
// 用户
Route::get('layui/user/user/list', 'Layui\User\UserController@list');// 网站用户 user/user/list.html
Route::get('layui/user/user/userform', 'Layui\User\UserController@userform');// user/user/userform.html  网站用户 iframe 框

Route::get('layui/user/administrators/list', 'Layui\User\AdministratorsController@list');// 后台管理员 user/administrators/list.html
Route::get('layui/user/administrators/role', 'Layui\User\AdministratorsController@role');// 角色管理 user/administrators/role.html
Route::get('layui/user/administrators/adminform', 'Layui\User\AdministratorsController@adminform');// user/administrators/adminform.html 管理员 iframe 框
Route::get('layui/user/administrators/roleform', 'Layui\User\AdministratorsController@roleform');// user/administrators/roleform.html 角色管理 iframe 框

// 设置
//    系统设置
Route::get('layui/set/system/website', 'Layui\Set\SystemController@website');// 网站设置 set/system/website.html
Route::get('layui/set/system/email', 'Layui\Set\SystemController@email');// 邮件服务 set/system/email.html
//    我的设置
Route::get('layui/set/user/info', 'Layui\Set\UserController@info');// 基本资料 set/user/info.html
Route::get('layui/set/user/password', 'Layui\Set\UserController@password');// 修改密码 set/user/password.html
// 授权  //www.layui.com/admin/#get

// ----大后台
// Admin
Route::get('admin/index', 'Admin\IndexController@index');// 首页
Route::get('admin', 'Admin\IndexController@index');
Route::get('admin/login', 'Admin\IndexController@login');//login.html 登录
Route::get('admin/logout', 'Admin\IndexController@logout');// 注销
Route::get('admin/password', 'Admin\IndexController@password');//psdmodify.html 个人信息-修改密码
Route::get('admin/info', 'Admin\IndexController@info');//myinfo.html 个人信息--显示


// 后台--管理员
Route::get('admin/staff', 'Admin\StaffController@index');// 列表
Route::get('admin/staff/add/{id}', 'Admin\StaffController@add');// 添加
// Route::get('admin/staff/select', 'Admin\StaffController@select');// 选择-弹窗
Route::get('admin/staff/export', 'Admin\StaffController@export');// 导出
Route::get('admin/staff/import_template', 'Admin\StaffController@import_template');// 导入模版

// 城市
Route::get('admin/city', 'Admin\CityController@index');// 列表
Route::get('admin/city/add/{id}', 'Admin\CityController@add');// 添加
Route::get('admin/city/select', 'Admin\CityController@select');// 选择-弹窗
Route::get('admin/city/export', 'Admin\CityController@export');// 导出
Route::get('admin/city/import_template', 'Admin\CityController@import_template');// 导入模版

