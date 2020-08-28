<?php

namespace App\Http\Controllers\Web;

use App\Business\Controller\API\RunBuy\CTAPIActivityCodeBusiness;
use App\Business\Controller\API\RunBuy\CTAPICityBusiness;
use App\Business\Controller\API\RunBuy\CTAPIDeliveryAddrBusiness;
use App\Services\Request\CommonRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AddrsController extends BaseWebController
{
    public static $VIEW_NAME = 'addrs';// 视图栏目文件夹目录名称

    /**
     * 首页
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function add(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        // 省
        $reDataArr['province_kv'] = CTAPICityBusiness::getCityByPid($request, $this,  0, 1);
        $reDataArr['defaultProvince'] = -1;
        // 状态
        $reDataArr['status'] =  CTAPIDeliveryAddrBusiness::$statusArr;
        $reDataArr['defaultStatus'] = -1;// 默认状态
        return view('' . static::$VIEW_PATH . '.' . static::$VIEW_NAME. '.add', $reDataArr);
    }

    /**
     * ajax保存数据
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_save(Request $request)
    {
        $this->InitParams($request);
        $codeInfo = $this->user_info;
        $code_id = $codeInfo['id'];
        // 获得兑换码信息
        $codeInfo = CTAPIActivityCodeBusiness::getInfoData($request, $this, $code_id, ['activity_id'],  ['activityInfo'], 1);
        $activity_info = $codeInfo['activity_info'] ?? [];
        $activity_tips = $activity_info['activity_tips'] ?? '操作成功!！';

        $id = CommonRequest::getInt($request, 'id');
        // CommonRequest::judgeEmptyParams($request, 'id', $id);
//        $work_num = CommonRequest::get($request, 'work_num');
//        $department_id = CommonRequest::getInt($request, 'department_id');
//        $group_id = CommonRequest::getInt($request, 'group_id');
//        $position_id = CommonRequest::getInt($request, 'position_id');
        $real_name = CommonRequest::get($request, 'real_name');
//        $sex = CommonRequest::getInt($request, 'sex');
//        $account_status = CommonRequest::getInt($request, 'account_status');
//        $mobile = CommonRequest::get($request, 'mobile');
        $tel = CommonRequest::get($request, 'tel');
//        $qq_number = CommonRequest::get($request, 'qq_number');
        $province_id = CommonRequest::getInt($request, 'province_id');
        $city_id = CommonRequest::getInt($request, 'city_id');
        $area_id = CommonRequest::getInt($request, 'area_id');
        $addr = CommonRequest::get($request, 'addr');
//        $status = CommonRequest::getInt($request, 'status');
//        $latitude = CommonRequest::get($request, 'latitude');
//        $longitude = CommonRequest::get($request, 'longitude');
//        $admin_username = CommonRequest::get($request, 'admin_username');
//        $admin_password = CommonRequest::get($request, 'admin_password');
//        $sure_password = CommonRequest::get($request, 'sure_password');

        $saveData = [
//            'admin_type' => 1,
//            'work_num' => $work_num,
//            'department_id' => $department_id,
//            'group_id' => $group_id,
//            'position_id' => $position_id,
            'real_name' => $real_name,
//            'sex' => $sex,
//            'gender' => $sex,
//            'account_status' => $account_status,
//            'mobile' => $mobile,
            'tel' => $tel,
//            'qq_number' => $qq_number,
            'seller_id' => $codeInfo['seller_id'] ?? 0,
            'province_id' => $province_id,
            'city_id' => $city_id,
            'area_id' => $area_id,
            'addr' => $addr,
//            'status' => $status,
//            'latitude' => $latitude,
//            'longitude' => $longitude,
//            'admin_username' => $admin_username,
        ];
//        if($admin_password != '' || $sure_password != ''){
//            if ($admin_password != $sure_password){
//                return ajaxDataArr(0, null, '密码和确定密码不一致！');
//            }
//            $saveData['admin_password'] = $admin_password;
//        }

//        if($id <= 0) {// 新加;要加入的特别字段
//            $addNewData = [
//                // 'account_password' => $account_password,
//            ];
//            $saveData = array_merge($saveData, $addNewData);
//        }
        $resultDatas = CTAPIDeliveryAddrBusiness::addAddr($request, $this, $saveData, $id, $this->code_id, true);
        return ajaxDataArr(1, ['result' => $resultDatas, 'activity_tips' => $activity_tips], '');
    }
}
