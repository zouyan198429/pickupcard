<?php

namespace App\Http\Controllers;

use App\Services\DB\CommonDB;
use App\Services\Request\CommonRequest;
use App\Services\Tool;
use Illuminate\Http\Request;

class WorksController extends BaseController
{

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
    }

    // 登陆信息
    // 获得生产单元信息
//    public function getUnits($user_info = []){
//        $proUnits = [];
//        // $user_info = $this->user_info;
//        $user_id = $user_info['id'] ?? 0;
//        $company_id = $user_info['company_info']['id'] ?? 0;//$this->company_id;
//        // 判断是否在VIP有效期内-- 没有有效期，则处理[重新登录]
//        $company_vipbegin = $user_info['company_info']['company_vipbegin'] ?? '';
//        $company_vipend = $user_info['company_info']['company_vipend'] ?? '';
//        //判断开始
//        $comp_begin_time_unix = judgeDate($company_vipbegin);
//        if($comp_begin_time_unix === false){
//            // ajaxDataArr(0, null, 'VIP开始日期不是有效日期');
//            // 删除登陆状态
//            $resDel = $this->delUserInfo();
//            return $proUnits;
//        }
//
//        //判断期限结束
//        $comp_end_time_unix = judgeDate($company_vipend);
//        if($comp_end_time_unix === false){
//            // ajaxDataArr(0, null, 'VIP结束日期不是有效日期');
//            // 删除登陆状态
//            $resDel = $this->delUserInfo();
//            return $proUnits;
//        }
//
//        if($comp_end_time_unix < $comp_begin_time_unix){
//            // ajaxDataArr(0, null, 'VIP结束日期不能小于开始日期');
//            // 删除登陆状态
//            //$resDel = $this->delUserInfo();
//            //return $proUnits;
//        }
//        $nowTime = time();
//        if($nowTime < $comp_begin_time_unix){
//            // ajaxDataArr(0, null, 'VIP还未到开始日期，不能新加生产单元!');
//            // 删除登陆状态
//            //$resDel = $this->delUserInfo();
//            //return $proUnits;
//        }
//        if($nowTime > $comp_end_time_unix){
//            // ajaxDataArr(0, null, 'VIP已过期，不能新加生产单元!');
//            // 删除登陆状态
//            //$resDel = $this->delUserInfo();
//            // return $proUnits;
//        }
//
//        // 判断用户状态
//        $relations = "";
//        $userInfo = CommonBusiness::getinfoApi('CompanyAccounts', '', $relations, 0 , $user_id,1);
//
//        $account_status = $userInfo['account_status'] ?? 1;
//        if($account_status != 0){
//            // 删除登陆状态
//            $resDel = $this->delUserInfo();
//            return $proUnits;
//        }
//        // 获得当前所有的
//        //$relations = '';// 关系
//        //if(!$this->save_session){
//            $relations =['siteResources'];
//        //}
//        $queryParams = [
//            'where' => [
//                ['company_id', $company_id],
//            ],
//            'orderBy' => ['id'=>'desc'],
//        ];// 查询条件参数
//        $proUnitList = CommonBusiness::ajaxGetAllList('CompanyProUnit', '', $company_id,$queryParams ,$relations );
//
//        foreach($proUnitList as $v){
//            $status = $v['status'] ?? 0;
//            if($this->save_session && (! in_array($status,[1]))){//后台
//                continue;
//            }elseif( (! $this->save_session) && (! in_array($status,[1]))){// 小程序[0,1]
//                continue;
//            }
//            $begin_time = $v['begin_time'] ?? '';
//            $end_time = $v['end_time'] ?? '';
//            //判断开始
//            $begin_time_unix = judgeDate($begin_time);
//            if($begin_time_unix === false){
//                continue;
//                // ajaxDataArr(0, null, '开如日期不是有效日期');
//            }
//
//            //判断期限结束
//            $end_time_unix = judgeDate($end_time);
// //           if($end_time_unix === false){
// //               continue;
//                // ajaxDataArr(0, null, '结束日期不是有效日期');
// //           }
//
//            if( $end_time_unix !== false && $end_time_unix < $begin_time_unix){
//                continue;
//                // ajaxDataArr(0, null, '结束日期不能小于开始日期');
//            }
//            $time = time();
//            if($end_time_unix !== false && $end_time_unix < $time ){// 过期
//                continue;
//            }
//
//            $tem = [
//                'unit_id' => $v['id'],
//                'site_pro_unit_id' => $v['site_pro_unit_id'],
//                'pro_input_name' => $v['pro_input_name'],
//                'status' => $v['status'],
//                'status_text' => $v['status_text'],
//                'begin_time' => judge_date($v['begin_time'],'Y-m-d'),
//                'end_time' => judge_date($v['end_time'],'Y-m-d'),
//            ];
//
//            //if(! $this->save_session) {
//                // $resource_url = $v['company_pro_config']['site_resources'][0]['resource_url'] ?? '';
//                $resource_url = $v['site_resources'][0]['resource_url'] ?? '';
//                $tem['resource_url'] = $resource_url;
//                CommonBusiness::resourceUrl($tem, 1);
//
//            //}
//            $proUnits[$v['id']] = $tem;
//        }
//        return $proUnits;
//    }

}
