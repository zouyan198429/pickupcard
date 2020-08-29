<?php
// 人员操作记录
namespace App\Business\DB\RunBuy;

use App\Services\Tool;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class DeliveryAddrDBBusiness extends BasePublicDBBusiness
{
    public static $model_name = 'RunBuy\DeliveryAddr';
    public static $table_name = 'delivery_addr';// 表名称

    /**
     * 根据兑换单号id及收货信息，完成在线提货功能
     *
     * @param array $saveData 要保存或修改的数组
     * @param int  $company_id 企业id
     * @param int $id id
     * @param int $code_id 兑换单号id
     * @param int $operate_staff_id 操作人id
     * @param int $modifAddOprate 修改时是否加操作人，1:加;0:不加[默认]
     * @return  int 记录id值，
     * @author zouyan(305463219@qq.com)
     */
    public static function replaceById($saveData, $company_id, &$id, $code_id = 0, $operate_staff_id = 0, $modifAddOprate = 0){

        if(isset($saveData['real_name']) && empty($saveData['real_name'])  ){
            throws('收货人不能为空！');
        }

        if(isset($saveData['tel']) && empty($saveData['tel'])  ){
            throws('收货电话不能为空！');
        }

        if(isset($saveData['addr']) && empty($saveData['addr'])  ){
            throws('收货地址不能为空！');
        }
        // 根据兑换单号，获得详情

        $codeInfo = ActivityCodeDBBusiness::getInfo($code_id, [], ['activityInfo'])->toArray();
        if(empty($codeInfo))  throws('兑换码记录不存在！');
        if($codeInfo['status'] == 2)  throws('此兑换码已兑换！');
        if($codeInfo['status'] == 4)  throws('此兑换码已过期！');
        if($codeInfo['status'] != 1 )  throws('此兑换码非待兑换状态！');

        $activityInfo = $codeInfo['activity_info'] ?? [];
        if(empty($activityInfo))  throws('提货活动不存在！');
        if($activityInfo['status'] == 1)  throws('提货活动未开始！');
        if($activityInfo['status'] == 4)  throws('提货活动已结束！');
        if($activityInfo['status'] != 2 )  throws('提货活动非进行状态！不可在线提货!');

        $begin_time = $activityInfo['begin_time'];
        $end_time = $activityInfo['end_time'];
        $used_num = $activityInfo['used_num'];

        $product_id_history = $codeInfo['product_id_history'];
        $saveData['product_id_history'] = $product_id_history;

        $saveData['product_id'] = $codeInfo['product_id'];

        $activity_id = $codeInfo['activity_id'];
        $saveData['activity_id'] = $activity_id;
        $saveData['code_id'] = $codeInfo['id'];
        $saveData['code'] = $codeInfo['code'];
        if($id <= 0){
            $saveData['status'] = 1;// 状态1未发货2已发货4已收货
            $saveData['order_time'] = date('Y-m-d H:i:s');
        }

         DB::beginTransaction();
        try {
            // 省id历史
            if( isset($saveData['province_id']) && $saveData['province_id'] > 0 ){
                $saveData['province_id_history'] = CityDBBusiness::getIdHistory($saveData['province_id']);
            }
            // 市id历史
            if( isset($saveData['city_id']) && $saveData['city_id'] > 0 ){
                $saveData['city_id_history'] = CityDBBusiness::getIdHistory($saveData['city_id']);
            }
            // 区县id历史
            if( isset($saveData['area_id']) && $saveData['area_id'] > 0 ){
                $saveData['area_id_history'] = CityDBBusiness::getIdHistory($saveData['area_id']);
            }

            $isModify = false;
            $operate_staff_id_history = 0;

            if($id > 0){
                $isModify = true;
                // 判断权限
                //            $judgeData = [
                //                'company_id' => $company_id,
                //            ];
                //            $relations = '';
                //            static::judgePower($id, $judgeData , $company_id , [], $relations);
                if($modifAddOprate) static::addOprate($saveData, $operate_staff_id,$operate_staff_id_history);



            }else {// 新加;要加入的特别字段
                //            $addNewData = [
                //                'company_id' => $company_id,
                //            ];
                //            $saveData = array_merge($saveData, $addNewData);
                // 加入操作人员信息
                static::addOprate($saveData, $operate_staff_id,$operate_staff_id_history);
            }

            // 新加或修改
            if($id <= 0){// 新加
                $resultDatas = static::create($saveData);
                $id = $resultDatas['id'] ?? 0;
            }else{// 修改
                $modelObj = null;
                $saveBoolen = static::saveById($saveData, $id,$modelObj);
                // $resultDatas = static::getInfo($id);
                // 修改数据，是否当前版本号 + 1
                // static::compareHistory($id, 1);
            }
            // 更新兑换单状态
            ActivityCodeDBBusiness::saveById(['status' => 2], $code_id);
            // 更新活动已使用数量
            ActivityDBBusiness::saveById(['used_num' => $used_num + 1], $activity_id);
        } catch ( \Exception $e) {
            DB::rollBack();
            throws($e->getMessage());
            // throws($e->getMessage());
        }
        DB::commit();
        return $id;
    }


    /**
     * 根据收货信息id，修改地址功能
     *
     * @param array $saveData 要保存或修改的数组
     * @param int  $company_id 企业id
     * @param int $id id
     * @param int $operate_staff_id 操作人id
     * @param int $modifAddOprate 修改时是否加操作人，1:加;0:不加[默认]
     * @return  int 记录id值，
     * @author zouyan(305463219@qq.com)
     */
    public static function modifyById($saveData, $company_id, &$id, $operate_staff_id = 0, $modifAddOprate = 0){

        if(isset($saveData['real_name']) && empty($saveData['real_name'])  ){
            throws('收货人不能为空！');
        }

        if(isset($saveData['tel']) && empty($saveData['tel'])  ){
            throws('收货电话不能为空！');
        }

        if(isset($saveData['addr']) && empty($saveData['addr'])  ){
            throws('收货地址不能为空！');
        }
        // 根据兑换单号，获得详情

        $addrInfo = static::getInfo($id, [], ['codeInfo', 'activityInfo'])->toArray();

        if(empty($addrInfo))  throws('提货地址记录不存在！');
        if($addrInfo['status'] != 1)  throws('提货地址非待发货状态，不可修改！');

        $codeInfo = $addrInfo['code_info'] ?? [];
        if(empty($codeInfo))  throws('兑换码记录不存在！');
        // if($codeInfo['status'] == 2)  throws('此兑换码已兑换！');
         if($codeInfo['status'] == 4)  throws('此兑换码已过期！');
        // if($codeInfo['status'] != 1 )  throws('此兑换码非待兑换状态！');
         if($codeInfo['status'] != 2 )  throws('此兑换码非已兑换状态！');

        $activityInfo = $addrInfo['activity_info'] ?? [];
        if(empty($activityInfo))  throws('提货活动不存在！');
        if($activityInfo['status'] == 1)  throws('提货活动未开始！');
        // if($activityInfo['status'] == 4)  throws('提货活动已结束！');
        // if($activityInfo['status'] != 2 )  throws('提货活动非进行状态！不可在线提货!');

        $begin_time = $activityInfo['begin_time'];
        $end_time = $activityInfo['end_time'];
        $used_num = $activityInfo['used_num'];

//        $product_id_history = $codeInfo['product_id_history'];
//        $saveData['product_id_history'] = $product_id_history;

//        $saveData['product_id'] = $codeInfo['product_id'];
//
//        $activity_id = $codeInfo['activity_id'];
//        $saveData['activity_id'] = $activity_id;
//        $saveData['code_id'] = $codeInfo['id'];
//        $saveData['code'] = $codeInfo['code'];
//        if($id <= 0){
//            $saveData['status'] = 1;// 状态1未发货2已发货4已收货
//            $saveData['order_time'] = date('Y-m-d H:i:s');
//        }
        if(isset($saveData['status']) && $saveData['status'] == 2){
            $saveData['send_time'] = date('Y-m-d H:i:s');
        }

        DB::beginTransaction();
        try {
            // 省id历史
            if( isset($saveData['province_id']) && $saveData['province_id'] > 0 ){
                $saveData['province_id_history'] = CityDBBusiness::getIdHistory($saveData['province_id']);
            }
            // 市id历史
            if( isset($saveData['city_id']) && $saveData['city_id'] > 0 ){
                $saveData['city_id_history'] = CityDBBusiness::getIdHistory($saveData['city_id']);
            }
            // 区县id历史
            if( isset($saveData['area_id']) && $saveData['area_id'] > 0 ){
                $saveData['area_id_history'] = CityDBBusiness::getIdHistory($saveData['area_id']);
            }

            $isModify = false;
            $operate_staff_id_history = 0;

            if($id > 0){
                $isModify = true;
                // 判断权限
                //            $judgeData = [
                //                'company_id' => $company_id,
                //            ];
                //            $relations = '';
                //            static::judgePower($id, $judgeData , $company_id , [], $relations);
                if($modifAddOprate) static::addOprate($saveData, $operate_staff_id,$operate_staff_id_history);



            }else {// 新加;要加入的特别字段
                //            $addNewData = [
                //                'company_id' => $company_id,
                //            ];
                //            $saveData = array_merge($saveData, $addNewData);
                // 加入操作人员信息
                static::addOprate($saveData, $operate_staff_id,$operate_staff_id_history);
            }

            // 新加或修改
            if($id <= 0){// 新加
                $resultDatas = static::create($saveData);
                $id = $resultDatas['id'] ?? 0;
            }else{// 修改
                $modelObj = null;
                $saveBoolen = static::saveById($saveData, $id,$modelObj);
                // $resultDatas = static::getInfo($id);
                // 修改数据，是否当前版本号 + 1
                // static::compareHistory($id, 1);
            }
        } catch ( \Exception $e) {
            DB::rollBack();
            throws($e->getMessage());
            // throws($e->getMessage());
        }
        DB::commit();
        return $id;
    }

    /**
     * 根据id发货
     *
     * @param int  $company_id 企业id
     * @param string $id id 需要操作的id , 多个逗号分隔
     * @param int $operate_staff_id 操作人id
     * @param int $modifAddOprate 修改时是否加操作人，1:加;0:不加[默认]
     * @return  int 记录id值
     * @author zouyan(305463219@qq.com)
     */
    public static function sendById($company_id, $id, $operate_staff_id = 0, $modifAddOprate = 0){

        if(strlen($id) <= 0){
            throws('操作记录标识不能为空！');
        }
        DB::beginTransaction();
        try {
            // 批量发货
            $updateData = [
                'status' => 2
            ];
            $saveQueryParams = [
                'where' => [
                    ['status', 1],
                ],
//                            'select' => [
//                                'id','title','sort_num','volume'
//                                ,'operate_staff_id','operate_staff_id_history'
//                                ,'created_at' ,'updated_at'
//                            ],

                //   'orderBy' => [ 'id'=>'desc'],//'sort_num'=>'desc',
            ];

            if (!empty($id)) {
                if (strpos($id, ',') === false) { // 单条
                    array_push($saveQueryParams['where'], ['id', $id]);
                } else {
                    $saveQueryParams['whereIn']['id'] = explode(',', $id);
                }
            }
            static::save($updateData, $saveQueryParams);
        } catch ( \Exception $e) {
            DB::rollBack();
            throws($e->getMessage());
            // throws($e->getMessage());
        }
        DB::commit();
        return $id;
    }


    /**
     * 支付回调--微信
     *
     * @param array $message 回调的参数
    {
    "appid": "wxcb82783fe211782f",
    "bank_type": "CFT",// 银行类型
    "cash_fee": "1",// 现金
    "fee_type": "CNY",// 币种
    "is_subscribe": "N",// 是否订阅
    "mch_id": "1527642191",
    "nonce_str": "5c8e67b1d9bc3",
    "openid": "owfFF4ydu2HmuvmSDS4goIoAIYEs",
    "out_trade_no": "119108029350007",
    "result_code": "SUCCESS",// 支付结果 FAIL:失败;SUCCESS:成功
    "return_code": "SUCCESS",// 表示通信状态: SUCCESS 成功
    "sign": "C6ACF2C7C8AF999048094ED2264F0ABC",
    "time_end": "20190317232919",// 交易时间
    "total_fee": "1",// 交易金额
    "trade_type": "JSAPI",// 交易类型
    "transaction_id": "4200000288201903177135850941"// 交易号
    }
     * @param array $queryMessage 商户订单号查询 结果
     *
    商户订单号查询 结果
    {
    "return_code": "SUCCESS",
    "return_msg": "OK",
    "appid": "wxcb82783fe211782f",
    "mch_id": "1527642191",
    "nonce_str": "aA5oRYgVOf7osQv3",
    "sign": "DCD3A1790A8C4E1A4BBE2339E812AB3C",
    "result_code": "SUCCESS",
    "openid": "owfFF4ydu2HmuvmSDS4goIoAIYEs",
    "is_subscribe": "N",
    "trade_type": "JSAPI",
    "bank_type": "CFT",
    "total_fee": "1",
    "fee_type": "CNY",
    "transaction_id": "4200000288201903177135850941",
    "out_trade_no": "119108029350007",
    "attach": null,
    "time_end": "20190317232919",
    "trade_state": "SUCCESS",// 交易状态
    "cash_fee": "1",
    "trade_state_desc": "支付成功"
    }
     *
     * 交易状态
    SUCCESS—支付成功
    REFUND—转入退款
    NOTPAY—未支付
    CLOSED—已关闭
    REVOKED—已撤销（付款码支付）
    USERPAYING--用户支付中（付款码支付）
    PAYERROR--支付失败(其他原因，如银行返回失败)
    支付状态机请见下单API页面
     * @return  mixed string throws错误，请再通知我  正常返回 :不用通知我了
     * @author zouyan(305463219@qq.com)
     */
    public static function payWXNotify($message, $queryMessage){

        try{
            // 查询订单
            $out_trade_no = $message['out_trade_no'] ?? '';// 我方单号--与第三方对接用
            $out_trade_no = trim($out_trade_no);
            if(empty($out_trade_no)) throws('参数out_trade_no不能为空!');
            $transaction_id = $message['transaction_id'] ?? '';// 第三方单号[有则填]
            $transaction_id = trim($transaction_id);
            if(empty($transaction_id)) throws('参数transaction_id不能为空!');
            // 查询支付单
            $queryParams = [
                'where' => [
                    ['order_no', $out_trade_no],
                ],
                /*
                'select' => [
                    'id','title','sort_num','volume'
                    ,'operate_staff_id','operate_staff_id_history'
                    ,'created_at' ,'updated_at'
                ],
                */
                //   'orderBy' => [ 'id'=>'desc'],//'sort_num'=>'desc',
            ];
            // 查询记录
            $wrInfo = static::getInfoByQuery(1, $queryParams, []);
            if(empty($wrInfo)) return '记录不存在';// 1; //记录不存在

            $status = $wrInfo->pay_status;// 付款状态1无需付款2待支付4支付失败8已付款
            if(in_array($status, [1,8])) return '已关闭或成功';//  return 1;// 已关闭或成功

        } catch ( \Exception $e) {
            // throws('失败；信息[' . $e->getMessage() . ']');
//            return $e->getMessage();// $fail($e->getMessage());
            throws($e->getMessage());
        }
        // pr($wrInfo->toArray());
        // pr($message);
        // pr($queryMessage);

//            if (!$order || $order->paid_at) { // 如果订单不存在 或者 订单已经支付过了
//                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
//            }

//            ///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////
//
        $returnStr = '';
        if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态

            $lockObj = Tool::getLockRedisesLaravelObj();
            $lockState = $lockObj->lock('lock:' . Tool::getUniqueKey([Tool::getProjectKey(1, ':', ':'), Tool::getActionMethod(), __CLASS__, __FUNCTION__, $out_trade_no]), 2000, 2000);//加锁
            if($lockState)
            {
                try {
                    DB::beginTransaction();
                    try {
                        $wrInfo->pay_no = $transaction_id;
                        // 用户是否支付成功 1无需付款2待支付4支付失败8已付款
                        $payStatus = 1;// 1失败  2 成功
                        if ($message['result_code'] === 'SUCCESS' && $queryMessage['trade_state'] === 'SUCCESS') {
                            $payStatus = 2;// 1失败  2 成功
                            $wrInfo->pay_status = 8;
                            $wrInfo->pay_time = date("Y-m-d H:i:s",time());
                            //                    $order->paid_at = time(); // 更新支付时间为当前时间
                            //                    $order->status = 'paid';
                            //                $saveData['status'] = 2;
                            //                $saveData['pay_run_price'] = 1;
                            //                $saveData['pay_order_no'] = $transaction_id;
                            //                $saveData['pay_time'] = date("Y-m-d H:i:s",time());

                            // 用户支付失败
                        } elseif ($message['result_code'] === 'FAIL') {
                            $wrInfo->pay_status = 4;
                            //                    $order->status = 'paid_fail';
                            // $saveData['pay_run_price'] = 4;
                        }
                        $wrInfo->save();

                        //            try{
                        //                $resultDatas = CTAPIOrdersDoingBusiness::replaceById($request, $this, $saveData, $id, false, 1);
                        //                $resultOrder = CTAPIOrdersBusiness::replaceById($request, $this, $saveData, $order_id, false, 1);
                        //
                        //            } catch ( \Exception $e) {
                        //                // throws('失败；信息[' . $e->getMessage() . ']');
                        //                return $fail($e->getMessage());
                        //            }

                        $operate_staff_id_history = 0;
                        // Log::info('微信支付日志 $orderDatas' . __FUNCTION__, [$saveData, $resultDatas, $resultOrder ]);
                        // return 1;
                        DB::commit();
                    } catch ( \Exception $e) {
                        DB::rollBack();
                        $errMsg = $e->getMessage();
                        $errCode = $e->getCode();
//                        if($errCode == 10 ){
//                            $returnStr = $errMsg;
//                            return $returnStr;
//                        }else{
        //                    throws('操作失败；信息[' . $e->getMessage() . ']');
                            throws($e->getMessage(), $errCode);
//                        }
        //                if(is_numeric($errMsg) || $errMsg == 1){
        //
        //                }else{
        //                    DB::rollBack();
        ////                    throws('操作失败；信息[' . $e->getMessage() . ']');
        //                     throws($e->getMessage());
        //                }
                    }

                } catch ( \Exception $e) {
                    $errStr = $e->getMessage();
                    $errCode = $e->getCode();
                    if($errCode == 10 ){
                        $returnStr = $errStr;
                        return $returnStr;
                    }else{
                        //                    throws('操作失败；信息[' . $e->getMessage() . ']');
                        throws($errStr, $errCode);
                    }
                    // throws($e->getMessage());
                }finally{
                    $lockObj->unlock($lockState);//解锁
                }
            }else{
                throws('操作失败，请稍后重试!');
            }
            return $returnStr;
        } else {
//            return '通信失败，请稍后再通知我';// $fail('通信失败，请稍后再通知我');
            throws('通信失败，请稍后再通知我');
        }
        return '';
//
//            $order->save(); // 保存订单

//            return true; // 返回处理完成
    }


}
