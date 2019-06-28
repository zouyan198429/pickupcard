<?php

namespace App\Http\Controllers;

use App\Services\Request\CommonRequest;
use App\Services\Tool;
use Illuminate\Http\Request;

class AdminController extends BaseController
{

    public function InitParams(Request $request)
    {
        if (!session_id()) session_start();// 初始化session
        $userInfo = $_SESSION['userInfo']?? [];
        if(empty($userInfo)) {
            throws('非法请求！');
//            if(isAjax()){
//                ajaxDataArr(0, null, '非法请求！');
//            }else{
//                redirect('login');
//            }
        }
        $company_id = $userInfo['company_id'] ?? null;//CommonRequest::getInt($request, 'company_id');
        if(empty($company_id) || (!is_numeric($company_id))){
            throws('非法请求！');
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
        // $this->operate_staff_id_history = $this->user_id;
        // $company_id = config('public.company_id');
        $this->company_id =  $company_id;//'99999';//

        $real_name = $userInfo['real_name'] ?? '';
        $this->reDataArr['baseArr']['real_name'] = $real_name;
    }

}
