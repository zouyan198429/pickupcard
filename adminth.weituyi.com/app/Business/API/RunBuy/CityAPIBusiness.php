<?php
// 城市[三级分类]
namespace App\Business\API\RunBuy;


class CityAPIBusiness extends BasePublicAPIBusiness
{
    public static $model_name = 'RunBuy\City';
    public static $table_name = 'city';// 表名称

    /**
     * 跑城市订单过期未接单自动关闭脚本--每一分钟跑一次
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public static function autoCancelOrdes()
    {
//        $company_id = $controller->company_id;
//        $user_id = $controller->user_id;
//        if(isset($saveData['real_name']) && empty($saveData['real_name'])  ){
//            throws('联系人不能为空！');
//        }
        $company_id = 0;
        $user_id = 0;
        // 调用新加或修改接口
        $apiParams = [];
        $params = static::exeDBBusinessMethodBS('', 'autoCityCancelOrder', $apiParams, $company_id, 1);
        $out_refund_nos = [];
        if(!empty($params)){
            $out_refund_nos = WalletRecordAPIBusiness::orderCancel($company_id, $user_id, $params, 1);
        }
        return $out_refund_nos;
    }
}