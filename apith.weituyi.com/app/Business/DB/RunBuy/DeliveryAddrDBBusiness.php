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
}
