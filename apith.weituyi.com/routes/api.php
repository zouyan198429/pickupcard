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

// 通用公共接口,不需要任何参数
// any(
// 手机站访问接口,其它参数 企业id-company_id; 生产单元id unit_id;
 Route::post('m/', 'TinyWeb\TinyWebController@index');// 首页生产单元信息
 Route::post('m/unit', 'TinyWeb\TinyWebController@unit');// 生产记录信息
 Route::post('m/input', 'TinyWeb\TinyWebController@input');// 投入品信息
 Route::post('m/input_info', 'TinyWeb\TinyWebController@inputInfo');// 投入品信息详情
 Route::post('m/company', 'TinyWeb\TinyWebController@company');// 企业信息
 Route::post('m/company/intro', 'TinyWeb\TinyWebController@companyIntro');// 企业信息-介绍
 Route::post('m/report', 'TinyWeb\TinyWebController@report');// 反馈

Route::post('m/create_label', 'TinyWeb\TinyWebController@create_label');// 生成防伪标签

// 农场主后台接口
// 通用接口
Route::post('comp/index', 'Comp\CommonController@index');// 首页-农场主后台
Route::post('comp/admin', 'Comp\CommonController@admin');// 首页-大后台
Route::post('comp/all', 'Comp\CommonController@all');// 获得所有列表接口
Route::post('comp/queryList', 'Comp\CommonController@queryList');// 获得列表接口-根据条件
Route::post('comp/list', 'Comp\CommonController@list');// 获得列表接口
Route::post('comp/info', 'Comp\CommonController@getInfo');// 获得id详情接口
Route::post('comp/infoQuery', 'Comp\CommonController@getInfoByQuery');// 获得条件详情接口 pagesize 1:返回一维数组,>1 返回二维数组
Route::post('comp/kv', 'Comp\CommonController@kv');// 获得键值对接口
Route::post('comp/attr', 'Comp\CommonController@attr');// 获得数据模型属性接口
Route::post('comp/exeMethod', 'Comp\CommonController@exeMethod');// 调用数据模型方法接口
Route::post('comp/businessDBAttr', 'Comp\CommonController@businessDBAttr');// 获得数据中间Business-DB层属性接口
Route::any('comp/exeBusinessDBMethod', 'Comp\CommonController@exeBusinessDBMethod');// 调用数据中间Business-DB层方法接口
Route::post('comp/businessAttr', 'Comp\CommonController@businessAttr');// 获得数据中间Business层属性接口
Route::post('comp/exeBusinessMethod', 'Comp\CommonController@exeBusinessMethod');// 调用数据中间Business层方法接口
Route::post('comp/add', 'Comp\CommonController@add');// 新加接口
Route::post('comp/addBath', 'Comp\CommonController@addBath');// 批量新加接口-data只能返回成功true:失败:false
Route::post('comp/addBathById', 'Comp\CommonController@addBathByPrimaryKey');// 批量新加接口-data返回成功的id数组
Route::post('comp/saveDecIncByQuery', 'Comp\CommonController@saveDecIncByQuery');// 自增自减接口,通过条件-data操作的行数
Route::post('comp/saveDecIncByArr', 'Comp\CommonController@saveDecIncByArr');// 批量自增自减接口,通过数组[二维]-data操作的行数数组
Route::post('comp/save', 'Comp\CommonController@save');// 修改接口
Route::any('comp/saveById', 'Comp\CommonController@saveById');// 通过id修改接口
Route::post('comp/saveBathById', 'Comp\CommonController@saveBathById');// 通过主健批量修改接口
Route::post('comp/del', 'Comp\CommonController@del');// 根据条件删除接口
Route::post('comp/sync', 'Comp\CommonController@sync');// 同步修改关系接口
Route::post('comp/detach', 'Comp\CommonController@detach');// 移除关系接口
Route::any('comp/getHistoryId', 'Comp\CommonController@getHistoryId');// 根据主表id，获得对应的历史表id
Route::post('comp/firstOrCreate', 'Comp\CommonController@firstOrCreate');//查找记录,或创建新记录[没有找到]
Route::post('comp/updateOrCreate', 'Comp\CommonController@updateOrCreate');//已存在则更新，否则创建新模型--持久化模型，所以无需调用 save()
Route::any('comp/compareHistoryOrUpdateVersion', 'Comp\CommonController@compareHistoryOrUpdateVersion');// 对比主表和历史表是否相同，相同：不更新版本号，不同：版本号+1

// 大后台
//Route::post('work/test', 'CompanyWorkController@test');//测试
//Route::post('work/add_init', 'CompanyWorkController@addInit');//工单添加页初始数据
//Route::post('work/add_save', 'CompanyWorkController@add_save');//工单添加/修改
//Route::post('work/mobile_index', 'CompanyWorkController@mobile_index');//手机站首页初始化数据
//Route::post('work/workReSend', 'CompanyWorkController@workReSend');//工单重新指定
//Route::post('work/workSure', 'CompanyWorkController@workSure');//确认工单
//Route::post('work/workWin', 'CompanyWorkController@workWin');//结单
//Route::post('work/workReply', 'CompanyWorkController@workReply');//回访
//Route::post('work/workCount', 'CompanyWorkController@workCount');//工单统计
// 统计
//Route::post('work/statusCount', 'CompanyWorkController@statusCount');//工单状态统计

// 试题
//Route::any('subject/test', 'CompanySubjectController@test');//测试
//Route::any('subject/add_save', 'CompanySubjectController@add_save');//试题添加/修改
//Route::any('subject/getSubjectByIds', 'CompanySubjectController@getSubjectByIds');//通过id获得试题
//Route::any('subject/saveExam', 'CompanySubjectController@saveExam');//保存考试

// 员工
//Route::post('staff/bathImport', 'CompanyStaffController@bathImport');//批量导入
//Route::any('staff/adminStaff', 'CompanyStaffController@adminStaff');//管理员转为员工
//Route::post('staff/getHistoryStaff', 'CompanyStaffController@getHistoryStaff');//获得操作员工历史
//Route::any('staff/getStaffByIds', 'CompanyStaffController@getStaffByIds');//ajax添加员工地址-根据试卷id,多个,号分隔

// 问题反馈
//Route::post('problem/add_save', 'CompanyProblemController@add_save');//问题反馈添加/修改
//
//Route::post('proUnit/countLabels', 'CompanyProUnitController@countLabels');// 统计生产单元下的标签

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('users', function () {
    return config('public.apiUrl');
    return App\Models\Company::paginate();
});


Route::get('test/runbuy', 'TestbController@runbuy');// 测试
//Route::get('test/index', 'TestbController@index');// 测试
Route::any('test/index', 'TestController@index');//测试
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