<?php

namespace App\Http\Controllers\Site;

use App\Business\Controller\API\RunBuy\CTAPIActivityCodeBusiness;
use App\Business\Controller\API\RunBuy\CTAPICityBusiness;
use App\Business\Controller\API\RunBuy\CTAPIDeliveryAddrBusiness;
use App\Business\Controller\API\RunBuy\CTAPIStaffBusiness;
use App\Services\pay\weixin\easyWechatPay;
use App\Services\Request\CommonRequest;
use App\Services\Tool;
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
    public function add(Request $request, $redisKey = '')
    {
        $this->judgeWeixinVisit();// 判断是否微信内浏览器
        if(empty($redisKey)) throws('请先提交密码');
        $request->merge(['redisKey' => $redisKey]);
        $this->InitParams($request);

        // 判断是否登录，需要登录了
        $openid = $this->autoSiteGetOpenid($request, $redisKey);//判断是否在微信内及是否已经获得用户微信openid[登录]
        if(!is_string($openid)) return $openid;// 需要重新登录--微信登录

        $reDataArr = $this->reDataArr;
        $code_id = $this->code_id;
        // 获得兑换码信息
        $codeInfo = CTAPIActivityCodeBusiness::getInfoData($request, $this, $code_id, ['product_id', 'activity_id'],  ['productInfo', 'activityInfo.siteResources'], 1);

        // 资源url
        $resource_list = [];
        if(isset($codeInfo['activity_info'])){
            $activity_info = $codeInfo['activity_info'] ?? [];
            $codeInfo['activity_info']['pay_price'] = Tool::formatFloat($activity_info['price'] + $activity_info['freight_price'] + $activity_info['insured_price']);;
            Tool::resourceUrl($activity_info, 2);
            $resource_list = Tool::formatResource($activity_info['site_resources'], 2);

            if(isset($codeInfo['activity_info']['site_resources']) ) unset($codeInfo['activity_info']['site_resources']);
//            unset($codeInfo['activity_info']);
        }
        $reDataArr['resource_list'] = $resource_list;

        $reDataArr['info'] =  $codeInfo;
        // 省
        $reDataArr['province_kv'] = CTAPICityBusiness::getCityByPid($request, $this,  0, 1);
        $reDataArr['defaultProvince'] = -1;
        // 状态
        $reDataArr['status'] =  CTAPIDeliveryAddrBusiness::$statusArr;
        $reDataArr['defaultStatus'] = -1;// 默认状态

        $reDataArr['redisKey'] =  $redisKey;
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
        $this->judgeWeixinVisit();// 判断是否微信内浏览器

        $redisKey = CommonRequest::get($request, 'redisKey');
        if(empty($redisKey)) throws('请先提交密码');
        $request->merge(['redisKey' => $redisKey]);
        $this->InitParams($request);

        // 判断是否登录，需要登录了
        $openid = $this->autoSiteGetOpenid($request, $redisKey);//判断是否在微信内及是否已经获得用户微信openid[登录]
        if(!is_string($openid)) return $openid;// 需要重新登录--微信登录

        $codeInfo = $this->user_info;
        $code_id = $codeInfo['id'];
        // 获得兑换码信息
        $codeNewInfo = CTAPIActivityCodeBusiness::getInfoData($request, $this, $code_id, ['activity_id'],  ['activityInfo'], 1);
        $activity_info = $codeNewInfo['activity_info'] ?? [];// 活动
        $activity_tips = $activity_info['activity_tips'] ?? '操作成功!！';

        $pay_price = Tool::formatFloat($activity_info['price'] + $activity_info['freight_price'] + $activity_info['insured_price']);;


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

        // 获得用户信息
        $queryParams = [
            'where' => [
                // ['company_id',$company_id],
                ['admin_type',8],
                ['mp_openid', $openid],
            ],
//            'whereIn' => [
//                'admin_type' => array_keys(self::$adminType),
//            ],
            // 'select' => ['id','company_id','admin_username','real_name','admin_type'],
            // 'limit' => 1
        ];
        $userInfo = CTAPIStaffBusiness::getInfoQuery($request, $this, '', 0, 1, $queryParams, [], 1);
        if(empty($userInfo)) throws('用户记录不存在！');
        // 生成订单号
        // 重新发起一笔支付要使用原订单号，避免重复支付；已支付过或已调用关单、撤销（请见后文的API列表）的订单号不能重新发起支付。--支付未成功的订单号，可以重新发起支付
        $orderNum = CTAPIActivityCodeBusiness::createSn($request, $this , 1);
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
            'order_no' => $orderNum,// 订单号
            'user_id' => $userInfo['id'],// 用户id
            'pay_price' => $pay_price,// 支付费用
            // 'pay_time' => $activity_info['aaaaa'],// 付款时间
            'pay_status' => ($pay_price <= 0) ? 1 : 2,// 付款状态1无需付款2待支付4支付失败8已付款
            // 'pay_no' => $activity_info['aaaaa'],// 支付单号(第三方)
            'tag_price' => $activity_info['tag_price'],// 原价格【吊牌价-不参与付费】
            'price' => $activity_info['price'],// 商品价【参与付费】
            'freight_price' => $activity_info['freight_price'],// 快递费【参与付费】
            'insured_price' => $activity_info['insured_price'],// 保价费【参与付费】
        ];
        $config = [];
        $pay_type = 1;// 1有微信订单 2 无微信订单
        if($pay_price <= 0){
            $pay_type = 2;
            $saveData['pay_time'] = date('Y-m-d H:i:s');
        }else{
            $app = app('wechat.payment.mxpay');
            $params = [
                'body' => $activity_info['activity_name'] . '领取商品[' .  $codeInfo['product_name'] . ']微信支付',
                'out_trade_no' => $orderNum,
                'total_fee' => ceil($pay_price * 100),
                // 'spbill_create_ip' => '123.12.12.123', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
                // 'notify_url' => 'https://pay.weixin.qq.com/wxpay/pay.action', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
                'openid' => $openid, // 'oUpF8uMuAJO_M2pxb1Q9zNjWeS6o',
            ];
            try{
                $result = easyWechatPay::miniProgramunify($app, $params, 4);
            } catch ( \Exception $e) {
                throws('失败；信息[' . $e->getMessage() . ']');
            }
            // 去掉敏感信息
//            Tool::formatArrKeys($result, Tool::arrEqualKeyVal(['timeStamp', 'nonceStr', 'package', 'signType', 'paySign']), true );
//            pr($result);
            $prepay_id = $result['prepay_id'];
            $jssdk = $app->jssdk;
            $json = $jssdk->bridgeConfig($prepay_id, false); // 返回 json 字符串，如果想返回数组，传第二个参数 false
//
//            $config = $result;//$jssdk->sdkConfig($prepay_id); // 返回数组

        }
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
        return ajaxDataArr(1, ['result' => $resultDatas, 'activity_tips' => $activity_tips, 'pay_type' => $pay_type , 'pay_config' => $json], '');
    }

    /**
     * 支付成功
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function payOK(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        $code_id = $this->code_id;
        // 获得兑换码信息
//        $codeInfo = CTAPIActivityCodeBusiness::getInfoData($request, $this, $code_id, ['product_id', 'activity_id'],  ['productInfo', 'activityInfo.siteResources'], 1);
//
//        // 资源url
//        $resource_list = [];
//        if(isset($codeInfo['activity_info'])){
//            $activity_info = $codeInfo['activity_info'] ?? [];
//            $codeInfo['activity_info']['pay_price'] = Tool::formatFloat($activity_info['price'] + $activity_info['freight_price'] + $activity_info['insured_price']);;
//            Tool::resourceUrl($activity_info, 2);
//            $resource_list = Tool::formatResource($activity_info['site_resources'], 2);
//
//            if(isset($codeInfo['activity_info']['site_resources']) ) unset($codeInfo['activity_info']['site_resources']);
////            unset($codeInfo['activity_info']);
//        }
//        $reDataArr['resource_list'] = $resource_list;
//
//        $reDataArr['info'] =  $codeInfo;
        $reDataArr['code_id'] =  $code_id;
        $reDataArr['code'] =  $this->user_info['code'];
        return view('' . static::$VIEW_PATH . '.' . static::$VIEW_NAME. '.payOK', $reDataArr);
    }
}
