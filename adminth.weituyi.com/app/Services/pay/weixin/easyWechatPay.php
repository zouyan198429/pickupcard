<?php
namespace App\Services\pay\weixin;

use Illuminate\Support\Facades\Log;
use function EasyWeChat\Kernel\Support\generate_sign;

class easyWechatPay
{
    /**
     * 统一下单  重新发起一笔支付要使用原订单号，避免重复支付；已支付过或已调用关单、撤销（请见后文的API列表）的订单号不能重新发起支付。--支付未成功的订单号，可以重新发起支付
     * @param $app  obj 当前对象
     * @param $params  参数 参考 https://pay.weixin.qq.com/wiki/doc/api/wxa/wxa_api.php?chapter=9_1
     *
        $params = [
            'openid' => '', // 必填  用户标识
            'body' => '', // 必填商品描述 String(128)
            'out_trade_no' => '', // 必填 商户订单号 String(32) 商户系统内部订单号，要求32个字符内，只能是数字、大小写字母_-|*且在同一个商户号下唯一
            'total_fee' => '', // 标价金额 Int 订单总金额，单位为分
            'trade_type' => '', // 交易类型  小程序取值如下：JSAPI
        ];
     * @param int  $operateType 1 小程序 2 app 4 js  8   16
     * @return  mixed
     * @author zouyan(305463219@qq.com)
     */
    public static function miniProgramunify(&$app, $params = [], $operateType = 1){
        $unifyParams = [
            // 'body' => '测试支付',
            // 'out_trade_no' => '1',
            // 'total_fee' => 1,
            // 'spbill_create_ip' => '123.12.12.123', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
            // 'notify_url' => 'https://pay.weixin.qq.com/wxpay/pay.action', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            // 'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            // 'openid' => $userInfo['mini_openid'], // 'oUpF8uMuAJO_M2pxb1Q9zNjWeS6o',
        ];
        $unifyParams = array_merge($unifyParams, $params);
        $result = $app->order->unify($unifyParams);
        Log::info('微信支付日志--统一下单 unify 返回：-->' . __FUNCTION__, ['params'=> $unifyParams,'result'=> $result,]);
        /*
        {
            "return_code": "SUCCESS",// 表示通信状态: SUCCESS 成功
            "return_msg": "OK",
            "appid": "wxcb82783fe211782f",
            "mch_id": "1527642191",
            "nonce_str": "ltRgaeH2QepSUpJI",
            "sign": "F1B7E4ECE2B5A4B385BD40A6FC56D040",
            "result_code": "SUCCESS",//  FAIL:失败;SUCCESS:成功
            "prepay_id": "wx17225920132209a91a14f7514271219036",
            "trade_type": "JSAPI"
        }
         *
         */

        static::returnErr($app, $result);// 通信错误判断

        $result_code = $result['result_code'] ?? '';// result_code  业务结果  SUCCESS/FAIL


        // 小程序
        // 如果成功生成统一下单的订单，那么进行二次签名
        if ($result_code === 'SUCCESS' && ($operateType & 1) == 1 ) {
            // 二次签名的参数必须与下面相同
            $params = [
                'appId' => $result['appid'],// '你的小程序的appid',
                'timeStamp' => time(),
                'nonceStr' => $result['nonce_str'],
                'package' => 'prepay_id=' . $result['prepay_id'],
                'signType' => 'MD5',
            ];

            // config('wechat.payment.default.key')为商户的key
            $params['paySign'] = generate_sign($params, config('wechat.payment.default.key'));
            Log::info('微信支付日志 二次签名 -->' . __FUNCTION__, [$params]);

            /*
            {
                "appId": "wxcb82783fe211782f",
                "timeStamp": 1552836530,
                "nonceStr": "n2HYFHaNb79m0C9E",
                "package": "prepay_id=wx1723285008427722494c5c901036576914",
                "signType": "MD5",
                "paySign": "92D034998AAAF2A5C58D93C8743C13CD"
            }
            */
            $params = array_merge($params, [
                'mch_id' => $result['mch_id'],
                'prepay_id' => $result['prepay_id'],
            ]);
            return $params;
        }else if ($result_code === 'SUCCESS'){
            return $result;
        }else {
            // $prepay_id = $result['prepay_id'] ?? '';
//            if(empty($prepay_id))  throws('统一下单失败');
            throws('统一下单失败');
//            return $result;
        }
    }

    /**
     * 根据商户订单号查询
     * @param $app  obj 当前对象
     * @param string  $out_trade_no 商户系统内部的订单号（out_trade_no）
     * @return  array
     * @author zouyan(305463219@qq.com)
     */
    public static function queryByOutTradeNumber(&$app, $out_trade_no){
        // 根据商户订单号查询
        // $app = app('wechat.payment');
        $result = $app->order->queryByOutTradeNumber($out_trade_no);// 商户系统内部的订单号（out_trade_no）
        Log::info('微信支付日志 根据商户订单号查询$result-->' . __FUNCTION__, ['request' => $out_trade_no, 'result' => $result]);

        static::returnErr($app, $result);// 通信错误判断

        /** 交易状态
        SUCCESS—支付成功
        REFUND—转入退款
        NOTPAY—未支付
        CLOSED—已关闭
        REVOKED—已撤销（付款码支付）
        USERPAYING--用户支付中（付款码支付）
        PAYERROR--支付失败(其他原因，如银行返回失败)
        支付状态机请见下单API页面
         */
        $trade_state = $result['trade_state'] ?? '';// 交易状态

        return $result;
        /* 商户订单号查询 结果
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
        */
    }


    /**
     * 根据微信订单号查询
     * @param $app  obj 当前对象
     * @param string  transaction_id 微信订单号（transaction_id）
     * @return  array
     * @author zouyan(305463219@qq.com)
     */
    public static function queryByTransactionId(&$app, $transaction_id){

       // 根据微信订单号查询
        // $app = app('wechat.payment');
        $result = $app->order->queryByTransactionId($transaction_id);// "微信订单号（transaction_id）"

        Log::info('微信支付日志 根据微信订单号查询 $result-->' . __FUNCTION__, ['request' => $transaction_id, 'result' => $result]);

        static::returnErr($app, $result);// 通信错误判断
        /** 交易状态
        SUCCESS—支付成功
        REFUND—转入退款
        NOTPAY—未支付
        CLOSED—已关闭
        REVOKED—已撤销（付款码支付）
        USERPAYING--用户支付中（付款码支付）
        PAYERROR--支付失败(其他原因，如银行返回失败)
        支付状态机请见下单API页面
         */
        $trade_state = $result['trade_state'] ?? '';// 交易状态

        return $result;
            /*
             *
            {
                "return_code": "SUCCESS",
                "return_msg": "OK",
                "appid": "wxcb82783fe211782f",
                "mch_id": "1527642191",
                "nonce_str": "wUod7PAIHe8zLULJ",
                "sign": "BC92D6BC06182CF2398C8560F57A5DC9",
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
                "trade_state": "SUCCESS",
                "cash_fee": "1",
                "trade_state_desc": "支付成功"
            }
             *
             */
    }

    /**
     * 关闭订单  注意：订单生成后不能马上调用关单接口，最短调用时间间隔为5分钟。
     * @param $app  obj 当前对象
     * @param string  $out_trade_no 商户系统内部的订单号（out_trade_no）
     * @return  array
     * @author zouyan(305463219@qq.com)
     */
    public static function closeByOutTradeNumber(&$app, $out_trade_no)
    {
        $result = $app->order->close($out_trade_no);
        Log::info('微信支付日志 关闭订单:-->' . __FUNCTION__, ['request' => $out_trade_no, 'result' => $result]);

        static::returnErr($app, $result);// 通信错误判断
        $result_code = $result['result_code'] ?? '';// result_code  业务结果  SUCCESS/FAIL
        return $result;
    }

    // --------------申请退款--------------------------------
    /*
    当交易发生之后一段时间内，由于买家或者卖家的原因需要退款时，卖家可以通过退款接口将支付款退还给买家，微信支付将在收到退款请求并且验证成功之后，
    按照退款规则将支付款按原路退到买家帐号上。
    注意：
    1、交易时间超过一年的订单无法提交退款；
    2、微信支付退款支持单笔交易分多次退款，多次退款需要提交原支付订单的商户订单号和设置不同的退款单号。
        一笔退款失败后重新提交，要采用原来的退款单号。
        总退款金额不能超过用户实际支付金额。
    */

    /**
     * 根据微信订单号退款
     * @param $app  obj 当前对象
     * @param string  $transactionId 微信订单号
     * @param string  $refundNumber 商户退款单号
     * @param int  $totalFee 订单金额 单位为分，只能为整数
     * @param int  $refundFee 退款金额 单位为分，只能为整数
     * @param array  $config 其他参数
     * @return  array
     * @author zouyan(305463219@qq.com)
     */
    public static function refundByTransactionId(&$app, $transactionId, $refundNumber, $totalFee, $refundFee, $config = [])
    {
        // 参数分别为：微信订单号、商户退款单号、订单金额、退款金额、其他参数
        $result = $app->refund->byTransactionId($transactionId, $refundNumber, $totalFee, $refundFee, $config);
        Log::info('微信支付日志 根据微信订单号退款:-->' . __FUNCTION__, ['request' => [
            'transactionId' => $transactionId
            ,'refundNumber' => $refundNumber
            ,'totalFee' => $totalFee
            ,'refundFee' => $refundFee
            ,'config' => $config
        ], 'result' => $result]);

        static::returnErr($app, $result);// 通信错误判断
        $result_code = $result['result_code'] ?? '';// 业务结果  SUCCESS/FAIL SUCCESS/FAIL  SUCCESS退款申请接收成功，结果通过退款查询接口查询  FAIL 提交业务失败

        return $result;
//        // Example:
//        $result = $app->refund->byTransactionId('transaction-id-xxx', 'refund-no-xxx', 10000, 10000, [
//            // 可在此处传入其他参数，详细参数见微信支付文档
//            'refund_desc' => '商品已售完',
//        ]);
        /**
         * 返回结果同下面函数
         */
    }

    /**
     * 根据商户订单号退款
     * @param $app  obj 当前对象
     * @param string  $out_trade_no 商户系统内部的订单号（out_trade_no）
     * @param string  $refundNumber 商户退款单号
     * @param int  $totalFee 订单金额 单位为分，只能为整数
     * @param int  $refundFee 退款金额 单位为分，只能为整数
     * @param array  $config 其他参数
     * @return  array
     * @author zouyan(305463219@qq.com)
     */
    public static function refundByOutTradeNumber(&$app, $out_trade_no, $refundNumber, $totalFee, $refundFee, $config = [])
    {
        // 参数分别为：商户订单号、商户退款单号、订单金额、退款金额、其他参数
        $result = $app->refund->byOutTradeNumber($out_trade_no, $refundNumber, $totalFee, $refundFee, $config);

        Log::info('微信支付日志 根据商户订单号退款:-->' . __FUNCTION__, ['request' => [
            'transactionId' => $out_trade_no
            ,'refundNumber' => $refundNumber
            ,'totalFee' => $totalFee
            ,'refundFee' => $refundFee
            ,'config' => $config
        ], 'result' => $result]);

        static::returnErr($app, $result);// 通信错误判断
        $result_code = $result['result_code'] ?? '';// 业务结果  SUCCESS/FAIL SUCCESS/FAIL  SUCCESS退款申请接收成功，结果通过退款查询接口查询  FAIL 提交业务失败


//        // Example:
//        $result = $app->refund->byOutTradeNumber('out-trade-no-xxx', 'refund-no-xxx', 20000, 1000, [
//            // 可在此处传入其他参数，详细参数见微信支付文档
//            'refund_desc' => '退运费',
//        ]);
        return $result;
        /**
        {
            "return_code": "SUCCESS",// 业务结果  SUCCESS/FAIL  SUCCESS退款申请接收成功，结果通过退款查询接口查询   FAIL 提交业务失败
            "return_msg": "OK",
            "appid": "wxcb82783fe211782f",
            "mch_id": "1527642191",
            "nonce_str": "C1hieOOwwXsDZrNd",// 随机字符串
            "sign": "AFCB8D5B9205827FAB3A561AC4F4B349",// 签名
            "result_code": "SUCCESS",
            "transaction_id": "4200000279201903189120405440",// 微信订单号
            "out_trade_no": "119109471350010",// 商户订单号
            "out_refund_no": "21903181646303501",// 商户退款单号
            "refund_id": "50000009922019031808806393608",// 微信退款单号
            "refund_channel": null,
            "refund_fee": "2",// 退款金额 ,  退款总金额,单位为分,可以做部分退款 --当前这次的
            "coupon_refund_fee": "0",// 代金券退款总金额
            "total_fee": "5",// 标价金额  订单总金额，单位为分，只能为整数
            "cash_fee": "5",// 现金支付金额 现金支付金额，单位为分，只能为整数
            "coupon_refund_count": "0",// 退款代金券使用数量
            "cash_refund_fee": "2"// 现金退款金额 现金退款金额，单位为分，只能为整数 --当前这次的
        }
         *
         */
    }
// --------------查询退款-------------------------------------------
// 提交退款申请后，通过调用该接口查询退款状态。退款有一定延时，用零钱支付的退款20分钟内到账，银行卡支付的退款3个工作日后重新查询退款状态。
// 可通过 4 种不同类型的单号查询：
// 微信订单号 => queryByTransactionId($transactionId) --前面订单查询
// 商户订单号 => queryByOutTradeNumber($outTradeNumber)--前面订单查询
// 商户退款单号 => queryByOutRefundNumber($outRefundNumber)
// 微信退款单号 => queryByRefundId($refundId)

    /**
     * 查询退款--根据 商户退款单号-- 只返回当次的
     * @param $app  obj 当前对象
     * @param string  $outRefundNumber 商户系统内部的退款单号
     * @return  array
     * @author zouyan(305463219@qq.com)
     */
    public static function queryByOutRefundNumber(&$app, $outRefundNumber){
        // 根据商户订单号查询
        // $app = app('wechat.payment');
        $result = $app->refund->queryByOutRefundNumber($outRefundNumber);
        Log::info('微信支付日志 查询退款--根据 商户退款单号-->' . __FUNCTION__, ['request' => $outRefundNumber, 'result' => $result]);

        static::returnErr($app, $result);// 通信错误判断

        $result_code = $result['result_code'] ?? '';// 业务结果  SUCCESS/FAIL SUCCESS退款申请接收成功，结果通过退款查询接口查询 FAIL

        /*   refund_status_$n  退款状态
        退款状态：
        SUCCESS—退款成功
        REFUNDCLOSE—退款关闭。
        PROCESSING—退款处理中
        CHANGE—退款异常，退款到银行发现用户的卡作废或者冻结了，导致原路退款银行卡失败，可前往商户平台（pay.weixin.qq.com）-交易中心，手动处理此笔退款。$n为下标，从0开始编号。
        */

        return $result;
        /**
        {
        "appid": "wxcb82783fe211782f",
        "cash_fee": "5",// 现金支付金额，单位为分，只能为整数
        "mch_id": "1527642191",
        "nonce_str": "ROLGSjReXWfcFTHV",
        "out_refund_no_0": "21903181646303501",// 商户退款单号
        "out_trade_no": "119109471350010",// 商户订单号
        "refund_account_0": "REFUND_SOURCE_UNSETTLED_FUNDS",
        "refund_channel_0": "ORIGINAL",// 退款渠道 ORIGINAL—原路退款;BALANCE—退回到余额;OTHER_BALANCE—原账户异常退到其他余额账户;OTHER_BANKCARD—原银行卡异常退到其他银行卡
        "refund_count": "1",// 当前返回退款笔数
        "refund_fee": "2",
        "refund_fee_0": "2",// 申请退款金额 退款总金额,单位为分,可以做部分退款
        "refund_id_0": "50000009922019031808806393608",  // 微信退款单号
        "refund_recv_accout_0": "工商银行借记卡6959",
        "refund_status_0": "SUCCESS",// 退款状态  款状态： SUCCESS—退款成功;  REFUNDCLOSE—退款关闭。;PROCESSING—退款处理中
         *                          CHANGE—退款异常，退款到银行发现用户的卡作废或者冻结了，导致原路退款银行卡失败，可前往商户平台（pay.weixin.qq.com）-交易中心，手动处理此笔退款。$n为下标，从0开始编号。
        "refund_success_time_0": "2019-03-18 16:47:06",
        "result_code": "SUCCESS",// 业务结果   SUCCESS/FAIL  SUCCESS退款申请接收成功，结果通过退款查   询接口查询  FAIL
        "return_code": "SUCCESS",
        "return_msg": "OK",
        "sign": "0AE490996FD14965FA1E9B94F29D3799",
        "total_fee": "5",// 订单总金额，单位为分，只能为整数
        "transaction_id": "4200000279201903189120405440"// 微信订单号
        }
         */
    }


    /**
     * 查询退款--根据 微信退款单号-- 只返回当次的
     * @param $app  obj 当前对象
     * @param string  $refundId 微信退款单号
     * @return  array
     * @author zouyan(305463219@qq.com)
     */
    public static function queryByRefundId(&$app, $refundId){

        // 根据微信订单号查询
        // $app = app('wechat.payment');
        $result = $app->refund->queryByRefundId($refundId);
        Log::info('微信支付日志 查询退款--根据 微信退款单号 $result-->' . __FUNCTION__, ['request' => $refundId, 'result' => $result]);

        static::returnErr($app, $result);// 通信错误判断

        $result_code = $result['result_code'] ?? '';// 业务结果  SUCCESS/FAIL SUCCESS退款申请接收成功，结果通过退款查询接口查询 FAIL

        /*   refund_status_$n  退款状态
        退款状态：
        SUCCESS—退款成功
        REFUNDCLOSE—退款关闭。
        PROCESSING—退款处理中
        CHANGE—退款异常，退款到银行发现用户的卡作废或者冻结了，导致原路退款银行卡失败，可前往商户平台（pay.weixin.qq.com）-交易中心，手动处理此笔退款。$n为下标，从0开始编号。
        */

        return $result;
        /*
         *
            {
                "appid": "wxcb82783fe211782f",
                "cash_fee": "5",
                "mch_id": "1527642191",
                "nonce_str": "s5bVBKfw9wn6RxGP",
                "out_refund_no_0": "21903181646303501",
                "out_trade_no": "119109471350010",
                "refund_account_0": "REFUND_SOURCE_UNSETTLED_FUNDS",
                "refund_channel_0": "ORIGINAL",
                "refund_count": "1",
                "refund_fee": "2",
                "refund_fee_0": "2",
                "refund_id_0": "50000009922019031808806393608",
                "refund_recv_accout_0": "工商银行借记卡6959",
                "refund_status_0": "SUCCESS",
                "refund_success_time_0": "2019-03-18 16:47:06",
                "result_code": "SUCCESS",
                "return_code": "SUCCESS",
                "return_msg": "OK",
                "sign": "D656DD999B3DF2B553C6E2087F0179BB",
                "total_fee": "5",
                "transaction_id": "4200000279201903189120405440"
            }
         *
         */
    }

    /**
     * 根据商户订单号查询 -- 多次退款，返回多次的
     * @param $app  obj 当前对象
     * @param string  $out_trade_no 商户系统内部的订单号（out_trade_no）
     * @return  array
     * @author zouyan(305463219@qq.com)
     */
    public static function queryRefundByOutTradeNumber(&$app, $out_trade_no){
        // 根据商户订单号查询
        // $app = app('wechat.payment');
        $result = $app->refund->queryByOutTradeNumber($out_trade_no);// 商户系统内部的订单号（out_trade_no）
        Log::info('微信支付日志 根据商户订单号查询$result-->' . __FUNCTION__, ['request' => $out_trade_no, 'result' => $result]);

        static::returnErr($app, $result);// 通信错误判断

        $result_code = $result['result_code'] ?? '';// 业务结果  SUCCESS/FAIL SUCCESS退款申请接收成功，结果通过退款查询接口查询 FAIL
        // refund_count  当前返回退款笔数
        /*   refund_status_$n  退款状态
        退款状态：
        SUCCESS—退款成功
        REFUNDCLOSE—退款关闭。
        PROCESSING—退款处理中
        CHANGE—退款异常，退款到银行发现用户的卡作废或者冻结了，导致原路退款银行卡失败，可前往商户平台（pay.weixin.qq.com）-交易中心，手动处理此笔退款。$n为下标，从0开始编号。
        */
        return $result;
        /**
            {
                "appid": "wxcb82783fe211782f",
                "cash_fee": "5",
                "mch_id": "1527642191",
                "nonce_str": "PGoJJJ7CUdJkN2VW",
                "out_refund_no_0": "21903181646303501",
                "out_refund_no_1": "21903181737563502",
                "out_trade_no": "119109471350010",
                "refund_account_0": "REFUND_SOURCE_UNSETTLED_FUNDS",
                "refund_account_1": "REFUND_SOURCE_UNSETTLED_FUNDS",
                "refund_channel_0": "ORIGINAL",
                "refund_channel_1": "ORIGINAL",
                "refund_count": "2",
                "refund_fee": "3",// 总退款金额[多次退，则是多次退款的和]
                "refund_fee_0": "2",
                "refund_fee_1": "1",
                "refund_id_0": "50000009922019031808806393608",
                "refund_id_1": "50000009922019031808811970746",
                "refund_recv_accout_0": "工商银行借记卡6959",
                "refund_recv_accout_1": "工商银行借记卡6959",
                "refund_status_0": "SUCCESS",
                "refund_status_1": "SUCCESS",
                "refund_success_time_0": "2019-03-18 16:47:06",
                "refund_success_time_1": "2019-03-18 17:38:37",
                "result_code": "SUCCESS",
                "return_code": "SUCCESS",
                "return_msg": "OK",
                "sign": "E47372C689A6C227ED99BBA8C0A89031",
                "total_fee": "5",
                "transaction_id": "4200000279201903189120405440"
            }
         *
         */
    }


    /**
     * 根据微信订单号查询 -- 多次退款，返回多次的
     * @param $app  obj 当前对象
     * @param string  transaction_id 微信订单号（transaction_id）
     * @return  array
     * @author zouyan(305463219@qq.com)
     */
    public static function queryRefundByTransactionId(&$app, $transaction_id){

        // 根据微信订单号查询
        // $app = app('wechat.payment');
        $result = $app->refund->queryByTransactionId($transaction_id);// "微信订单号（transaction_id）"

        Log::info('微信支付日志 根据微信订单号查询 $result-->' . __FUNCTION__, ['request' => $transaction_id, 'result' => $result]);

        static::returnErr($app, $result);// 通信错误判断

        $result_code = $result['result_code'] ?? '';// 业务结果  SUCCESS/FAIL SUCCESS退款申请接收成功，结果通过退款查询接口查询 FAIL
        // refund_count  当前返回退款笔数
        /*   refund_status_$n  退款状态
        退款状态：
        SUCCESS—退款成功
        REFUNDCLOSE—退款关闭。
        PROCESSING—退款处理中
        CHANGE—退款异常，退款到银行发现用户的卡作废或者冻结了，导致原路退款银行卡失败，可前往商户平台（pay.weixin.qq.com）-交易中心，手动处理此笔退款。$n为下标，从0开始编号。
        */

        return $result;
        /**
         *
            {
                "appid": "wxcb82783fe211782f",
                "cash_fee": "5",
                "mch_id": "1527642191",
                "nonce_str": "NpGlDHGFCXBi7snQ",
                "out_refund_no_0": "21903181646303501",
                "out_refund_no_1": "21903181737563502",
                "out_trade_no": "119109471350010",
                "refund_account_0": "REFUND_SOURCE_UNSETTLED_FUNDS",
                "refund_account_1": "REFUND_SOURCE_UNSETTLED_FUNDS",
                "refund_channel_0": "ORIGINAL",
                "refund_channel_1": "ORIGINAL",
                "refund_count": "2",
                "refund_fee": "3",
                "refund_fee_0": "2",
                "refund_fee_1": "1",
                "refund_id_0": "50000009922019031808806393608",
                "refund_id_1": "50000009922019031808811970746",
                "refund_recv_accout_0": "工商银行借记卡6959",
                "refund_recv_accout_1": "工商银行借记卡6959",
                "refund_status_0": "SUCCESS",
                "refund_status_1": "SUCCESS",
                "refund_success_time_0": "2019-03-18 16:47:06",
                "refund_success_time_1": "2019-03-18 17:38:37",
                "result_code": "SUCCESS",
                "return_code": "SUCCESS",
                "return_msg": "OK",
                "sign": "83150F2099A7123767E68681597A2331",
                "total_fee": "5",
                "transaction_id": "4200000279201903189120405440"
            }
         *
         */
    }


    /**
     * 返回结果 错误处理
     * @param $app  obj 当前对象
     * @param array  $result 返回的结果
     * @return  array
     * @author zouyan(305463219@qq.com)
     */
    public static function returnErr(&$app, $result){
        $return_code = $result['return_code'] ?? '';// 返回状态码 String(16) return_code  SUCCESS/FAIL  此字段是通信标识
        $return_msg = $result['return_msg'] ?? '失败';// 返回信息 String(128)  当return_code为FAIL时返回信息为错误原因 ，例如  签名失败  参数格式校验错误
        if($return_code !== 'SUCCESS') throws($return_msg);
    }
}