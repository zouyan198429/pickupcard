<?php

namespace App\Http\Controllers\Admin;

use App\Business\Controller\API\RunBuy\CTAPIStaffBusiness;
use App\Http\Controllers\WorksController;
use App\Services\Request\CommonRequest;
use App\Services\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IndexController extends BasicController
{
    public static $VIEW_NAME = '';// 视图栏目文件夹目录名称

    public function test(){
//        $this->company_id = 1;
//        $cityList = CTAPILrChinaCityBusiness::getList($request, $this, 2 + 4, [], []);
//        pr($cityList);

        // 测试数据模型属性
//        $attr = APIRunBuyRequest::getAttrApi('RunBuy\LrChinaCity', 'status_arr', 0, 1 );
//        pr($attr);
        // 测试调用数据模型方法
//        $tableName = APIRunBuyRequest::exeMethodApi('RunBuy\LrChinaCity', 'getTable', [], 1 );
//        pr($tableName);

        // 获得数据中间层属性
//        $attr = APIRunBuyRequest::getBusinessAttrApi('RunBuy\LrChinaCity', 'attrTest', 1, 1 );
//        pr($attr);
        // 测试调用数据模型方法
//        $tableName = APIRunBuyRequest::exeBusinessMethodApi('RunBuy\LrChinaCity', 'testMethod', ['1111','222'], 1 );
//        pr($tableName);

    }

    /**
     * 首页
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('' . static::$VIEW_PATH . '.index', $reDataArr);
    }

    /**
     * ajax获得模型表的缓存时间；没有缓存时间-则返回当前时间
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function ajax_getTableUpdateTime(Request $request){
//        return $this->exeDoPublicFun($request, 0, 4,'', true, '', [], function (&$reDataArr) use ($request){
//            $module_name = CommonRequest::get($request, 'module_name');// QualityControl\CTAPIStaff
//            if(empty($module_name)) throws('参数【module_name】不能为空！');
//
//            $objClass = 'App\\Business\\Controller\API\\' . $module_name  . 'Business';// 'App\Business\Controller\API\QualityControl\CTAPIStaffBusiness';
//            if (! class_exists($objClass )) {
//                throws('参数[module_name]类不存在！');
//            }
//            // 空或 string(29) "2020-09-04 15:00:03!!!9840900"  [true, 4]
//            $tableUpdateTime = $objClass::exeMethodCT($request, $this, '', 'getTableUpdateTimeCache', [], 1, 1);
//            if(!empty($tableUpdateTime)) list($tableUpdateTime, $cacheMsecint) = array_values(Tool::getTimeMsec($tableUpdateTime));
//            if(empty($tableUpdateTime)) $tableUpdateTime = date('Y-m-d H:i:s');
//            return ajaxDataArr(1, $tableUpdateTime, '');
//        });
//    }

    /**
     * 登陆
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function login(Request $request)
    {
        $reDataArr = $this->reDataArr;

        Log::info('日志测试---login页',[]);
        return view('' . static::$VIEW_PATH . '.login', $reDataArr);
    }

    /**
     * 修改密码
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function password(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        $user_info = $this->user_info;
        $reDataArr = array_merge($reDataArr, $user_info);
        return view('' . static::$VIEW_PATH . '.admin.password', $reDataArr);
    }

    /**
     * 显示
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function info(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        $user_info = $this->user_info;

        $reDataArr['adminType'] =  CTAPIStaffBusiness::$adminType;
        $reDataArr['defaultAdminType'] = $user_info['admin_type'] ?? 0;// 列表页默认状态
        $reDataArr = array_merge($reDataArr, $user_info);
        return view('' . static::$VIEW_PATH . '.admin.info', $reDataArr);
    }

    /**
     * err404
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function err404(Request $request)
    {
        $reDataArr = $this->reDataArr;
        return view('404', $reDataArr);
    }

    /**
     * ajax保存数据
     *
     * @param Request $request
     * @return array
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_login(Request $request)
    {
        // $this->InitParams($request);
        // $company_id = $this->company_id;

        return CTAPIStaffBusiness::login($request, $this,static::$LOGIN_ADMIN_TYPE);

    }

    /**
     * 注销
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function logout(Request $request)
    {
        // $this->InitParams($request);
        CTAPIStaffBusiness::loginOut($request, $this);
        $reDataArr = $this->reDataArr;
        return redirect('admin/login');
    }

    /**
     * ajax修改密码
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_password_save(Request $request)
    {
        $this->InitParams($request);
        return CTAPIStaffBusiness::modifyPassWord($request, $this);
    }

    /**
     * ajax 修改设置
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_info_save(Request $request)
    {
        $this->InitParams($request);

        $id = $this->user_id;
        $company_id = $this->company_id;
        $admin_username = CommonRequest::get($request, 'admin_username');
        $mobile = CommonRequest::get($request, 'mobile');
        $real_name = CommonRequest::get($request, 'real_name');
        $sex = CommonRequest::getInt($request, 'sex');
        $tel = CommonRequest::get($request, 'tel');
        $qq_number = CommonRequest::get($request, 'qq_number');

        $saveData = [
            'admin_username' => $admin_username,
            'mobile' => $mobile,
            'real_name' => $real_name,
            'sex' => $sex,
            'gender' => $sex,
            'tel' => $tel,
            'qq_number' => $qq_number,
        ];
        $resultDatas = CTAPIStaffBusiness::replaceById($request, $this, $saveData, $id, true);
        return ajaxDataArr(1, $resultDatas, '');
    }
}
