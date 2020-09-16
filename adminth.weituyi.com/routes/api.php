<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// 文件上传 any(
// Route::post('file/upload', 'IndexController@upload');
Route::post('upload', 'UploadController@index');
// Route::post('upload/test', 'UploadController@test');
// excel
Route::get('excel/test','ExcelController@test');
Route::get('excel/export','ExcelController@export'); // 导出
Route::get('excel/import','ExcelController@import'); // 导入
Route::get('excel/import_test','ExcelController@import_test'); // 导入 - 测试

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~无支付~~~~~~~~~~~~~~~~~开始~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// ----大后台--普通【无支付】
// admin
// 上传图片
Route::post('admin/upload', 'Admin\UploadController@index');
Route::post('admin/upload/ajax_del', 'Admin\UploadController@ajax_del');// 根据id删除文件

//// 登陆
Route::any('admin/ajax_login', 'Admin\IndexController@ajax_login');// 登陆
Route::post('admin/ajax_password_save', 'Admin\IndexController@ajax_password_save');// 修改密码
Route::any('admin/ajax_info_save', 'Admin\IndexController@ajax_info_save');// 修改设置
//Route::any('admin/ajax_getTableUpdateTime', 'Admin\IndexController@ajax_getTableUpdateTime');// 获得模块表的最新更新时间

//后台--管理员
Route::any('admin/staff/ajax_alist', 'Admin\StaffController@ajax_alist');//ajax获得列表数据
Route::post('admin/staff/ajax_del', 'Admin\StaffController@ajax_del');// 删除
Route::any('admin/staff/ajax_save', 'Admin\StaffController@ajax_save');// 新加/修改
Route::post('admin/staff/ajax_get_child', 'Admin\StaffController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('admin/staff/ajax_get_areachild', 'Admin\StaffController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('admin/staff/ajax_import_staff','Admin\StaffController@ajax_import'); // 导入员工

Route::post('admin/staff/import', 'Admin\StaffController@import');// 导入excel
Route::post('admin/staff/ajax_get_ids', 'Admin\StaffController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔


//后台--企业
Route::any('admin/seller/ajax_alist', 'Admin\SellerController@ajax_alist');//ajax获得列表数据
Route::post('admin/seller/ajax_del', 'Admin\SellerController@ajax_del');// 删除
Route::any('admin/seller/ajax_save', 'Admin\SellerController@ajax_save');// 新加/修改
Route::post('admin/seller/ajax_get_child', 'Admin\SellerController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('admin/seller/ajax_get_areachild', 'Admin\SellerController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('admin/seller/ajax_import_staff','Admin\SellerController@ajax_import'); // 导入员工

Route::post('admin/seller/import', 'Admin\SellerController@import');// 导入excel
Route::post('admin/seller/ajax_get_ids', 'Admin\SellerController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔

//后台--管理员
Route::any('admin/employee/ajax_alist', 'Admin\EmployeeController@ajax_alist');//ajax获得列表数据
Route::post('admin/employee/ajax_del', 'Admin\EmployeeController@ajax_del');// 删除
//Route::any('admin/employee/ajax_save', 'Admin\EmployeeController@ajax_save');// 新加/修改
Route::post('admin/employee/ajax_get_child', 'Admin\EmployeeController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('admin/employee/ajax_get_areachild', 'Admin\EmployeeController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('admin/employee/ajax_import_staff','Admin\EmployeeController@ajax_import'); // 导入员工

Route::post('admin/employee/import', 'Admin\EmployeeController@import');// 导入excel
Route::post('admin/employee/ajax_get_ids', 'Admin\EmployeeController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔

//后台--用户
Route::any('admin/user/ajax_alist', 'Admin\UserController@ajax_alist');//ajax获得列表数据
Route::post('admin/user/ajax_del', 'Admin\UserController@ajax_del');// 删除
//Route::any('admin/user/ajax_save', 'Admin\UserController@ajax_save');// 新加/修改
Route::post('admin/user/ajax_get_child', 'Admin\UserController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('admin/user/ajax_get_areachild', 'Admin\UserController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('admin/user/ajax_import_staff','Admin\UserController@ajax_import'); // 导入员工

Route::post('admin/user/import', 'Admin\UserController@import');// 导入excel
Route::post('admin/user/ajax_get_ids', 'Admin\UserController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔

//城市
Route::any('admin/city/ajax_alist', 'Admin\CityController@ajax_alist');//ajax获得列表数据
Route::post('admin/city/ajax_del', 'Admin\CityController@ajax_del');// 删除
Route::post('admin/city/ajax_save', 'Admin\CityController@ajax_save');// 新加/修改
Route::post('admin/city/ajax_get_child', 'Admin\CityController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('admin/city/ajax_get_areachild', 'Admin\CityController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('admin/city/ajax_import_staff','Admin\CityController@ajax_import'); // 导入员工

Route::post('admin/city/import', 'Admin\CityController@import');// 导入excel
Route::post('admin/city/ajax_get_ids', 'Admin\CityController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔
Route::any('admin/city/ajax_selected', 'Admin\CityController@ajax_selected');//ajax选择中记录/更新记录

//商品
Route::post('admin/products/ajax_alist', 'Admin\ProductController@ajax_alist');//ajax获得列表数据
Route::post('admin/products/ajax_del', 'Admin\ProductController@ajax_del');// 删除
Route::post('admin/products/ajax_save', 'Admin\ProductController@ajax_save');// 新加/修改
Route::post('admin/products/ajax_get_child', 'Admin\ProductController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('admin/products/ajax_get_areachild', 'Admin\ProductController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('admin/products/ajax_import_staff','Admin\ProductController@ajax_import'); // 导入员工


Route::post('admin/products/import', 'Admin\ProductController@import');// 导入excel
Route::post('admin/products/ajax_get_ids', 'Admin\ProductController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔


//提货活动
Route::post('admin/activity/ajax_alist', 'Admin\ActivityController@ajax_alist');//ajax获得列表数据
Route::post('admin/activity/ajax_del', 'Admin\ActivityController@ajax_del');// 删除
Route::any('admin/activity/ajax_save', 'Admin\ActivityController@ajax_save');// 新加/修改
Route::post('admin/activity/ajax_get_child', 'Admin\ActivityController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('admin/activity/ajax_get_areachild', 'Admin\ActivityController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('admin/activity/ajax_import_staff','Admin\ActivityController@ajax_import'); // 导入员工


Route::post('admin/activity/import', 'Admin\ActivityController@import');// 导入excel
Route::post('admin/activity/ajax_get_ids', 'Admin\ActivityController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔

//兑换码
Route::any('admin/codes/ajax_alist', 'Admin\CodesController@ajax_alist');//ajax获得列表数据
Route::post('admin/codes/ajax_del', 'Admin\CodesController@ajax_del');// 删除
Route::post('admin/codes/ajax_save', 'Admin\CodesController@ajax_save');// 新加/修改
Route::post('admin/codes/ajax_get_child', 'Admin\CodesController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('admin/codes/ajax_get_areachild', 'Admin\CodesController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('admin/codes/ajax_import_staff','Admin\CodesController@ajax_import'); // 导入员工


Route::post('admin/codes/import', 'Admin\CodesController@import');// 导入excel
Route::post('admin/codes/ajax_get_ids', 'Admin\CodesController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔

Route::post('admin/codes/ajax_open_all', 'Admin\CodesController@ajax_open_all');// 开启所有[根据活动id]
Route::post('admin/codes/ajax_open', 'Admin\CodesController@ajax_open');// 单个或批量开启
Route::post('admin/codes/ajax_close_all', 'Admin\CodesController@ajax_close_all');// 关闭所有[根据活动id]
Route::post('admin/codes/ajax_close', 'Admin\CodesController@ajax_close');// 单个或批量关闭

//收货地址
Route::any('admin/addrs/ajax_alist', 'Admin\AddrsController@ajax_alist');//ajax获得列表数据
Route::post('admin/addrs/ajax_del', 'Admin\AddrsController@ajax_del');// 删除
Route::post('admin/addrs/ajax_save', 'Admin\AddrsController@ajax_save');// 新加/修改
Route::post('admin/addrs/ajax_send', 'Admin\AddrsController@ajax_send');// 发货
Route::post('admin/addrs/ajax_get_child', 'Admin\AddrsController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('admin/addrs/ajax_get_areachild', 'Admin\AddrsController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('admin/addrs/ajax_import_staff','Admin\AddrsController@ajax_import'); // 导入员工


Route::post('admin/addrs/import', 'Admin\AddrsController@import');// 导入excel
Route::post('admin/addrs/ajax_get_ids', 'Admin\AddrsController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔


// ----商家后台--普通【无支付】
// seller
// 上传图片
Route::post('seller/upload', 'Seller\UploadController@index');
Route::post('seller/upload/ajax_del', 'Seller\UploadController@ajax_del');// 根据id删除文件

//// 登陆
Route::any('seller/ajax_login', 'Seller\IndexController@ajax_login');// 登陆
Route::post('seller/ajax_password_save', 'Seller\IndexController@ajax_password_save');// 修改密码
Route::any('seller/ajax_info_save', 'Seller\IndexController@ajax_info_save');// 修改设置

//后台--管理员
Route::any('seller/staff/ajax_alist', 'Seller\StaffController@ajax_alist');//ajax获得列表数据
Route::post('seller/staff/ajax_del', 'Seller\StaffController@ajax_del');// 删除
Route::any('seller/staff/ajax_save', 'Seller\StaffController@ajax_save');// 新加/修改
Route::post('seller/staff/ajax_get_child', 'Seller\StaffController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('seller/staff/ajax_get_areachild', 'Seller\StaffController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('seller/staff/ajax_import_staff','Seller\StaffController@ajax_import'); // 导入员工

Route::post('seller/staff/import', 'Seller\StaffController@import');// 导入excel
Route::post('seller/staff/ajax_get_ids', 'Seller\StaffController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔


//后台--企业
Route::any('seller/seller/ajax_alist', 'Seller\SellerController@ajax_alist');//ajax获得列表数据
Route::post('seller/seller/ajax_del', 'Seller\SellerController@ajax_del');// 删除
Route::any('seller/seller/ajax_save', 'Seller\SellerController@ajax_save');// 新加/修改
Route::post('seller/seller/ajax_get_child', 'Seller\SellerController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('seller/seller/ajax_get_areachild', 'Seller\SellerController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('seller/seller/ajax_import_staff','Seller\SellerController@ajax_import'); // 导入员工

Route::post('seller/seller/import', 'Seller\SellerController@import');// 导入excel
Route::post('seller/seller/ajax_get_ids', 'Seller\SellerController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔

//后台--管理员
Route::any('seller/employee/ajax_alist', 'Seller\EmployeeController@ajax_alist');//ajax获得列表数据
Route::post('seller/employee/ajax_del', 'Seller\EmployeeController@ajax_del');// 删除
//Route::any('seller/employee/ajax_save', 'Seller\EmployeeController@ajax_save');// 新加/修改
Route::post('seller/employee/ajax_get_child', 'Seller\EmployeeController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('seller/employee/ajax_get_areachild', 'Seller\EmployeeController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('seller/employee/ajax_import_staff','Seller\EmployeeController@ajax_import'); // 导入员工

Route::post('seller/employee/import', 'Seller\EmployeeController@import');// 导入excel
Route::post('seller/employee/ajax_get_ids', 'Seller\EmployeeController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔

//后台--用户
Route::any('seller/user/ajax_alist', 'Seller\UserController@ajax_alist');//ajax获得列表数据
Route::post('seller/user/ajax_del', 'Seller\UserController@ajax_del');// 删除
//Route::any('seller/user/ajax_save', 'Seller\UserController@ajax_save');// 新加/修改
Route::post('seller/user/ajax_get_child', 'Seller\UserController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('seller/user/ajax_get_areachild', 'Seller\UserController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('seller/user/ajax_import_staff','Seller\UserController@ajax_import'); // 导入员工

Route::post('seller/user/import', 'Seller\UserController@import');// 导入excel
Route::post('seller/user/ajax_get_ids', 'Seller\UserController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔

//城市
Route::any('seller/city/ajax_alist', 'Seller\CityController@ajax_alist');//ajax获得列表数据
Route::post('seller/city/ajax_del', 'Seller\CityController@ajax_del');// 删除
Route::post('seller/city/ajax_save', 'Seller\CityController@ajax_save');// 新加/修改
Route::post('seller/city/ajax_get_child', 'Seller\CityController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('seller/city/ajax_get_areachild', 'Seller\CityController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('seller/city/ajax_import_staff','Seller\CityController@ajax_import'); // 导入员工

Route::post('seller/city/import', 'Seller\CityController@import');// 导入excel
Route::post('seller/city/ajax_get_ids', 'Seller\CityController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔
Route::any('seller/city/ajax_selected', 'Seller\CityController@ajax_selected');//ajax选择中记录/更新记录

//商品
Route::post('seller/products/ajax_alist', 'Seller\ProductController@ajax_alist');//ajax获得列表数据
Route::post('seller/products/ajax_del', 'Seller\ProductController@ajax_del');// 删除
Route::post('seller/products/ajax_save', 'Seller\ProductController@ajax_save');// 新加/修改
Route::post('seller/products/ajax_get_child', 'Seller\ProductController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('seller/products/ajax_get_areachild', 'Seller\ProductController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('seller/products/ajax_import_staff','Seller\ProductController@ajax_import'); // 导入员工


Route::post('seller/products/import', 'Seller\ProductController@import');// 导入excel
Route::post('seller/products/ajax_get_ids', 'Seller\ProductController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔


//提货活动
Route::post('seller/activity/ajax_alist', 'Seller\ActivityController@ajax_alist');//ajax获得列表数据
Route::post('seller/activity/ajax_del', 'Seller\ActivityController@ajax_del');// 删除
Route::any('seller/activity/ajax_save', 'Seller\ActivityController@ajax_save');// 新加/修改
Route::post('seller/activity/ajax_get_child', 'Seller\ActivityController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('seller/activity/ajax_get_areachild', 'Seller\ActivityController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('seller/activity/ajax_import_staff','Seller\ActivityController@ajax_import'); // 导入员工


Route::post('seller/activity/import', 'Seller\ActivityController@import');// 导入excel
Route::post('seller/activity/ajax_get_ids', 'Seller\ActivityController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔

//兑换码
Route::any('seller/codes/ajax_alist', 'Seller\CodesController@ajax_alist');//ajax获得列表数据
Route::post('seller/codes/ajax_del', 'Seller\CodesController@ajax_del');// 删除
Route::post('seller/codes/ajax_save', 'Seller\CodesController@ajax_save');// 新加/修改
Route::post('seller/codes/ajax_get_child', 'Seller\CodesController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('seller/codes/ajax_get_areachild', 'Seller\CodesController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('seller/codes/ajax_import_staff','Seller\CodesController@ajax_import'); // 导入员工


Route::post('seller/codes/import', 'Seller\CodesController@import');// 导入excel
Route::post('seller/codes/ajax_get_ids', 'Seller\CodesController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔

Route::post('seller/codes/ajax_open_all', 'Seller\CodesController@ajax_open_all');// 开启所有[根据活动id]
Route::post('seller/codes/ajax_open', 'Seller\CodesController@ajax_open');// 单个或批量开启
Route::post('seller/codes/ajax_close_all', 'Seller\CodesController@ajax_close_all');// 关闭所有[根据活动id]
Route::post('seller/codes/ajax_close', 'Seller\CodesController@ajax_close');// 单个或批量关闭

//收货地址
Route::any('seller/addrs/ajax_alist', 'Seller\AddrsController@ajax_alist');//ajax获得列表数据
Route::post('seller/addrs/ajax_del', 'Seller\AddrsController@ajax_del');// 删除
Route::post('seller/addrs/ajax_save', 'Seller\AddrsController@ajax_save');// 新加/修改
Route::post('seller/addrs/ajax_send', 'Seller\AddrsController@ajax_send');// 发货
Route::post('seller/addrs/ajax_get_child', 'Seller\AddrsController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('seller/addrs/ajax_get_areachild', 'Seller\AddrsController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('seller/addrs/ajax_import_staff','Seller\AddrsController@ajax_import'); // 导入员工


Route::post('seller/addrs/import', 'Seller\AddrsController@import');// 导入excel
Route::post('seller/addrs/ajax_get_ids', 'Seller\AddrsController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔


// 微网站
Route::any('web/ajax_login', 'Web\IndexController@ajax_login');// 登陆
Route::any('web/ajax_save', 'Web\IndexController@ajax_save');// 提货

//城市
//Route::any('web/city/ajax_alist', 'Web\CityController@ajax_alist');//ajax获得列表数据
//Route::post('web/city/ajax_del', 'Web\CityController@ajax_del');// 删除
//Route::post('web/city/ajax_save', 'Web\CityController@ajax_save');// 新加/修改
Route::post('web/city/ajax_get_child', 'Web\CityController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('web/city/ajax_get_areachild', 'Web\CityController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
//Route::post('web/city/ajax_import_staff','Web\CityController@ajax_import'); // 导入员工

//Route::post('web/city/import', 'Web\CityController@import');// 导入excel
Route::post('web/city/ajax_get_ids', 'Web\CityController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔
//Route::any('web/city/ajax_selected', 'Web\CityController@ajax_selected');//ajax选择中记录/更新记录

//收货地址
Route::any('web/addrs/ajax_save', 'Web\AddrsController@ajax_save');// 新加/修改

// 城市

Route::any('web/city/ajax_get_child', 'Web\CityController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~无支付~~~~~~~~~~~~~~~~~结束~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~有支付~~~~~~~~~~~~~~~~~开始~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// ----大后台--普通【有支付】
// manage
// 上传图片
Route::post('manage/upload', 'Manage\UploadController@index');
Route::post('manage/upload/ajax_del', 'Manage\UploadController@ajax_del');// 根据id删除文件

//// 登陆
Route::any('manage/ajax_login', 'Manage\IndexController@ajax_login');// 登陆
Route::post('manage/ajax_password_save', 'Manage\IndexController@ajax_password_save');// 修改密码
Route::any('manage/ajax_info_save', 'Manage\IndexController@ajax_info_save');// 修改设置

//后台--管理员
Route::any('manage/staff/ajax_alist', 'Manage\StaffController@ajax_alist');//ajax获得列表数据
Route::post('manage/staff/ajax_del', 'Manage\StaffController@ajax_del');// 删除
Route::any('manage/staff/ajax_save', 'Manage\StaffController@ajax_save');// 新加/修改
Route::post('manage/staff/ajax_get_child', 'Manage\StaffController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('manage/staff/ajax_get_areachild', 'Manage\StaffController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('manage/staff/ajax_import_staff','Manage\StaffController@ajax_import'); // 导入员工

Route::post('manage/staff/import', 'Manage\StaffController@import');// 导入excel
Route::post('manage/staff/ajax_get_ids', 'Manage\StaffController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔


//后台--企业
Route::any('manage/seller/ajax_alist', 'Manage\SellerController@ajax_alist');//ajax获得列表数据
Route::post('manage/seller/ajax_del', 'Manage\SellerController@ajax_del');// 删除
Route::any('manage/seller/ajax_save', 'Manage\SellerController@ajax_save');// 新加/修改
Route::post('manage/seller/ajax_get_child', 'Manage\SellerController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('manage/seller/ajax_get_areachild', 'Manage\SellerController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('manage/seller/ajax_import_staff','Manage\SellerController@ajax_import'); // 导入员工

Route::post('manage/seller/import', 'Manage\SellerController@import');// 导入excel
Route::post('manage/seller/ajax_get_ids', 'Manage\SellerController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔

//后台--管理员
Route::any('manage/employee/ajax_alist', 'Manage\EmployeeController@ajax_alist');//ajax获得列表数据
Route::post('manage/employee/ajax_del', 'Manage\EmployeeController@ajax_del');// 删除
//Route::any('manage/employee/ajax_save', 'Manage\EmployeeController@ajax_save');// 新加/修改
Route::post('manage/employee/ajax_get_child', 'Manage\EmployeeController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('manage/employee/ajax_get_areachild', 'Manage\EmployeeController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('manage/employee/ajax_import_staff','Manage\EmployeeController@ajax_import'); // 导入员工

Route::post('manage/employee/import', 'Manage\EmployeeController@import');// 导入excel
Route::post('manage/employee/ajax_get_ids', 'Manage\EmployeeController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔

//后台--用户
Route::any('manage/user/ajax_alist', 'Manage\UserController@ajax_alist');//ajax获得列表数据
Route::post('manage/user/ajax_del', 'Manage\UserController@ajax_del');// 删除
//Route::any('manage/user/ajax_save', 'Manage\UserController@ajax_save');// 新加/修改
Route::post('manage/user/ajax_get_child', 'Manage\UserController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('manage/user/ajax_get_areachild', 'Manage\UserController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('manage/user/ajax_import_staff','Manage\UserController@ajax_import'); // 导入员工

Route::post('manage/user/import', 'Manage\UserController@import');// 导入excel
Route::post('manage/user/ajax_get_ids', 'Manage\UserController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔

//城市
Route::any('manage/city/ajax_alist', 'Manage\CityController@ajax_alist');//ajax获得列表数据
Route::post('manage/city/ajax_del', 'Manage\CityController@ajax_del');// 删除
Route::post('manage/city/ajax_save', 'Manage\CityController@ajax_save');// 新加/修改
Route::post('manage/city/ajax_get_child', 'Manage\CityController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('manage/city/ajax_get_areachild', 'Manage\CityController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('manage/city/ajax_import_staff','Manage\CityController@ajax_import'); // 导入员工

Route::post('manage/city/import', 'Manage\CityController@import');// 导入excel
Route::post('manage/city/ajax_get_ids', 'Manage\CityController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔
Route::any('manage/city/ajax_selected', 'Manage\CityController@ajax_selected');//ajax选择中记录/更新记录

//商品
Route::post('manage/products/ajax_alist', 'Manage\ProductController@ajax_alist');//ajax获得列表数据
Route::post('manage/products/ajax_del', 'Manage\ProductController@ajax_del');// 删除
Route::post('manage/products/ajax_save', 'Manage\ProductController@ajax_save');// 新加/修改
Route::post('manage/products/ajax_get_child', 'Manage\ProductController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('manage/products/ajax_get_areachild', 'Manage\ProductController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('manage/products/ajax_import_staff','Manage\ProductController@ajax_import'); // 导入员工


Route::post('manage/products/import', 'Manage\ProductController@import');// 导入excel
Route::post('manage/products/ajax_get_ids', 'Manage\ProductController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔


//提货活动
Route::post('manage/activity/ajax_alist', 'Manage\ActivityController@ajax_alist');//ajax获得列表数据
Route::post('manage/activity/ajax_del', 'Manage\ActivityController@ajax_del');// 删除
Route::any('manage/activity/ajax_save', 'Manage\ActivityController@ajax_save');// 新加/修改
Route::post('manage/activity/ajax_get_child', 'Manage\ActivityController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('manage/activity/ajax_get_areachild', 'Manage\ActivityController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('manage/activity/ajax_import_staff','Manage\ActivityController@ajax_import'); // 导入员工


Route::post('manage/activity/import', 'Manage\ActivityController@import');// 导入excel
Route::post('manage/activity/ajax_get_ids', 'Manage\ActivityController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔

//兑换码
Route::any('manage/codes/ajax_alist', 'Manage\CodesController@ajax_alist');//ajax获得列表数据
Route::post('manage/codes/ajax_del', 'Manage\CodesController@ajax_del');// 删除
Route::post('manage/codes/ajax_save', 'Manage\CodesController@ajax_save');// 新加/修改
Route::post('manage/codes/ajax_get_child', 'Manage\CodesController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('manage/codes/ajax_get_areachild', 'Manage\CodesController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('manage/codes/ajax_import_staff','Manage\CodesController@ajax_import'); // 导入员工


Route::post('manage/codes/import', 'Manage\CodesController@import');// 导入excel
Route::post('manage/codes/ajax_get_ids', 'Manage\CodesController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔

Route::post('manage/codes/ajax_open_all', 'Manage\CodesController@ajax_open_all');// 开启所有[根据活动id]
Route::post('manage/codes/ajax_open', 'Manage\CodesController@ajax_open');// 单个或批量开启
Route::post('manage/codes/ajax_close_all', 'Manage\CodesController@ajax_close_all');// 关闭所有[根据活动id]
Route::post('manage/codes/ajax_close', 'Manage\CodesController@ajax_close');// 单个或批量关闭

//收货地址
Route::any('manage/addrs/ajax_alist', 'Manage\AddrsController@ajax_alist');//ajax获得列表数据
Route::post('manage/addrs/ajax_del', 'Manage\AddrsController@ajax_del');// 删除
Route::post('manage/addrs/ajax_save', 'Manage\AddrsController@ajax_save');// 新加/修改
Route::post('manage/addrs/ajax_send', 'Manage\AddrsController@ajax_send');// 发货
Route::post('manage/addrs/ajax_get_child', 'Manage\AddrsController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('manage/addrs/ajax_get_areachild', 'Manage\AddrsController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('manage/addrs/ajax_import_staff','Manage\AddrsController@ajax_import'); // 导入员工


Route::post('manage/addrs/import', 'Manage\AddrsController@import');// 导入excel
Route::post('manage/addrs/ajax_get_ids', 'Manage\AddrsController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔


// ----商家后台--普通【有支付】
// company
// 上传图片
Route::post('company/upload', 'Company\UploadController@index');
Route::post('company/upload/ajax_del', 'Company\UploadController@ajax_del');// 根据id删除文件

//// 登陆
Route::any('company/ajax_login', 'Company\IndexController@ajax_login');// 登陆
Route::post('company/ajax_password_save', 'Company\IndexController@ajax_password_save');// 修改密码
Route::any('company/ajax_info_save', 'Company\IndexController@ajax_info_save');// 修改设置

//后台--管理员
Route::any('company/staff/ajax_alist', 'Company\StaffController@ajax_alist');//ajax获得列表数据
Route::post('company/staff/ajax_del', 'Company\StaffController@ajax_del');// 删除
Route::any('company/staff/ajax_save', 'Company\StaffController@ajax_save');// 新加/修改
Route::post('company/staff/ajax_get_child', 'Company\StaffController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('company/staff/ajax_get_areachild', 'Company\StaffController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('company/staff/ajax_import_staff','Company\StaffController@ajax_import'); // 导入员工

Route::post('company/staff/import', 'Company\StaffController@import');// 导入excel
Route::post('company/staff/ajax_get_ids', 'Company\StaffController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔


//后台--企业
Route::any('company/seller/ajax_alist', 'Company\SellerController@ajax_alist');//ajax获得列表数据
Route::post('company/seller/ajax_del', 'Company\SellerController@ajax_del');// 删除
Route::any('company/seller/ajax_save', 'Company\SellerController@ajax_save');// 新加/修改
Route::post('company/seller/ajax_get_child', 'Company\SellerController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('company/seller/ajax_get_areachild', 'Company\SellerController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('company/seller/ajax_import_staff','Company\SellerController@ajax_import'); // 导入员工

Route::post('company/seller/import', 'Company\SellerController@import');// 导入excel
Route::post('company/seller/ajax_get_ids', 'Company\SellerController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔

//后台--管理员
Route::any('company/employee/ajax_alist', 'Company\EmployeeController@ajax_alist');//ajax获得列表数据
Route::post('company/employee/ajax_del', 'Company\EmployeeController@ajax_del');// 删除
//Route::any('company/employee/ajax_save', 'Company\EmployeeController@ajax_save');// 新加/修改
Route::post('company/employee/ajax_get_child', 'Company\EmployeeController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('company/employee/ajax_get_areachild', 'Company\EmployeeController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('company/employee/ajax_import_staff','Company\EmployeeController@ajax_import'); // 导入员工

Route::post('company/employee/import', 'Company\EmployeeController@import');// 导入excel
Route::post('company/employee/ajax_get_ids', 'Company\EmployeeController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔

//后台--用户
Route::any('company/user/ajax_alist', 'Company\UserController@ajax_alist');//ajax获得列表数据
Route::post('company/user/ajax_del', 'Company\UserController@ajax_del');// 删除
//Route::any('company/user/ajax_save', 'Company\UserController@ajax_save');// 新加/修改
Route::post('company/user/ajax_get_child', 'Company\UserController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('company/user/ajax_get_areachild', 'Company\UserController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('company/user/ajax_import_staff','Company\UserController@ajax_import'); // 导入员工

Route::post('company/user/import', 'Company\UserController@import');// 导入excel
Route::post('company/user/ajax_get_ids', 'Company\UserController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔

//城市
Route::any('company/city/ajax_alist', 'Company\CityController@ajax_alist');//ajax获得列表数据
Route::post('company/city/ajax_del', 'Company\CityController@ajax_del');// 删除
Route::post('company/city/ajax_save', 'Company\CityController@ajax_save');// 新加/修改
Route::post('company/city/ajax_get_child', 'Company\CityController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('company/city/ajax_get_areachild', 'Company\CityController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('company/city/ajax_import_staff','Company\CityController@ajax_import'); // 导入员工

Route::post('company/city/import', 'Company\CityController@import');// 导入excel
Route::post('company/city/ajax_get_ids', 'Company\CityController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔
Route::any('company/city/ajax_selected', 'Company\CityController@ajax_selected');//ajax选择中记录/更新记录

//商品
Route::post('company/products/ajax_alist', 'Company\ProductController@ajax_alist');//ajax获得列表数据
Route::post('company/products/ajax_del', 'Company\ProductController@ajax_del');// 删除
Route::post('company/products/ajax_save', 'Company\ProductController@ajax_save');// 新加/修改
Route::post('company/products/ajax_get_child', 'Company\ProductController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('company/products/ajax_get_areachild', 'Company\ProductController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('company/products/ajax_import_staff','Company\ProductController@ajax_import'); // 导入员工


Route::post('company/products/import', 'Company\ProductController@import');// 导入excel
Route::post('company/products/ajax_get_ids', 'Company\ProductController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔


//提货活动
Route::post('company/activity/ajax_alist', 'Company\ActivityController@ajax_alist');//ajax获得列表数据
Route::post('company/activity/ajax_del', 'Company\ActivityController@ajax_del');// 删除
Route::any('company/activity/ajax_save', 'Company\ActivityController@ajax_save');// 新加/修改
Route::post('company/activity/ajax_get_child', 'Company\ActivityController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('company/activity/ajax_get_areachild', 'Company\ActivityController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('company/activity/ajax_import_staff','Company\ActivityController@ajax_import'); // 导入员工


Route::post('company/activity/import', 'Company\ActivityController@import');// 导入excel
Route::post('company/activity/ajax_get_ids', 'Company\ActivityController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔

//兑换码
Route::any('company/codes/ajax_alist', 'Company\CodesController@ajax_alist');//ajax获得列表数据
Route::post('company/codes/ajax_del', 'Company\CodesController@ajax_del');// 删除
Route::post('company/codes/ajax_save', 'Company\CodesController@ajax_save');// 新加/修改
Route::post('company/codes/ajax_get_child', 'Company\CodesController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('company/codes/ajax_get_areachild', 'Company\CodesController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('company/codes/ajax_import_staff','Company\CodesController@ajax_import'); // 导入员工


Route::post('company/codes/import', 'Company\CodesController@import');// 导入excel
Route::post('company/codes/ajax_get_ids', 'Company\CodesController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔

Route::post('company/codes/ajax_open_all', 'Company\CodesController@ajax_open_all');// 开启所有[根据活动id]
Route::post('company/codes/ajax_open', 'Company\CodesController@ajax_open');// 单个或批量开启
Route::post('company/codes/ajax_close_all', 'Company\CodesController@ajax_close_all');// 关闭所有[根据活动id]
Route::post('company/codes/ajax_close', 'Company\CodesController@ajax_close');// 单个或批量关闭

//收货地址
Route::any('company/addrs/ajax_alist', 'Company\AddrsController@ajax_alist');//ajax获得列表数据
Route::post('company/addrs/ajax_del', 'Company\AddrsController@ajax_del');// 删除
Route::post('company/addrs/ajax_save', 'Company\AddrsController@ajax_save');// 新加/修改
Route::post('company/addrs/ajax_send', 'Company\AddrsController@ajax_send');// 发货
Route::post('company/addrs/ajax_get_child', 'Company\AddrsController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('company/addrs/ajax_get_areachild', 'Company\AddrsController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('company/addrs/ajax_import_staff','Company\AddrsController@ajax_import'); // 导入员工


Route::post('company/addrs/import', 'Company\AddrsController@import');// 导入excel
Route::post('company/addrs/ajax_get_ids', 'Company\AddrsController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔


// 微网站
Route::any('site/ajax_login', 'Site\IndexController@ajax_login');// 登陆
Route::any('site/ajax_save', 'Site\IndexController@ajax_save');// 提货

//城市
//Route::any('site/city/ajax_alist', 'Site\CityController@ajax_alist');//ajax获得列表数据
//Route::post('site/city/ajax_del', 'Site\CityController@ajax_del');// 删除
//Route::post('site/city/ajax_save', 'Site\CityController@ajax_save');// 新加/修改
Route::post('site/city/ajax_get_child', 'Site\CityController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('site/city/ajax_get_areachild', 'Site\CityController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
//Route::post('site/city/ajax_import_staff','Site\CityController@ajax_import'); // 导入员工

//Route::post('site/city/import', 'Site\CityController@import');// 导入excel
Route::post('site/city/ajax_get_ids', 'Site\CityController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔
//Route::any('site/city/ajax_selected', 'Site\CityController@ajax_selected');//ajax选择中记录/更新记录

//收货地址
Route::any('site/addrs/ajax_save', 'Site\AddrsController@ajax_save');// 新加/修改

// 城市

Route::any('site/city/ajax_get_child', 'Site\CityController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]

// 微信登录回调
Route::any('site/wx/callback/{redisKey}', 'Site\WeChatController@callback');// 授权回调页

Route::any('site/pay/wechatNotify', 'Site\PayController@wechatNotify');// 支付结果通知--回调
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~有支付~~~~~~~~~~~~~~~~~结束~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// 微信相关的
// 一定是 Route::any, 因为微信服务端认证的时候是 GET, 接收用户消息时是 POST ！
Route::any('wx/wechat', 'WX\WeChatController@index');
Route::any('wx/jssdkconfig', 'WX\WeChatController@getJSSDKConfig');

Route::any('wx/test', 'WX\WeChatController@test');
// oauth
Route::any('wx/profile', 'WX\WeChatController@profile');// 需要授权才能访问的页面
Route::any('wx/callback', 'WX\WeChatController@callback');// 授权回调页

/*
Route::post('file/upload', function(\Illuminate\Http\Request $request) {
    if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
        $photo = $request->file('photo');
        $extension = $photo->extension();
        //$store_result = $photo->store('photo');
        $store_result = $photo->storeAs('photo', 'test.jpg');
        $output = [
            'extension' => $extension,
            'store_result' => $store_result
        ];
        print_r($output);exit();
    }
    exit('未获取到上传文件或上传过程出错');
});
*/
