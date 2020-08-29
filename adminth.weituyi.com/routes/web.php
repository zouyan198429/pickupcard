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
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~无支付~~~~~~~~~~~~~~~~~开始~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// ----大后台--普通【无支付】
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

// 后台--企业
Route::get('admin/seller', 'Admin\SellerController@index');// 列表
Route::get('admin/seller/add/{id}', 'Admin\SellerController@add');// 添加
// Route::get('admin/seller/select', 'Admin\SellerController@select');// 选择-弹窗
Route::get('admin/seller/export', 'Admin\SellerController@export');// 导出
Route::get('admin/seller/import_template', 'Admin\SellerController@import_template');// 导入模版

// 后台--管理员
Route::get('admin/employee', 'Admin\EmployeeController@index');// 列表
//Route::get('admin/employee/add/{id}', 'Admin\EmployeeController@add');// 添加
// Route::get('admin/employee/select', 'Admin\EmployeeController@select');// 选择-弹窗
Route::get('admin/employee/export', 'Admin\EmployeeController@export');// 导出
Route::get('admin/employee/import_template', 'Admin\EmployeeController@import_template');// 导入模版

// 后台--用户
Route::get('admin/user', 'Admin\UserController@index');// 列表
//Route::get('admin/user/add/{id}', 'Admin\UserController@add');// 添加
// Route::get('admin/user/select', 'Admin\UserController@select');// 选择-弹窗
Route::get('admin/user/export', 'Admin\UserController@export');// 导出
Route::get('admin/user/import_template', 'Admin\UserController@import_template');// 导入模版

// 城市
Route::get('admin/city', 'Admin\CityController@index');// 列表
Route::get('admin/city/add/{id}', 'Admin\CityController@add');// 添加
Route::get('admin/city/select', 'Admin\CityController@select');// 选择-弹窗
Route::get('admin/city/export', 'Admin\CityController@export');// 导出
Route::get('admin/city/import_template', 'Admin\CityController@import_template');// 导入模版

// 商品
Route::get('admin/products', 'Admin\ProductController@index');// 列表
Route::get('admin/products/add/{id}', 'Admin\ProductController@add');// 添加
// Route::get('admin/products/select', 'Admin\ProductController@select');// 选择-弹窗
Route::get('admin/products/export', 'Admin\ProductController@export');// 导出
Route::get('admin/products/import_template', 'Admin\ProductController@import_template');// 导入模版

// 提货活动
Route::get('admin/activity', 'Admin\ActivityController@index');// 列表
Route::get('admin/activity/add/{id}', 'Admin\ActivityController@add');// 添加
// Route::get('admin/activity/select', 'Admin\ActivityController@select');// 选择-弹窗
Route::get('admin/activity/export', 'Admin\ActivityController@export');// 导出
Route::get('admin/activity/import_template', 'Admin\ActivityController@import_template');// 导入模版

// 兑换码
Route::get('admin/codes', 'Admin\CodesController@index');// 列表
Route::get('admin/codes/add/{id}', 'Admin\CodesController@add');// 添加
// Route::get('admin/codes/select', 'Admin\CodesController@select');// 选择-弹窗
Route::get('admin/codes/export', 'Admin\CodesController@export');// 导出
Route::get('admin/codes/import_template', 'Admin\CodesController@import_template');// 导入模版


// 收货地址
Route::get('admin/addrs', 'Admin\AddrsController@index');// 列表
Route::get('admin/addrs_wait_send', 'Admin\AddrsController@addrs_wait_send');// 未发货列表
Route::get('admin/addrs_sended', 'Admin\AddrsController@addrs_sended');// 已发货列表
Route::get('admin/addrs/add/{id}', 'Admin\AddrsController@add');// 添加
// Route::get('admin/addrs/select', 'Admin\AddrsController@select');// 选择-弹窗
Route::get('admin/addrs/export', 'Admin\AddrsController@export');// 导出
Route::get('admin/addrs/import_template', 'Admin\AddrsController@import_template');// 导入模版

// ----商家后台--普通【无支付】
// Seller
Route::get('seller/index', 'Seller\IndexController@index');// 首页
Route::get('seller', 'Seller\IndexController@index');
Route::get('seller/login', 'Seller\IndexController@login');//login.html 登录
Route::get('seller/logout', 'Seller\IndexController@logout');// 注销
Route::get('seller/password', 'Seller\IndexController@password');//psdmodify.html 个人信息-修改密码
Route::get('seller/info', 'Seller\IndexController@info');//myinfo.html 个人信息--显示

// 后台--企业
Route::get('seller/seller', 'Seller\SellerController@index');// 列表
Route::get('seller/seller/add/{id}', 'Seller\SellerController@add');// 添加
// Route::get('seller/seller/select', 'Seller\SellerController@select');// 选择-弹窗
Route::get('seller/seller/export', 'Seller\SellerController@export');// 导出
Route::get('seller/seller/import_template', 'Seller\SellerController@import_template');// 导入模版

// 后台--管理员
Route::get('seller/employee', 'Seller\EmployeeController@index');// 列表
//Route::get('seller/employee/add/{id}', 'Seller\EmployeeController@add');// 添加
// Route::get('seller/employee/select', 'Seller\EmployeeController@select');// 选择-弹窗
Route::get('seller/employee/export', 'Seller\EmployeeController@export');// 导出
Route::get('seller/employee/import_template', 'Seller\EmployeeController@import_template');// 导入模版

// 后台--用户
Route::get('seller/user', 'Seller\UserController@index');// 列表
//Route::get('seller/user/add/{id}', 'Seller\UserController@add');// 添加
// Route::get('seller/user/select', 'Seller\UserController@select');// 选择-弹窗
Route::get('seller/user/export', 'Seller\UserController@export');// 导出
Route::get('seller/user/import_template', 'Seller\UserController@import_template');// 导入模版

// 城市
Route::get('seller/city', 'Seller\CityController@index');// 列表
Route::get('seller/city/add/{id}', 'Seller\CityController@add');// 添加
Route::get('seller/city/select', 'Seller\CityController@select');// 选择-弹窗
Route::get('seller/city/export', 'Seller\CityController@export');// 导出
Route::get('seller/city/import_template', 'Seller\CityController@import_template');// 导入模版

// 商品
Route::get('seller/products', 'Seller\ProductController@index');// 列表
Route::get('seller/products/add/{id}', 'Seller\ProductController@add');// 添加
// Route::get('seller/products/select', 'Seller\ProductController@select');// 选择-弹窗
Route::get('seller/products/export', 'Seller\ProductController@export');// 导出
Route::get('seller/products/import_template', 'Seller\ProductController@import_template');// 导入模版

// 提货活动
Route::get('seller/activity', 'Seller\ActivityController@index');// 列表
Route::get('seller/activity/add/{id}', 'Seller\ActivityController@add');// 添加
// Route::get('seller/activity/select', 'Seller\ActivityController@select');// 选择-弹窗
Route::get('seller/activity/export', 'Seller\ActivityController@export');// 导出
Route::get('seller/activity/import_template', 'Seller\ActivityController@import_template');// 导入模版

// 兑换码
Route::get('seller/codes', 'Seller\CodesController@index');// 列表
Route::get('seller/codes/add/{id}', 'Seller\CodesController@add');// 添加
// Route::get('seller/codes/select', 'Seller\CodesController@select');// 选择-弹窗
Route::get('seller/codes/export', 'Seller\CodesController@export');// 导出
Route::get('seller/codes/import_template', 'Seller\CodesController@import_template');// 导入模版


// 收货地址
Route::get('seller/addrs', 'Seller\AddrsController@index');// 列表
Route::get('seller/addrs_wait_send', 'Seller\AddrsController@addrs_wait_send');// 未发货列表
Route::get('seller/addrs_sended', 'Seller\AddrsController@addrs_sended');// 已发货列表
Route::get('seller/addrs/add/{id}', 'Seller\AddrsController@add');// 添加
// Route::get('seller/addrs/select', 'Seller\AddrsController@select');// 选择-弹窗
Route::get('seller/addrs/export', 'Seller\AddrsController@export');// 导出
Route::get('seller/addrs/import_template', 'Seller\AddrsController@import_template');// 导入模版



// 微网站

// 首页

Route::get('web', 'Web\IndexController@index');// 首页
Route::get('web/index', 'Web\IndexController@index');// 首页
//Route::get('web/search/{code_id}/{code}', 'Web\IndexController@login');// 查询页 登录-- 暂时关闭一下
Route::get('web/test', 'Web\IndexController@test');// 测试

Route::get('web/search/{code_id}/{code}', 'Site\IndexController@product');// 第一页，显示商品页 --- 暂时跳转到SITE
// 产品相关的
Route::get('web/product/{product_id}', 'Web\ProductController@index');// 产品1
//Route::get('web/product2', 'Web\ProductController@product2');// 产品2
//Route::get('web/product3', 'Web\ProductController@product3');// 产品3
//Route::get('web/product4', 'Web\ProductController@product4');// 产品4

// 收货地址
Route::get('web/addrs/add', 'Web\AddrsController@add');// 添加


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~无支付~~~~~~~~~~~~~~~~~结束~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~有支付~~~~~~~~~~~~~~~~~开始~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// ----大后台--普通【有支付】
// Manage
Route::get('manage/index', 'Manage\IndexController@index');// 首页
Route::get('manage', 'Manage\IndexController@index');
Route::get('manage/login', 'Manage\IndexController@login');//login.html 登录
Route::get('manage/logout', 'Manage\IndexController@logout');// 注销
Route::get('manage/password', 'Manage\IndexController@password');//psdmodify.html 个人信息-修改密码
Route::get('manage/info', 'Manage\IndexController@info');//myinfo.html 个人信息--显示


// 后台--管理员
Route::get('manage/staff', 'Manage\StaffController@index');// 列表
Route::get('manage/staff/add/{id}', 'Manage\StaffController@add');// 添加
// Route::get('manage/staff/select', 'Manage\StaffController@select');// 选择-弹窗
Route::get('manage/staff/export', 'Manage\StaffController@export');// 导出
Route::get('manage/staff/import_template', 'Manage\StaffController@import_template');// 导入模版

// 后台--企业
Route::get('manage/seller', 'Manage\SellerController@index');// 列表
Route::get('manage/seller/add/{id}', 'Manage\SellerController@add');// 添加
// Route::get('manage/seller/select', 'Manage\SellerController@select');// 选择-弹窗
Route::get('manage/seller/export', 'Manage\SellerController@export');// 导出
Route::get('manage/seller/import_template', 'Manage\SellerController@import_template');// 导入模版

// 后台--管理员
Route::get('manage/employee', 'Manage\EmployeeController@index');// 列表
//Route::get('manage/employee/add/{id}', 'Manage\EmployeeController@add');// 添加
// Route::get('manage/employee/select', 'Manage\EmployeeController@select');// 选择-弹窗
Route::get('manage/employee/export', 'Manage\EmployeeController@export');// 导出
Route::get('manage/employee/import_template', 'Manage\EmployeeController@import_template');// 导入模版

// 后台--用户
Route::get('manage/user', 'Manage\UserController@index');// 列表
//Route::get('manage/user/add/{id}', 'Manage\UserController@add');// 添加
// Route::get('manage/user/select', 'Manage\UserController@select');// 选择-弹窗
Route::get('manage/user/export', 'Manage\UserController@export');// 导出
Route::get('manage/user/import_template', 'Manage\UserController@import_template');// 导入模版

// 城市
Route::get('manage/city', 'Manage\CityController@index');// 列表
Route::get('manage/city/add/{id}', 'Manage\CityController@add');// 添加
Route::get('manage/city/select', 'Manage\CityController@select');// 选择-弹窗
Route::get('manage/city/export', 'Manage\CityController@export');// 导出
Route::get('manage/city/import_template', 'Manage\CityController@import_template');// 导入模版

// 商品
Route::get('manage/products', 'Manage\ProductController@index');// 列表
Route::get('manage/products/add/{id}', 'Manage\ProductController@add');// 添加
// Route::get('manage/products/select', 'Manage\ProductController@select');// 选择-弹窗
Route::get('manage/products/export', 'Manage\ProductController@export');// 导出
Route::get('manage/products/import_template', 'Manage\ProductController@import_template');// 导入模版

// 提货活动
Route::get('manage/activity', 'Manage\ActivityController@index');// 列表
Route::get('manage/activity/add/{id}', 'Manage\ActivityController@add');// 添加
// Route::get('manage/activity/select', 'Manage\ActivityController@select');// 选择-弹窗
Route::get('manage/activity/export', 'Manage\ActivityController@export');// 导出
Route::get('manage/activity/import_template', 'Manage\ActivityController@import_template');// 导入模版

// 兑换码
Route::get('manage/codes', 'Manage\CodesController@index');// 列表
Route::get('manage/codes/add/{id}', 'Manage\CodesController@add');// 添加
// Route::get('manage/codes/select', 'Manage\CodesController@select');// 选择-弹窗
Route::get('manage/codes/export', 'Manage\CodesController@export');// 导出
Route::get('manage/codes/import_template', 'Manage\CodesController@import_template');// 导入模版


// 收货地址
Route::get('manage/addrs', 'Manage\AddrsController@index');// 列表
Route::get('manage/addrs_wait_send', 'Manage\AddrsController@addrs_wait_send');// 未发货列表
Route::get('manage/addrs_sended', 'Manage\AddrsController@addrs_sended');// 已发货列表
Route::get('manage/addrs/add/{id}', 'Manage\AddrsController@add');// 添加
// Route::get('manage/addrs/select', 'Manage\AddrsController@select');// 选择-弹窗
Route::get('manage/addrs/export', 'Manage\AddrsController@export');// 导出
Route::get('manage/addrs/import_template', 'Manage\AddrsController@import_template');// 导入模版

// ----商家后台--普通【有支付】
// Company
Route::get('company/index', 'Company\IndexController@index');// 首页
Route::get('company', 'Company\IndexController@index');
Route::get('company/login', 'Company\IndexController@login');//login.html 登录
Route::get('company/logout', 'Company\IndexController@logout');// 注销
Route::get('company/password', 'Company\IndexController@password');//psdmodify.html 个人信息-修改密码
Route::get('company/info', 'Company\IndexController@info');//myinfo.html 个人信息--显示

// 后台--企业
Route::get('company/seller', 'Company\SellerController@index');// 列表
Route::get('company/seller/add/{id}', 'Company\SellerController@add');// 添加
// Route::get('company/seller/select', 'Company\SellerController@select');// 选择-弹窗
Route::get('company/seller/export', 'Company\SellerController@export');// 导出
Route::get('company/seller/import_template', 'Company\SellerController@import_template');// 导入模版

// 后台--管理员
Route::get('company/employee', 'Company\EmployeeController@index');// 列表
//Route::get('company/employee/add/{id}', 'Company\EmployeeController@add');// 添加
// Route::get('company/employee/select', 'Company\EmployeeController@select');// 选择-弹窗
Route::get('company/employee/export', 'Company\EmployeeController@export');// 导出
Route::get('company/employee/import_template', 'Company\EmployeeController@import_template');// 导入模版

// 后台--用户
Route::get('company/user', 'Company\UserController@index');// 列表
//Route::get('company/user/add/{id}', 'Company\UserController@add');// 添加
// Route::get('company/user/select', 'Company\UserController@select');// 选择-弹窗
Route::get('company/user/export', 'Company\UserController@export');// 导出
Route::get('company/user/import_template', 'Company\UserController@import_template');// 导入模版

// 城市
Route::get('company/city', 'Company\CityController@index');// 列表
Route::get('company/city/add/{id}', 'Company\CityController@add');// 添加
Route::get('company/city/select', 'Company\CityController@select');// 选择-弹窗
Route::get('company/city/export', 'Company\CityController@export');// 导出
Route::get('company/city/import_template', 'Company\CityController@import_template');// 导入模版

// 商品
Route::get('company/products', 'Company\ProductController@index');// 列表
Route::get('company/products/add/{id}', 'Company\ProductController@add');// 添加
// Route::get('company/products/select', 'Company\ProductController@select');// 选择-弹窗
Route::get('company/products/export', 'Company\ProductController@export');// 导出
Route::get('company/products/import_template', 'Company\ProductController@import_template');// 导入模版

// 提货活动
Route::get('company/activity', 'Company\ActivityController@index');// 列表
Route::get('company/activity/add/{id}', 'Company\ActivityController@add');// 添加
// Route::get('company/activity/select', 'Company\ActivityController@select');// 选择-弹窗
Route::get('company/activity/export', 'Company\ActivityController@export');// 导出
Route::get('company/activity/import_template', 'Company\ActivityController@import_template');// 导入模版

// 兑换码
Route::get('company/codes', 'Company\CodesController@index');// 列表
Route::get('company/codes/add/{id}', 'Company\CodesController@add');// 添加
// Route::get('company/codes/select', 'Company\CodesController@select');// 选择-弹窗
Route::get('company/codes/export', 'Company\CodesController@export');// 导出
Route::get('company/codes/import_template', 'Company\CodesController@import_template');// 导入模版


// 收货地址
Route::get('company/addrs', 'Company\AddrsController@index');// 列表
Route::get('company/addrs_wait_send', 'Company\AddrsController@addrs_wait_send');// 未发货列表
Route::get('company/addrs_sended', 'Company\AddrsController@addrs_sended');// 已发货列表
Route::get('company/addrs/add/{id}', 'Company\AddrsController@add');// 添加
// Route::get('company/addrs/select', 'Company\AddrsController@select');// 选择-弹窗
Route::get('company/addrs/export', 'Company\AddrsController@export');// 导出
Route::get('company/addrs/import_template', 'Company\AddrsController@import_template');// 导入模版



// 微网站

// 首页

Route::get('site', 'Site\IndexController@index');// 首页-ok
Route::get('site/index', 'Site\IndexController@index');// 首页-ok
Route::get('site/search/{code_id}/{code}', 'Site\IndexController@product');// 第一页，显示商品页-ok
Route::get('site/login/{code_id}/{code}', 'Site\IndexController@login');// 查询页 登录--输入卡密-ok
Route::get('site/test', 'Site\IndexController@test');// 测试

// 产品相关的
Route::get('site/product/{product_id}', 'Site\ProductController@index');// 产品1
//Route::get('site/product2', 'Site\ProductController@product2');// 产品2
//Route::get('site/product3', 'Site\ProductController@product3');// 产品3
//Route::get('site/product4', 'Site\ProductController@product4');// 产品4

// 收货地址
Route::get('site/addrs/add/{redisKey}', 'Site\AddrsController@add');// 添加
Route::get('site/addrs/payOK/{redisKey}', 'Site\AddrsController@payOK');// 支付成功


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~有支付~~~~~~~~~~~~~~~~~结束~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


