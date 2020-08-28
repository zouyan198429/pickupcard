<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\BaseController;
use App\Services\Request\CommonRequest;
use App\Services\Request\API\Sites\APIRunBuyRequest;
use App\Services\Tool;
use Illuminate\Http\Request;

class BaseWebController extends BaseController
{
    public static $VIEW_PATH = 'site';// 视图文件夹目录名称
    public static $VIEW_NAME = '';// 视图栏目文件夹目录名称
    public static $LOGIN_ADMIN_TYPE = 8;// 当前登录的用户类型1平台2企业4管理员8个人


    public $code_id = null;
    public function InitParams(Request $request)
    {
        // 获得redisKey 参数值
        $temRedisKey = CommonRequest::get($request, 'redisKey');
        if(isAjax()){
            $this->source = 2;
        }
        if(!empty($temRedisKey)){// 不为空，则是从小程序来的
            $this->redisKey = $temRedisKey;
            $this->save_session = false;
            $this->source = 3;
        }
        //session_start(); // 初始化session
        //$userInfo = $_SESSION['userInfo']?? [];
        $codeInfo = $this->getUserInfo();// 保存的是code表的单条记录
        if(empty($codeInfo)) {
            throws('非法请求！', $this->source);
//            if(isAjax()){
//                ajaxDataArr(0, null, '非法请求！');
//            }else{
//                redirect('login');
//            }
        }
        $company_id = $codeInfo['id'] ?? null;//$userInfo['company_id'] ?? null;//CommonRequest::getInt($request, 'company_id');
        if(empty($company_id) || (!is_numeric($company_id))){
            throws('非法请求！', $this->source);
//            if(isAjax()){
//                ajaxDataArr(0, null, '非法请求！');
//            }else{
//                redirect('login');
//            }
        }
        $this->reDataArr['codeInfo'] = $codeInfo;

        $this->user_info =$codeInfo;
        $this->user_id = 0;// $codeInfo['id'] ?? '';
        $this->operate_staff_id = $this->user_id;
        $this->operate_staff_id_history = $this->user_id;
        $this->company_id = $company_id;


        $this->code_id = $codeInfo['id'] ?? '';

        /*
        $userInfo = $this->getUserInfo();
        // pr($userInfo);
        if(empty($userInfo)) {
            throws('非法请求！', $this->source);
//            if(isAjax()){
//                ajaxDataArr(0, null, '非法请求！');
//            }else{
//                redirect('login');
//            }
        }
        $company_id = $userInfo['id'] ?? null;//$userInfo['company_id'] ?? null;//CommonRequest::getInt($request, 'company_id');
        if(empty($company_id) || (!is_numeric($company_id))){
            throws('非法请求！', $this->source);
//            if(isAjax()){
//                ajaxDataArr(0, null, '非法请求！');
//            }else{
//                redirect('login');
//            }
        }
        // Tool::judgeInitParams('company_id', $company_id);
        $this->user_info =$userInfo;
        $this->user_id = $userInfo['id'] ?? '';
        $this->operate_staff_id = $this->user_id;
        $this->operate_staff_id_history = $this->user_id;
        $this->company_id = $company_id;


        $this->admin_type = $userInfo['admin_type'] ?? 0;
        $this->city_site_id = $userInfo['city_site_id'] ?? 0;
        $this->city_partner_id = $userInfo['city_partner_id'] ?? 0;
        $this->seller_id = $userInfo['seller_id'] ?? 0;
        $this->shop_id = $userInfo['shop_id'] ?? 0;

        $real_name = $userInfo['real_name'] ?? '';
        $mobile = $userInfo['mobile'] ?? '';
        if(empty($real_name)){
            $real_name = $mobile;
        }
        $this->reDataArr['baseArr']['real_name'] = $real_name;
        $this->reDataArr['qqMapsKey'] = config('public.qqMapsKey');// 腾讯地图Key鉴权
        // 每*分钟，自动更新一下左则
//        $recordTime  = time();
//        $difTime = 60 * 5 ;// 5分钟
//        $modifyTime = $userInfo['modifyTime'] ?? ($recordTime - $difTime - 1);
//        if($this->save_session &&  ($modifyTime + $difTime) <=  $recordTime){// 后台
//            $proUnits = $this->getUnits($this->user_info);
//            $userInfo['proUnits'] = $proUnits;
//            $userInfo['modifyTime'] = time();
//            $redisKey = $this->setUserInfo($userInfo, -1);
//        }

        */
    }
}
