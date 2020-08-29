<?php

namespace App\Http\Controllers\Site;

use App\Business\API\RunBuy\CityAPIBusiness;
use App\Business\Controller\API\RunBuy\CTAPIDeliveryAddrBusiness;
use App\Business\Controller\API\RunBuy\CTAPIOrdersBusiness;
use App\Business\Controller\API\RunBuy\CTAPIOrdersDoingBusiness;
use App\Business\Controller\API\RunBuy\CTAPIWalletRecordBusiness;
use App\Services\pay\weixin\easyWechatPay;
use App\Services\Request\CommonRequest;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class PayController extends BaseWebController
{
    public $controller_id =0;// 功能小模块[控制器]id - controller_id  历史表 、正在进行表 与原表相同

    /*

        $payConfig = [// $orderType 订单类型编号 1 订单号 2 退款订单 3 支付跑腿费  4 追加跑腿费 5 冲值  6 提现 7 压金或保证金
            'order_type' => [
                'operate_type' => 0,// 操作类型1充值2提现3交压金/保证金4订单付款5追加付款8退款16冻结32解冻
                'operate_text' => '',// 操作名称
            ]
        ];
     *
     */

    public static $payConfig = [
        '3' => [
            'operate_type' => 4,
            'operate_text' => '订单付款',
        ],
        '4' => [
            'operate_type' => 5,
            'operate_text' => '追加付款',
        ],
        '5' => [
            'operate_type' => 1,
            'operate_text' => '充值',
        ],
        '7' => [
            'operate_type' => 3,
            'operate_text' => '保证金',
        ],
    ];

    //  统一下单 -- 根据订单号[订单支付跑腿费、订单追加跑腿费]
    //  参数 pay_type
    //  1 订单支付跑腿费--[订单有关]  order_no
    //  2 订单追加跑腿费--[订单有关]  order_no  amount 追加跑腿费 单位元
    //  3 其它支付--[订单无关] amount  order_type  订单类型编号 1 订单号 2 退款订单 3 支付跑腿费  4 追加跑腿费 5 冲值  6 提现 7 压金或保证金  operate_text 操作名称[可为空]
    public function unifiedorderByNo(Request $request)
    {
        $this->InitParams($request);
        $pay_type = CommonRequest::getInt($request, 'pay_type');// 支付类型 1 订单支付跑腿费 2 订单追加跑腿费
        if(!in_array($pay_type, [1,2])) throws('支付类型有误!');

        switch($pay_type){
            case 1:// 1 订单支付跑腿费--[订单有关]
                $order_no = CommonRequest::get($request, 'order_no');
                if(empty($order_no)) throws('订单号不能为空!');

                $params = [
                    'pay_way' => 2,// 支付方式1余额支付2微信支付
                    'pay_type' => 1, // 支付类型 1 订单支付跑腿费--[订单有关] 2 订单追加跑腿费--[订单有关]   3 其它支付--[订单无关]
                    'order_type' => 3,// $orderType 订单类型编号 1 订单号 2 退款订单 3 支付跑腿费  4 追加跑腿费 5 冲值  6 提现 7 压金或保证金
                    'operate_type' => 4,// 操作类型1充值2提现3交压金/保证金4订单付款5追加付款8退款16冻结32解冻
                    'operate_text' => '跑腿订单[' . $order_no . ']服务费',// 操作名称
                    //  'amount' => 10,// 追加跑腿费 单位元
                ];
                // 支付订单跑腿费
                $res = CTAPIWalletRecordBusiness::payOrder($request, $this, $params, $order_no, 0);

                $body = $res['body'];// config('public.webName') . '-跑腿订单[' . $order_no . ']服务费';
                $total_fee = ceil($res['total_fee'] * 100);
                // 生成订单号
                // 重新发起一笔支付要使用原订单号，避免重复支付；已支付过或已调用关单、撤销（请见后文的API列表）的订单号不能重新发起支付。--支付未成功的订单号，可以重新发起支付
                $out_trade_no =  $res['out_trade_no'];// $order_no;// CTAPIOrdersBusiness::createSn($request, $this, 3);
                break;
            case 2://2 订单追加跑腿费--[订单有关]
                $order_no = CommonRequest::get($request, 'order_no');
                if(empty($order_no)) throws('订单号不能为空!');
                $amount = CommonRequest::get($request, 'amount');// 追加跑腿费 单位元
                if(!is_numeric($amount) && $amount < 1) throws('费用不能小于1!');

                $params = [
                    'pay_way' => 2,// 支付方式1余额支付2微信支付
                    'pay_type' => 2, // 支付类型 1 订单支付跑腿费--[订单有关] 2 订单追加跑腿费--[订单有关]   3 其它支付--[订单无关]
                    'order_type' => 4,// $orderType 订单类型编号 1 订单号 2 退款订单 3 支付跑腿费  4 追加跑腿费 5 冲值  6 提现 7 压金或保证金
                    'operate_type' => 5,// 操作类型1充值2提现3交压金/保证金4订单付款5追加付款8退款16冻结32解冻
                    'operate_text' => '跑腿订单[' . $order_no . ']追加服务费',// 操作名称
                    'amount' => $amount,// 追加跑腿费 单位元
                ];
                $res = CTAPIWalletRecordBusiness::payOrder($request, $this, $params, $order_no, 0);

                $body = $res['body'];// config('public.webName') . '-跑腿订单[' . $order_no . ']服务费';
                $total_fee = ceil($res['total_fee'] * 100);
                // 生成订单号
                // 重新发起一笔支付要使用原订单号，避免重复支付；已支付过或已调用关单、撤销（请见后文的API列表）的订单号不能重新发起支付。--支付未成功的订单号，可以重新发起支付
                $out_trade_no =  $res['out_trade_no'];// $order_no;// CTAPIOrdersBusiness::createSn($request, $this, 3);
                break;
            default:// 3 其它支付--[订单无关]
                $amount = CommonRequest::get($request, 'amount');// 追加跑腿费 单位元
                if(!is_numeric($amount) && $amount < 1) throws('费用不能小于1!');
                $order_type = CommonRequest::get($request, 'order_type');// 订单类型编号 1 订单号 2 退款订单 3 支付跑腿费  4 追加跑腿费 5 冲值  6 提现 7 压金或保证金

                if(empty($order_type) || !is_numeric($order_type) ) throws('参数订单类型有误!');
                $payConfig = static::$payConfig;
                $temPayConfig = $payConfig[$order_type] ?? [];
                if(empty($temPayConfig) ) throws('支付配置有误!');
                $operate_text = CommonRequest::get($request, 'operate_text');//
                if(empty($operate_text)) $operate_text = $temPayConfig['operate_text'];

                $params = [
                    'pay_way' => 2,// 支付方式1余额支付2微信支付
                    'pay_type' => 3, // 支付类型 1 订单支付跑腿费--[订单有关] 2 订单追加跑腿费--[订单有关]   3 其它支付--[订单无关]
                    'order_type' => $order_type,// 4,// $orderType 订单类型编号 1 订单号 2 退款订单 3 支付跑腿费  4 追加跑腿费 5 冲值  6 提现 7 压金或保证金
                    'operate_type' => $temPayConfig['operate_type'],// 5,// 操作类型1充值2提现3交压金/保证金4订单付款5追加付款8退款16冻结32解冻
                    'operate_text' => $operate_text,//  '跑腿订单[' . $order_no . ']追加服务费',// 操作名称
                    'amount' => $amount,// 追加跑腿费 单位元
                ];
                $order_no = '';
                $res = CTAPIWalletRecordBusiness::payOrder($request, $this, $params, $order_no, 0);

                $body = $res['body'];// config('public.webName') . '-跑腿订单[' . $order_no . ']服务费';
                $total_fee = ceil($res['total_fee'] * 100);
                // 生成订单号
                // 重新发起一笔支付要使用原订单号，避免重复支付；已支付过或已调用关单、撤销（请见后文的API列表）的订单号不能重新发起支付。--支付未成功的订单号，可以重新发起支付
                $out_trade_no =  $res['out_trade_no'];// $order_no;// CTAPIOrdersBusiness::createSn($request, $this, 3);
                break;
        }

        // 日志
//        $requestLog = [
//            'files'       => $request->file(),
//            'posts'  => $request->post(),
//            'input'      => $request->input(),
//            'post_data' => apiGetPost(),
//        ];
//        Log::info('微信支付日志-->' . __FUNCTION__,$requestLog);
        // $url = config('public.wxPayURL') . 'pay/unifiedorder';

        //  下单：主要参数 $body  $out_trade_no  $total_fee
        $userInfo = $this->user_info;

        $app = app('wechat.payment');
        $params = [
            'body' => $body,
            'out_trade_no' => $out_trade_no,
            'total_fee' => $total_fee,
            // 'spbill_create_ip' => '123.12.12.123', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
            // 'notify_url' => 'https://pay.weixin.qq.com/wxpay/pay.action', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            'openid' => $userInfo['mini_openid'], // 'oUpF8uMuAJO_M2pxb1Q9zNjWeS6o',
        ];
        try{
            $result = easyWechatPay::miniProgramunify($app, $params, 1);
        } catch ( \Exception $e) {
            throws('失败；信息[' . $e->getMessage() . ']');
        }
        // 去掉敏感信息
        Tool::formatArrKeys($result, Tool::arrEqualKeyVal(['timeStamp', 'nonceStr', 'package', 'signType', 'paySign']), true );
        return ajaxDataArr(1, $result, '');
    }

    //  统一下单--- 测试支付用
    public function unifiedorder(Request $request)
    {
         $this->InitParams($request);
        // 日志
        $requestLog = [
            'files'       => $request->file(),
            'posts'  => $request->post(),
            'input'      => $request->input(),
            'post_data' => apiGetPost(),
        ];
        Log::info('微信支付日志-->' . __FUNCTION__,$requestLog);
        // $url = config('public.wxPayURL') . 'pay/unifiedorder';

        $userInfo = $this->user_info;

        // 查询退款单
//        try{
//        $app = app('wechat.payment');
//         $result = easyWechatPay::queryByOutRefundNumber($app, '21903181737563502');
        // $result = easyWechatPay::queryByRefundId($app, '50000009922019031808811970746');
//         $result = easyWechatPay::queryRefundByOutTradeNumber($app, '119109471350010');
//         $result = easyWechatPay::queryRefundByTransactionId($app, '4200000279201903189120405440');
        //return ajaxDataArr(1, $result, '');
//        } catch ( \Exception $e) {
//            throws('失败；信息[' . $e->getMessage() . ']');
//        }

        // 生成订单号
        // 重新发起一笔支付要使用原订单号，避免重复支付；已支付过或已调用关单、撤销（请见后文的API列表）的订单号不能重新发起支付。--支付未成功的订单号，可以重新发起支付
        $orderNum = CTAPIOrdersBusiness::createSn($request, $this, 1);

        $app = app('wechat.payment');
        $params = [
             'body' => '测试支付',
             'out_trade_no' => $orderNum,
             'total_fee' => 1,
             // 'spbill_create_ip' => '123.12.12.123', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
             // 'notify_url' => 'https://pay.weixin.qq.com/wxpay/pay.action', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
             'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
             'openid' => $userInfo['mini_openid'], // 'oUpF8uMuAJO_M2pxb1Q9zNjWeS6o',
        ];
        try{
            $result = easyWechatPay::miniProgramunify($app, $params, 1);
        } catch ( \Exception $e) {
            throws('失败；信息[' . $e->getMessage() . ']');
        }

        // 去掉敏感信息
        Tool::formatArrKeys($result, Tool::arrEqualKeyVal(['timeStamp', 'nonceStr', 'package', 'signType', 'paySign']), true );
        return ajaxDataArr(1, $result, '');
    }

    //  退单测试
    // order_no 或 my_order_no 之一不能为空
    // amount 需要退款的金额--不传为0为全退---单位元
    // refund_reason 退款的原因--:为空，则后台自己组织内容
    public function refundOrder(Request $request)
    {
        $this->InitParams($request);
        return CTAPIWalletRecordBusiness::cancelOrder($request, $this, 0);
        /*
        switch($pay_type){
            case 1:// 1 订单支付跑腿费
                $order_no = CommonRequest::get($request, 'order_no');
//                if(empty($order_no)) throws('订单号不能为空!');
//                $orderInfo = CTAPIOrdersDoingBusiness::getOrderInfoByOrderNo($request, $this, $order_no, 1);
//
//                if(empty($orderInfo)) throws('订单信息不存在!');
//                $pay_run_price = $orderInfo['pay_run_price'] ?? '';// 是否支付跑腿费0未支付1已支付
//                $status = $orderInfo['status'] ?? 8;// 状态1待支付2等待接单4取货或配送中8订单完成16取消[系统取消]32取消[用户取消]64作废[非正常完成]
//                if($pay_run_price != 1) throws('订单非已支付!');
//                if($status != 2) throws('订单非等待接单状态!');
//                $total_run_price = $orderInfo['total_run_price'];
//
//                $refund_desc = config('public.webName') . '-跑腿订单[' . $order_no . ']取消';
//                $totalFee = ceil($total_run_price * 100);
//                $refundFee = $totalFee;
//
//                // 生成订单号
//                // 重新发起一笔支付要使用原订单号，避免重复支付；已支付过或已调用关单、撤销（请见后文的API列表）的订单号不能重新发起支付。--支付未成功的订单号，可以重新发起支付
//                $out_trade_no = $order_no;// CTAPIOrdersBusiness::createSn($request, $this, 3);
//                // TODO 生成支付数据
                break;
            case 2:// 2 订单追加跑腿费

                throws('此功能正在调试中...!');
                // TODO 生成支付数据

                break;
            default:
                break;
        }

        // 生成退订单号
        $refundOrderNum = CTAPIOrdersBusiness::createSn($request, $this, 2);

        $app = app('wechat.payment');
        // $out_trade_no = '119109471350010';
        // $transactionId = '4200000279201903189120405440';
        $refundNumber = $refundOrderNum;
        // $totalFee = 5;
        // $refundFee = 1;
        $config = [
            'refund_desc' => $refund_desc,//'测试退款',// 退款原因 若商户传入，会在下发给用户的退款消息中体现退款原因  ；注意：若订单退款金额≤1元，且属于部分退款，则不会在退款消息中体现退款原因
            'notify_url' => config('public.wxNotifyURL') . 'api/pay/refundNotify' ,// 退款结果通知的回调地址
        ];

        try{
            // 根据商户订单号退款
            $result = easyWechatPay::refundByOutTradeNumber($app, $out_trade_no, $refundNumber, $totalFee, $refundFee, $config);
            // 根据微信订单号退款
            // $result = easyWechatPay::refundByTransactionId($app, $transactionId, $refundNumber, $totalFee, $refundFee, $config);
        } catch ( \Exception $e) {
            throws('失败；信息[' . $e->getMessage() . ']');
        }


        return ajaxDataArr(1, $result, '');
        */
    }

    //  退单测试
    public function refundOrderBack(Request $request)
    {
        $this->InitParams($request);

        // 生成退订单号
        $refundOrderNum = CTAPIOrdersBusiness::createSn($request, $this, 2);

        $app = app('wechat.payment');
        $out_trade_no = '119109471350010';
        $transactionId = '4200000279201903189120405440';
        $refundNumber = $refundOrderNum;
        $totalFee = 5;
        $refundFee = 1;
        $config = [
            'refund_desc' => '测试退款',// 退款原因 若商户传入，会在下发给用户的退款消息中体现退款原因  ；注意：若订单退款金额≤1元，且属于部分退款，则不会在退款消息中体现退款原因
            'notify_url' => config('public.wxNotifyURL') . 'api/pay/refundNotify' ,// 退款结果通知的回调地址
        ];

        try{
            // 根据商户订单号退款
            $result = easyWechatPay::refundByOutTradeNumber($app, $out_trade_no, $refundNumber, $totalFee, $refundFee, $config);
            // 根据微信订单号退款
            // $result = easyWechatPay::refundByTransactionId($app, $transactionId, $refundNumber, $totalFee, $refundFee, $config);
        } catch ( \Exception $e) {
            throws('失败；信息[' . $e->getMessage() . ']');
        }


        return ajaxDataArr(1, $result, '');
    }


//
//    //  支付结果通知--回调
//    public function wechatNotify(Request $request)
//    {
//        // $this->InitParams($request);
//        // 日志
//        $requestLog = [
//            'files'       => $request->file(),
//            'posts'  => $request->post(),
//            'input'      => $request->input(),
//            'post_data' => apiGetPost(),
//        ];
//        Log::info('微信支付日志 回调-->' . __FUNCTION__,$requestLog);
//        $app = app('wechat.payment');
//        /* $message 的内容
//        {
//            "appid": "wxcb82783fe211782f",
//            "bank_type": "CFT",// 银行类型
//            "cash_fee": "1",// 现金
//            "fee_type": "CNY",// 币种
//            "is_subscribe": "N",// 是否订阅
//            "mch_id": "1527642191",
//            "nonce_str": "5c8e67b1d9bc3",
//            "openid": "owfFF4ydu2HmuvmSDS4goIoAIYEs",
//            "out_trade_no": "119108029350007",
//            "result_code": "SUCCESS",// 支付结果 FAIL:失败;SUCCESS:成功
//            "return_code": "SUCCESS",// 表示通信状态: SUCCESS 成功
//            "sign": "C6ACF2C7C8AF999048094ED2264F0ABC",
//            "time_end": "20190317232919",// 交易时间
//            "total_fee": "1",// 交易金额
//            "trade_type": "JSAPI",// 交易类型
//            "transaction_id": "4200000288201903177135850941"// 交易号
//        }
//        */
//        $response = $app->handlePaidNotify(function($message, $fail) use(&$app) {
//
//            Log::info('微信支付日志 $message-->' . __FUNCTION__, [$message]);
//            Log::info('微信支付日志 $fail-->' . __FUNCTION__, [$fail]);
//            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
//
//            try{
//                // 查询订单
//                $out_trade_no = $message['out_trade_no'] ?? '';
//                // Log::info('微信支付日志 $order-->' . __FUNCTION__, [$out_trade_no]);
//                if(!empty($out_trade_no)){
//                    $queryResult = easyWechatPay::queryByOutTradeNumber($app, $out_trade_no);
//                }
//
//                // 根据微信订单号查询
//    //            $transaction_id = $message['transaction_id'] ?? '';
//    //            if(!empty($out_trade_no)) {
//    //                $queryResult = easyWechatPay::queryByTransactionId($app, $transaction_id);
//    //            }
//            } catch ( \Exception $e) {
//                // throws('失败；信息[' . $e->getMessage() . ']');
//                return $fail($e->getMessage());
//            }
//
//
//            return true;
//
////            if (!$order || $order->paid_at) { // 如果订单不存在 或者 订单已经支付过了
////                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
////            }
//
////            ///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////
////
////            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
////                // 用户是否支付成功
////                if (array_get($message, 'result_code') === 'SUCCESS') {
////                    $order->paid_at = time(); // 更新支付时间为当前时间
////                    $order->status = 'paid';
////
////                    // 用户支付失败
////                } elseif (array_get($message, 'result_code') === 'FAIL') {
////                    $order->status = 'paid_fail';
////                }
////            } else {
////                return $fail('通信失败，请稍后再通知我');
////            }
////
////            $order->save(); // 保存订单
//
////            return true; // 返回处理完成
//        });
//        return $response;//return $response->send();
//        // return ajaxDataArr(1, 'wechatNotify', '');
//    }

        public function test(Request $request){
//             $cancelOrderList = CityAPIBusiness::autoCancelOrdes();
//             pr($cancelOrderList);
            $message = '{"appid":"wxcb82783fe211782f","bank_type":"CFT","cash_fee":"1","fee_type":"CNY","is_subscribe":"N","mch_id":"1527642191","nonce_str":"5c9a4a10401dd","openid":"owfFF4ydu2HmuvmSDS4goIoAIYEs","out_trade_no":"31903262349363516","result_code":"SUCCESS","return_code":"SUCCESS","sign":"27EEA7F8C022D97B9FC6B147373B5AA7","time_end":"20190326234942","total_fee":"1","trade_type":"JSAPI","transaction_id":"4200000284201903266412932872"}';
            $queryResult = '{"return_code":"SUCCESS","return_msg":"OK","appid":"wxcb82783fe211782f","mch_id":"1527642191","nonce_str":"fKvV3jFuqUG3H0mJ","sign":"96F61E600C674F0D2E049D400BF26A62","result_code":"SUCCESS","openid":"owfFF4ydu2HmuvmSDS4goIoAIYEs","is_subscribe":"N","trade_type":"JSAPI","bank_type":"CFT","total_fee":"1","fee_type":"CNY","transaction_id":"4200000284201903266412932872","out_trade_no":"31903262349363516","attach":null,"time_end":"20190326234942","trade_state":"SUCCESS","cash_fee":"1","trade_state_desc":"支付成功"}';
            $message = json_decode($message, true);
            $queryResult = json_decode($queryResult, true);
            $res = CTAPIWalletRecordBusiness::payWXNotify($request, $this, $message, $queryResult, 1);
            pr($res);
        }

    //  支付结果通知--回调
    public function wechatNotify(Request $request)
    {
        // $this->InitParams($request);
        // 日志
        $requestLog = [
            'files'       => $request->file(),
            'posts'  => $request->post(),
            'input'      => $request->input(),
           // 'post_data' => apiGetPost(),
        ];
        Log::info('微信支付日志 回调-->' . __FUNCTION__,$requestLog);
        $app = app('wechat.payment.mxpay');
        /* $message 的内容
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
        */
        $response = $app->handlePaidNotify(function($message, $fail) use(&$request, &$app) {

            Log::info('微信支付日志 $message-->' . __FUNCTION__, [$message]);
            Log::info('微信支付日志 $fail-->' . __FUNCTION__, [$fail]);
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
           // 查询订单
            $out_trade_no = $message['out_trade_no'] ?? '';
            // $transaction_id = $message['transaction_id'] ?? '';
            try{
                $queryResult = easyWechatPay::queryByOutTradeNumber($app, $out_trade_no);

                Log::info('微信支付日志 $queryResult-->' . __FUNCTION__, [$queryResult]);
                // $res = CTAPIWalletRecordBusiness::payWXNotify($request, $this, $message, $queryResult, 1);
                $res = CTAPIDeliveryAddrBusiness::payWXNotify($request, $this, $message, $queryResult, 1);
                Log::info('微信支付日志 $res true -->' . __FUNCTION__, [$res]);
                // if(is_numeric($res) && $res == 1) return true;
                // return $fail($res);
                return true;
            } catch ( \Exception $e) {
                Log::info('微信支付日志 error-->' . __FUNCTION__, [$e->getMessage()]);
                // throws('失败；信息[' . $e->getMessage() . ']');
                return $fail($e->getMessage());
            }
//            try{
//                // 查询订单
//                $out_trade_no = $message['out_trade_no'] ?? '';
//                $transaction_id = $message['transaction_id'] ?? '';
//                $queryParams = [
//                    'where' => [
//                        ['order_type', '=', 1],
//                        // ['staff_id', '=', $user_id],
//                        ['order_no', '=', $out_trade_no],
//                        // ['id', '&' , '16=16'],
//                        // ['company_id', $company_id],
//                        // ['admin_type',self::$admin_type],
//                    ],
//                    // 'whereIn' => [
//                    //   'id' => $subjectHistoryIds,
//                    //],
////            'select' => [
////                'id'
////            ],
//                    // 'orderBy' => ['is_default'=>'desc', 'id'=>'desc'],
//                ];
//                $resultDatas = CTAPIOrdersDoingBusiness::getInfoByQuery($request, $this, '', $this->company_id, $queryParams);
//
//                Log::info('微信支付日志 $resultDatas-->' . __FUNCTION__, [$resultDatas]);
//                $orderDatas = CTAPIOrdersBusiness::getInfoByQuery($request, $this, '', $this->company_id, $queryParams);
//
//                Log::info('微信支付日志 $orderDatas-->' . __FUNCTION__, [$orderDatas]);
//                if(empty($resultDatas)) return true;// return $fail('订单信息不存在!');
//                $pay_run_price = $resultDatas['pay_run_price'] ?? '';// 是否支付跑腿费0未支付1已支付
//                if($pay_run_price == 1) return true;// 订单已支付
//                // Log::info('微信支付日志 $order-->' . __FUNCTION__, [$out_trade_no]);
//                if(!empty($out_trade_no)){
//                    $queryResult = easyWechatPay::queryByOutTradeNumber($app, $out_trade_no);
//                }
//
//                // 根据微信订单号查询
//                //            $transaction_id = $message['transaction_id'] ?? '';
//                //            if(!empty($out_trade_no)) {
//                //                $queryResult = easyWechatPay::queryByTransactionId($app, $transaction_id);
//                //            }
//            } catch ( \Exception $e) {
//                // throws('失败；信息[' . $e->getMessage() . ']');
//                return $fail($e->getMessage());
//            }
//
////            if (!$order || $order->paid_at) { // 如果订单不存在 或者 订单已经支付过了
////                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
////            }
//
////            ///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////
////
//            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
//                $saveData = [] ;
//                $id = $resultDatas['id'];
//                $order_id = $orderDatas['id'];
//                // 用户是否支付成功
//                if (array_get($message, 'result_code') === 'SUCCESS') {
////                    $order->paid_at = time(); // 更新支付时间为当前时间
////                    $order->status = 'paid';
//                    $saveData['status'] = 2;
//                    $saveData['pay_run_price'] = 1;
//                    $saveData['pay_order_no'] = $transaction_id;
//                    $saveData['pay_time'] = date("Y-m-d H:i:s",time());
//
//                    // 用户支付失败
//                } elseif (array_get($message, 'result_code') === 'FAIL') {
////                    $order->status = 'paid_fail';
//                    $saveData['pay_run_price'] = 4;
//                }
//
//                try{
//                    $resultDatas = CTAPIOrdersDoingBusiness::replaceById($request, $this, $saveData, $id, false, 1);
//                    $resultOrder = CTAPIOrdersBusiness::replaceById($request, $this, $saveData, $order_id, false, 1);
//
//                } catch ( \Exception $e) {
//                    // throws('失败；信息[' . $e->getMessage() . ']');
//                    return $fail($e->getMessage());
//                }
//                Log::info('微信支付日志 $orderDatas-->' . __FUNCTION__, [$saveData, $resultDatas, $resultOrder ]);
//                return true;
//            } else {
//                return $fail('通信失败，请稍后再通知我');
//            }
//
//            $order->save(); // 保存订单

//            return true; // 返回处理完成
        });
        return $response;//return $response->send();
        // return ajaxDataArr(1, 'wechatNotify', '');
    }


    //  退款结果通知--回调
    public function refundNotify(Request $request)
    {
        // $this->InitParams($request);
        // 日志
        $requestLog = [
            'files'       => $request->file(),
            'posts'  => $request->post(),
            'input'      => $request->input(),
            'post_data' => apiGetPost(),
        ];
        Log::info('微信支付日志 退款结果通知--回调-->' . __FUNCTION__,$requestLog);
        $app = app('wechat.payment');
        /**  $message
        {
            "return_code": "SUCCESS",// 返回状态码  SUCCESS/FAIL  此字段是通信标识，非交易标识，交易是否成功需要查看trade_state来判断
                return_msg  当return_code为FAIL时返回信息为错误原因 ，例如  签名失败  参数格式校验错误
            "appid": "wxcb82783fe211782f",
            "mch_id": "1527642191",
            "nonce_str": "da42c1396000ea4d42c7714e4c6cf19d",
            "req_info": "uTmREqV8NkWXBdG32TJhJlA2LGVzCROHjGnKaIYfPNCjovTEeQtNQljda8RthGNbg6efS2qx3zf79vg4ORX5JMfYLa2YWBOpYvhjK1RovUpAgjcLiyqdx7Dgd2yyn6uBmu1Kp/1qJWEIlP7ctmAFYw+l2Xa9OXdKDkNdn0PX4RjJA8Npvg92pyGclbLKRoxWjnWhrmofJbDmRVrFQfIGTxzAj0JXrIPcbhGs+ybIAD0D3DAi5i51KL5dndw9YS0s5C2wgCEFkHNpYHRfsLY/XD6XMAvqKPUYNSQuAd3f35lDUfdOUuse3zce2kVjjJW/HueA22MVJb/Fs/zHElFp+/vR2hubi4zgb4pmffLHTCh6O/0o+zCG4v6lyPkfv07t+uG34tM63Z/pgoYF6FVca9YcrYEOP4IuZf9hskEProY6lvCoNn0pcOTUYjfeuZ2iKWJLmbOApkCiyg5yND1KlmBHRbHVocZOLq03s55PD459uFc3Nkn9eXRzCOWLu+jCsaqvaSCG5WMAv19RMiq/rQRRv0adFGCE5thxfcDDPutLHzAKUAw3V72QflCLFZe+M6p/psLux8Ssu4SV+od20kAbZjXTLYyKoeJ3oAu/aufgt9ndxasP+bGH+mnEg8gGUWrHbBYz2fBAZK3ASYlMevIAW1/dqfS2405FRCZWAlp6NlVDsNEcD3HRu0bjY4nMF0hoLFio225jzYb2VMei/WwLUx7XHH+9dGZL7JJuVj+oUjwmce3CwXhJ4rpZH/aYpByq3mFHgkTeazl4i6TUvUAYj1INon1Kk111IPFDNjOxmFP8hQ1+VOETMlHrtLRnw3AXZk1Z9EjbJqgA4cnyvEfScKSWvTDOgxXwtkGlmBAZh59tTEKg+eu4Th8jtdP54VF8xnbUJTFRXNuLC2/HjalSjeGSjgaIh5/moD78ZvBYXBKQ5iNEwaEx8st2DZhxGvJCrcB6bfci7v2iMuU6GaRY5YRaQDyJl5d/22vWMYqEFmmXuMduxxaHaWL/DZvN5l1jsd6n8HKlK6ef/HxPTSsSRlmmRnpEJYgCgqrPmNE="
        }
         */
        /**   $reqInfo
            {
                "out_refund_no": "21903181737563502",// 商户退款单号
                "out_trade_no": "119109471350010",// 商户订单号
                "refund_account": "REFUND_SOURCE_RECHARGE_FUNDS",// 退款资金来源
                                                REFUND_SOURCE_RECHARGE_FUNDS 可用余额退款/基本账户
                                                REFUND_SOURCE_UNSETTLED_FUNDS 未结算资金退款
                "refund_fee": "1",// 申请退款金额  退款总金额,单位为分
                "refund_id": "50000009922019031808811970746",// 微信退款单号
                "refund_recv_accout": "工商银行借记卡6959",// 退款入账账户
                          1）退回银行卡：
                        {银行名称}{卡类型}{卡尾号}
                        2）退回支付用户零钱:
                        支付用户零钱
                        3）退还商户:
                        商户基本账户
                        商户结算银行账户
                        4）退回支付用户零钱通:
                        支付用户零钱通
                "refund_request_source": "API",// 退款发起来源   API接口  VENDOR_PLATFORM商户平台
                "refund_status": "SUCCESS",// 退款状态  SUCCESS-退款成功   CHANGE-退款异常   REFUNDCLOSE—退款关闭
                "settlement_refund_fee": "1",// 退款金额  退款金额=申请退款金额-非充值代金券退款金额，退款金额<=申请退款金额
                "settlement_total_fee": "5",// 应结订单金额  当该订单有使用非充值券时，返回此字段。应结订单金额=订单金额-非充值代金券金额，应结订单金额<=订单金额。
                "success_time": "2019-03-18 17:38:37",// 退款成功时间  资金退款至用户帐号的时间，格式2017-12-15 09:46:01
                "total_fee": "5",// 订单金额 订单总金额，单位为分，只能为整数
                "transaction_id": "4200000279201903189120405440" // 微信订单号
            }
         **/

        $response = $app->handleRefundedNotify(function ($message, $reqInfo, $fail) use(&$request, &$app) {
            Log::info('微信支付日志 退款结果通知--回调$message-->' . __FUNCTION__, [$message]);
            Log::info('微信支付日志 退款结果通知--回调$reqInfo-->' . __FUNCTION__, [$reqInfo]);
            Log::info('微信支付日志 退款结果通知--回调 $fail-->' . __FUNCTION__, [$fail]);

            try{
                $res = CTAPIWalletRecordBusiness::refundWXNotify($request, $this, $reqInfo, 1);
                Log::info('微信支付日志 $res true -->' . __FUNCTION__, [$res]);
                // if(is_numeric($res) && $res == 1) return true;
                // return $fail($res);
                return true;
            } catch ( \Exception $e) {
                Log::info('微信支付日志 error-->' . __FUNCTION__, [$e->getMessage()]);
                // throws('失败；信息[' . $e->getMessage() . ']');
                return $fail($e->getMessage());
            }
            // return $fail('测试');
            // 其中 $message['req_info'] 获取到的是加密信息
            // $reqInfo 为 message['req_info'] 解密后的信息
            // 你的业务逻辑...
            // return true; // 返回 true 告诉微信“我已处理完成”
            // 或返回错误原因 $fail('参数格式校验错误');
        });
        return $response;// $response->send();
        // return ajaxDataArr(1, 'wechatNotify', '');
    }

    //  扫码支付通知
    public function sweepCodePayNotify(Request $request)
    {
        // $this->InitParams($request);
        // 日志
        $requestLog = [
            'files'       => $request->file(),
            'posts'  => $request->post(),
            'input'      => $request->input(),
            'post_data' => apiGetPost(),
        ];
        Log::info('微信支付日志 扫码支付通知-->' . __FUNCTION__,$requestLog);
        $app = app('wechat.payment');
        // 扫码支付通知接收第三个参数 `$alert`，如果触发该函数，会返回“业务错误”到微信服务器，触发 `$fail` 则返回“通信错误”
        $response = $app->handleScannedNotify(function ($message, $fail, $alert) use ($app) {
            Log::info('微信支付日志 退款结果通知--回调$message-->' . __FUNCTION__, [$message]);
            Log::info('微信支付日志 退款结果通知--回调 $fail-->' . __FUNCTION__, [$fail]);
            Log::info('微信支付日志 退款结果通知--回调$alert-->' . __FUNCTION__, [$alert]);
            // 如：$alert('商品已售空');
            // 如业务流程正常，则要调用“统一下单”接口，并返回 prepay_id 字符串，代码如下
            $result = $app->order->unify([
                'trade_type' => 'NATIVE',
                'product_id' => $message['product_id'],
                // ...
            ]);

            return $result['prepay_id'];
        });

        return $response;// $response->send();
    }

    //  手动查询退单结果并操作记录
    public function operateRefundByNo(Request $request){
        $out_refund_no = CommonRequest::get($request, 'out_refund_no');// 系统退款单号
        $app = app('wechat.payment');
        $resultSuccess = [];
            // 重试 3次 6秒
//                    $queryNum = 0;
//                    while(true)   #循环获取锁
//                    {
//                        $queryNum++;
//                        $delay = mt_rand(2 * 1000 * 1000, 3 * 1000 * 1000);
//                        usleep($delay);//usleep($delay * 1000);

                        $resultQuery = easyWechatPay::queryByOutRefundNumber($app, $out_refund_no);
                        Log::info('微信支付日志 退款结果查询情况$resultQuery-->' . __FUNCTION__,[$resultQuery]);
                        // 如果成功，则修改退款单为成功
                        $quest_result_code = $resultQuery['result_code'] ?? '';
                        $quest_refund_status = $resultQuery['refund_status_0'] ?? '';
                        Log::info('微信支付日志 退款结果查询情况 $quest_result_code-->' . __FUNCTION__,[$quest_result_code]);
                        Log::info('微信支付日志 退款结果查询情况 $quest_refund_status-->' . __FUNCTION__,[$quest_refund_status]);
                        if($quest_result_code == 'SUCCESS' && $quest_refund_status == 'SUCCESS' ) {
                            $quest_return_msg = $resultQuery['return_msg'] ?? '';// 失败原因
                            $resultSuccess = CTAPIWalletRecordBusiness::refundApplyWXFail($request, $this, $out_refund_no, $quest_refund_status, $quest_return_msg);
                            Log::info('微信支付日志 退款申请业务成功自动更新记录-->' . __FUNCTION__,[$resultSuccess]);
                        }
//                        if($quest_refund_status == 'SUCCESS' || $queryNum >= 3) break;
//                    }
        $request = ['resultQuery' => $resultQuery, 'resultSuccess' => $resultSuccess];
        return ajaxDataArr(1, $request, '');
    }


    // **************公用重写方法********************开始*********************************
    /**
     * 公用列表页 --- 可以重写此方法--需要时重写
     *  主要把要传递到视图或接口的数据 ---放到 $reDataArr 数组中
     * @param Request $request
     * @param array $reDataArr // 需要返回的参数
     * @param array $extendParams // 扩展参数
     *   $extendParams = [
     *      'pageNum' => 1,// 页面序号  同 属性 $fun_id【查看它指定的】 (其它根据具体的业务单独指定)
     *      'returnType' => 1,// 返回类型 1 视图[默认] 2 ajax请求的json数据[同视图数据，只是不显示在视图，是ajax返回]
     *                          4 ajax 直接返回 $exeFun 或 $extendParams['doFun'] 方法的执行结果 8 视图 直接返回 $exeFun 或 $extendParams['doFun'] 方法的执行结果
     *      'view' => 'index', // 显示的视图名 默认index
     *      'hasJudgePower' => true,// 是否需要判断登录权限 true:判断[默认]  false:不判断
     *      'doFun' => 'doListPage',// 具体的业务方法，动态或 静态方法 默认'' 可有返回值 参数  $request,  &$reDataArr, $extendParams ；
     *                               doListPage： 列表页； doInfoPage：详情页
     *      'params' => [],// 需要传入 doFun 的数据 数组[一维或多维]
     *  ];
     * @return mixed 无返回值
     * @author zouyan(305463219@qq.com)
     */
    public function doListPage(Request $request, &$reDataArr, $extendParams = []){
        // $pageNum = $extendParams['pageNum'] ?? 1;// 1->1 首页；2->2 列表页； 12->2048 弹窗选择页面；
        // $user_info = $this->user_info;
        // $id = $extendParams['params']['id'];

//        // 拥有者类型1平台2企业4个人
//        $reDataArr['adminType'] =  AbilityJoin::$adminTypeArr;
//        $reDataArr['defaultAdminType'] = -1;// 列表页默认状态

    }

    /**
     * 公用详情页 --- 可以重写此方法-需要时重写
     *  主要把要传递到视图或接口的数据 ---放到 $reDataArr 数组中
     * @param Request $request
     * @param array $reDataArr // 需要返回的参数
     * @param array $extendParams // 扩展参数
     *   $extendParams = [
     *      'pageNum' => 1,// 页面序号  同 属性 $fun_id【查看它指定的】 (其它根据具体的业务单独指定)
     *      'returnType' => 1,// 返回类型 1 视图[默认] 2 ajax请求的json数据[同视图数据，只是不显示在视图，是ajax返回]
     *                          4 ajax 直接返回 $exeFun 或 $extendParams['doFun'] 方法的执行结果 8 视图 直接返回 $exeFun 或 $extendParams['doFun'] 方法的执行结果
     *      'view' => 'index', // 显示的视图名 默认index
     *      'hasJudgePower' => true,// 是否需要判断登录权限 true:判断[默认]  false:不判断
     *      'doFun' => 'doListPage',// 具体的业务方法，动态或 静态方法 默认'' 可有返回值 参数  $request,  &$reDataArr, $extendParams ；
     *                               doListPage： 列表页； doInfoPage：详情页
     *      'params' => [],// 需要传入 doFun 的数据 数组[一维或多维]
     *  ];
     * @return mixed 无返回值
     * @author zouyan(305463219@qq.com)
     */
    public function doInfoPage(Request $request, &$reDataArr, $extendParams = []){
        // $pageNum = $extendParams['pageNum'] ?? 1;// 5->16 添加页； 7->64 编辑页；8->128 ajax详情； 35-> 17179869184 详情页
//        // $user_info = $this->user_info;
//        $id = $extendParams['params']['id'] ?? 0;
//
////        // 拥有者类型1平台2企业4个人
////        $reDataArr['adminType'] =  AbilityJoin::$adminTypeArr;
////        $reDataArr['defaultAdminType'] = -1;// 列表页默认状态
//        $info = [
//            'id'=>$id,
//            //   'department_id' => 0,
//        ];
//        $operate = "添加";
//
//        if ($id > 0) { // 获得详情数据
//            $operate = "修改";
//            $info = CTAPIRrrDdddBusiness::getInfoData($request, $this, $id, [], '', []);
//        }
//        // $reDataArr = array_merge($reDataArr, $resultDatas);
//        $reDataArr['info'] = $info;
//        $reDataArr['operate'] = $operate;

    }
    // **************公用重写方法********************结束*********************************

}
