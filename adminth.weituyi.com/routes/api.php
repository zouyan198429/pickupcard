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

// ----大后台
// admin
// 上传图片
Route::post('admin/upload', 'Admin\UploadController@index');
Route::post('admin/upload/ajax_del', 'Admin\UploadController@ajax_del');// 根据id删除文件

//// 登陆
Route::any('admin/ajax_login', 'Admin\IndexController@ajax_login');// 登陆
Route::post('admin/ajax_password_save', 'Admin\IndexController@ajax_password_save');// 修改密码
Route::any('admin/ajax_info_save', 'Admin\IndexController@ajax_info_save');// 修改设置

//后台--管理员
Route::any('admin/staff/ajax_alist', 'Admin\StaffController@ajax_alist');//ajax获得列表数据
Route::post('admin/staff/ajax_del', 'Admin\StaffController@ajax_del');// 删除
Route::any('admin/staff/ajax_save', 'Admin\StaffController@ajax_save');// 新加/修改
Route::post('admin/staff/ajax_get_child', 'Admin\StaffController@ajax_get_child');// 根据部门id,小组id获得子类员工数组[kv一维数组]
Route::post('admin/staff/ajax_get_areachild', 'Admin\StaffController@ajax_get_areachild');// 根据区县id,街道id获得子类员工数组[kv一维数组]
Route::post('admin/staff/ajax_import_staff','Admin\StaffController@ajax_import'); // 导入员工

Route::post('admin/staff/import', 'Admin\StaffController@import');// 导入excel
Route::post('admin/staff/ajax_get_ids', 'Admin\StaffController@ajax_get_ids');// 获得查询所有记录的id字符串，多个逗号分隔


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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
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
