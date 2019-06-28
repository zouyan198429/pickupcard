<?php
namespace App\Business\Controller\API\RunBuy;

use App\Business\Controller\API\BasicCTAPIBusiness;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as Controller;

class BasicPublicCTAPIBusiness extends BasicCTAPIBusiness
{
    public static $model_name = '';

    /**
     * 生成单号
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int  $orderType 要保存或修改的数组 1 订单号 2 退款订单 3 支付跑腿费  4 追加跑腿费 5 冲值  6 提现 7 压金或保证金
     * @return  int
     * @author zouyan(305463219@qq.com)
     */
    public static function createSn(Request $request, Controller $controller, $orderType = 1){
        $company_id = $controller->company_id;
        $user_id = $controller->user_id ?? '';
        $namespace = '';
        $prefix = $orderType;
        $midFix = '';
        $backfix = '';
        $length = 6;
        $expireNums = [];
        $needNum = 0;
        $dataFormat = '';
        switch ($orderType)
        {
            case 1:// 订单
                $userIdBack = str_pad(substr($user_id, -2), 2, '0', STR_PAD_LEFT);
                $midFix = $userIdBack;
                $namespace = 'order' . $userIdBack;
                $length = 4;
                $needNum = 1 + 2 + 8;
//                $expireNums = [
//                  [1000,1100,365 * 24 * 60 * 60]  // 缓存的秒数365 * 24 * 60 * 60
//                ];
                break;
            case 2:// 2 退款订单
            case 3:// 3 支付跑腿费
            case 4:// 4 追加跑腿费
            case 5:// 5 冲值
            case 6:// 6 提现
            case 7:// 7 压金或保证金
                $userIdBack = str_pad(substr($user_id, -2), 2, '0', STR_PAD_LEFT);
                $midFix = $userIdBack;
                $namespace = 'orderRefund' . $userIdBack;
                $length = 2;// 总共一秒一万
                $needNum = 4 + 8;
                $dataFormat = 'ymdHis';

//                $expireNums = [
//                  [1000,1100,365 * 24 * 60 * 60]  // 缓存的秒数365 * 24 * 60 * 60
//                ];
                break;
            default:
        }
        $fixParams = [
            'prefix' => $prefix,// 前缀[1-2位] 可填;可写业务编号等
            'midFix' => $midFix,// 日期后中间缀[1-2位] 可填;适合用户编号里的后2位或其它等
            'backfix' => $backfix,// 后缀[1-2位] 可填;备用
            'expireNums' => $expireNums,// redis设置缓存 ，在两个值之间时 - 二维 [[1,20,'缓存的秒数365 * 24 * 60 * 60'], [40,50,'缓存的秒数']]
            'needNum' => $needNum,// 需要拼接的内容 1 年 2日期 4 自定义日期格式 8 自增的序号
            'dataFormat' => $dataFormat, // needNum 值为 4时的日期格式  'YmdHis'
        ];
        return Tool::makeOrder($namespace , $fixParams, $length);
    }
}