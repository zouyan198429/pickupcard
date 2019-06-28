<?php

namespace App\Http\Controllers\Admin;

use App\Business\Controller\API\RunBuy\CTAPIStaffBusiness;
use App\Http\Controllers\WorksController;
use App\Services\Request\CommonRequest;
use Illuminate\Http\Request;

class IndexController extends WorksController
{

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
        return view('admin.index', $reDataArr);
    }

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
        return view('admin.login', $reDataArr);
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
        return view('admin.admin.password', $reDataArr);
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
        return view('admin.admin.info', $reDataArr);
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

        return CTAPIStaffBusiness::login($request, $this,1);

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
