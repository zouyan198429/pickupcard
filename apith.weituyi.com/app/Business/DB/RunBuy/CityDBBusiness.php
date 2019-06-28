<?php
// 城市[三级分类]
namespace App\Business\DB\RunBuy;

use App\Services\Map\Map;
use App\Services\Map\S2;
use App\Services\Tool;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 *
 */
class CityDBBusiness extends BasePublicDBBusiness
{
    public static $model_name = 'RunBuy\City';
    public static $table_name = 'city';// 表名称

    // 获得记录历史id
    public static function getIdHistory($mainId = 0, &$mainDBObj = null, &$historyDBObj = null){
        // $mainDBObj = null ;
        // $historyDBObj = null ;
        return static::getHistoryId($mainDBObj, $mainId, CityHistoryDBBusiness::$model_name
            , CityHistoryDBBusiness::$table_name, $historyDBObj, ['city_table_id' => $mainId], []);
    }

    /**
     * 对比主表和历史表是否相同，相同：不更新版本号，不同：版本号+1
     *
     * @param mixed $mId 主表对象主键值
     * @param int $forceIncVersion 如果需要主表版本号+1,是否更新主表 1 更新 ;0 不更新
     * @return array 不同字段的内容 数组 [ '字段名' => ['原表中的值','历史表中的值']]; 空数组：不用版本号+1;非空数组：版本号+1
     * @author zouyan(305463219@qq.com)
     */
    public static function compareHistory($id = 0, $forceIncVersion = 0, &$mainDBObj = null, &$historyDBObj = null){
        // 判断版本号是否要+1
        $historySearch = [
            //  'company_id' => $company_id,
            'city_table_id' => $id,
        ];
        // $mainDBObj = null ;
        // $historyDBObj = null ;
        return static::compareHistoryOrUpdateVersion($mainDBObj, $id, CityHistoryDBBusiness::$model_name
            , CityHistoryDBBusiness::$table_name, $historyDBObj, $historySearch, ['city_table_id'], $forceIncVersion);
    }

    /**
     * 根据id新加或修改单条数据-id 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     *
     * @param array $saveData 要保存或修改的数组
     * @param int  $company_id 企业id
     * @param int $id id
     * @param int $operate_staff_id 操作人id
     * @param int $modifAddOprate 修改时是否加操作人，1:加;0:不加[默认]
     * @return  int 记录id值，
     * @author zouyan(305463219@qq.com)
     */
    public static function replaceById($saveData, $company_id, &$id, $operate_staff_id = 0, $modifAddOprate = 0){

        if(isset($saveData['city_name']) && empty($saveData['city_name'])  ){
            throws('名称不能为空！');
        }

        if(isset($saveData['code']) && empty($saveData['code'])  ){
            throws('城市代码不能为空！');
        }

        // 如果有经纬度信息
        if(isset($saveData['latitude'])){
            $is_city_site = $saveData['is_city_site'] ?? ''; // 是否城市分站0不是1是
            $latitude = $saveData['latitude'] ?? ''; // 纬度
            $longitude = $saveData['longitude'] ?? ''; // 经度
            if( $is_city_site == 1 && ( $latitude == '' || $longitude == '' || ($latitude == '0' && $longitude == '0') ) ){
                throws('经纬度不能为空！');
            }
            $hashs = Map::getGeoHashs($latitude, $longitude);
            $saveData['geohash'] = $hashs[0] ?? '';
            $saveData['geohash3'] = $hashs[3] ?? '';
            $saveData['geohash4'] = $hashs[4] ?? '';
            $saveData['geohash5'] = $hashs[5] ?? '';
            if(!is_numeric($latitude)) $latitude = 0;
            if(!is_numeric($longitude)) $longitude = 0;
            $saveData['lat'] = $latitude;
            $saveData['lng'] = $longitude;
        }

        $parent_id = 0;
        $city_ids = [];
        // 处理所所地区
        $province_id = $saveData['province_id'] ?? 0;
        if(isset($saveData['province_id'])) unset($saveData['province_id']);
        if($province_id > 0){
            $parent_id = $province_id;
            array_push($city_ids, $province_id);
        }

        $city_id = $saveData['city_id'] ?? 0;
        if(isset($saveData['city_id'])) unset($saveData['city_id']);
        if($city_id > 0){
            $parent_id = $city_id;
            array_push($city_ids, $city_id);
        }

        $cityNamePinyin = strtoupper(pinyin_abbr($saveData['city_name']));
        $initial = substr($cityNamePinyin,0,1);
        $saveData = array_merge($saveData, ['parent_id' => $parent_id, 'head' => $cityNamePinyin, 'initial' => $initial]);

        DB::beginTransaction();
        try {
            $childList = [];// 需要修改子级城市父级关系的子级城市
            if($id > 0){
                array_push($city_ids, $id);
                $newCityIds = implode(',', $city_ids) . ',';
                $saveData = array_merge($saveData, ['city_ids' => $newCityIds]);
                // 获得详情
                $info = static::getInfo($id);
                $oldCityIds = $info['city_ids'];
                // 有子级城市，则不能修改所属
                if($info['parent_id'] != $parent_id ){
                    if($parent_id == $id) throws('所属不能选择自己');
                    // throws($newCityIds . '--- ' . $oldCityIds);
                    if (strpos(',' . $newCityIds, ',' . $oldCityIds) === 0) { // 当前记录移动到它的子级了 //  && strpos(',' . $newCityIds, ',' . $oldCityIds) !== false
                        throws('所属不能选择自己的子级所属');
                    }

                    // 获得子级城市信息
                    $queryParams = [
                        'where' => [
                            // ['parent_id', '=' , $id],
                            ['city_ids', 'like', '' . $oldCityIds . '%'],
                            // ['id', '&' , '16=16'],
                        ],
                         'select' => [
                             'id','city_ids','city_name'
                         ]
                    ];
                    $childList = static::getAllList($queryParams,[]);
                    if(count($childList) > 0){
                        $cityPidCount = count($city_ids);// 当前记录级数
                        $oldPIds = explode(',', $oldCityIds);
                        $oldPidCount = count($oldPIds) - 1; // 老记录级数

                        //判断子级是否会超过三级
                        foreach($childList as $temChildCity){
                            $childCityIds = $temChildCity['city_ids'];
                            $childPIds = explode(',', $childCityIds);
                            $childCountPIds = count($childPIds) - 1;// 子记录级数
                            if($cityPidCount + ($childCountPIds - $oldPidCount) > 3){
                                throws('修改所属，子级城市[' . $temChildCity['city_name'] . ']级数超过三级，请先处理子级城市，再修改所属！');
                                break;
                            }
                        }
                    }
                }

                // 判断权限
    //            $judgeData = [
    //                'company_id' => $company_id,
    //            ];
    //            $relations = '';
    //            static::judgePower($id, $judgeData , $company_id , [], $relations);
                if($modifAddOprate) static::addOprate($saveData, $operate_staff_id);

            }else {// 新加;要加入的特别字段
    //            $addNewData = [
    //                'company_id' => $company_id,
    //            ];
    //            $saveData = array_merge($saveData, $addNewData);
                // 加入操作人员信息
                static::addOprate($saveData, $operate_staff_id);
            }

            $saveData['parent_id_history'] = 0;
            if($parent_id > 0) {
                $saveData['parent_id_history'] = static::getIdHistory($parent_id);
            }
            // 新加或修改
            if($id <= 0){// 新加
                $resultDatas = static::create($saveData,$cityObj);
                $id = $resultDatas['id'] ?? 0;
                // 保存父id串
                if($id > 0) {
                    array_push($city_ids, $id);
                    $modifyData = [
                        'city_ids' => implode(',', $city_ids) . ',',
                    ];
                    $saveBoolen = static::saveById($modifyData, $id, $cityObj);
                }

            }else{// 修改
                $modelObj = null;
                $saveBoolen = static::saveById($saveData, $id,$modelObj);
                // 更新子级城市所属
                foreach($childList as $temChildCity) {
                    $childCityId = $temChildCity['id'];
                    $childCityIds = $temChildCity['city_ids'];
                    //$temCityIds = $city_ids;
                    //array_push($temCityIds, $childCityId);
                    $modifyData = [
                        'city_ids' => str_ireplace($oldCityIds, $newCityIds, $childCityIds),
                    ];
                    static::saveById($modifyData, $childCityId, $temChildCity);
                }
                // 修改数据，是否当前版本号 + 1
                static::compareHistory($id, 1);
            }
        } catch ( \Exception $e) {
            DB::rollBack();
//            throws('操作失败；信息[' . $e->getMessage() . ']');
             throws($e->getMessage());
        }
        DB::commit();
        return $id;
    }
    /**
     * 根据id新加或修改单条数据-id 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     *
     * @param array $params 参数数组
     * @param int  $company_id 企业id
     * @param int  $reType 返回类型 1 最近的一个城市[一维数组] 2 所有城市 [二维数组]
     * @param int  $formatType  所有城市 返回时，数据格式 1 直接返回 2 所有城市 [二维数组--小程序城市切换页] 4 所有城市 [二维数组--sort_num升序] 8 所有城市 [二维数组--字母升序]
     * @param int $operate_staff_id 操作人id
     * @return  array ，
     * @author zouyan(305463219@qq.com)
     */
    public static function getNearCityByLatLong($params, $company_id, $reType = 1, $formatType = 1, $operate_staff_id = 0)
    {

        if (isset($params['latitude']) && empty($params['latitude'])) {
            throws('纬度不能为空！');
        }

        if (isset($params['longitude']) && empty($params['longitude'])) {
            throws('经度不能为空！');
        }
        $latitude = $params['latitude'];
        $longitude = $params['longitude'];
        // 获得所有城市
        // 获得属性值
        $queryParams = [
            'where' => [
                 ['is_city_site', '=' , 1],
                // ['main_name', 'like', '' . $main_name . '%'],
                // ['id', '&' , '16=16'],
            ],
            'select' => [
                'id', 'parent_id', 'city_ids', 'city_name', 'code', 'head', 'initial', 'sort_num', 'longitude', 'latitude', 'is_city_site', 'city_type'
            ],
            // 'orderBy' => ['sort_num'=>'desc', 'id'=>'desc'],
        ];
        $cityListObj = static::getList($queryParams,[]);
        $cityList = $cityListObj->toArray();
        $distanceOrder = '';
        if( ($reType & 1) == 1 || ($formatType & 2) == 2) $distanceOrder = 'asc';
        Map::resolveDistance($cityList, $latitude, $longitude, 'distance', 0, $distanceOrder, 'latitude', 'longitude', '');

        if( ($reType & 1) == 1 ) return $cityList[0] ?? [];
        if( ($formatType & 4) == 4 ) {// 4 所有城市 [二维数组--sort_num升序]
            $orderDistance = [
                ['key' => 'sort_num', 'sort' => 'asc', 'type' => 'numeric'],
                ['key' => 'id', 'sort' => 'desc', 'type' => 'numeric'],
            ];
            if (!empty($cityList)) {
                $cityList = Tool::php_multisort($cityList, $orderDistance);
                $cityList = array_values($cityList);
            }
        }
        if( ($formatType & 1) == 1 || ($formatType & 4) == 4  ) return $cityList;

        if( ($formatType & 2) == 2 || ($formatType & 8) == 8){// 按字母排序
            $nearCity = [];
            if( ($formatType & 2) == 2 ) $nearCity = $cityList[0] ?? [];// 最近的城市
            $orderDistance = [
                ['key' => 'initial', 'sort' => 'asc', 'type' => 'string'],
                ['key' => 'head', 'sort' => 'asc', 'type' => 'string'],
            ];
            if(!empty($cityList)) {
                $cityList = Tool::php_multisort($cityList, $orderDistance);
                $cityList = array_values($cityList);
            }
            if( ($formatType & 8) == 8) return $cityList;

            $searchLetter = array_values(array_unique(array_column($cityList, 'initial')));
            $resultArr = [
                'nearCity' => $nearCity,
                'searchLetter' => $searchLetter,// ["A", "B", "C", "D", "E", "F", "G", "H","J", "K", "L", "M", "N", "P", "Q", "R", "S", "T", "W", "X", "Y", "Z"]
                'cityList' => [],// ['initial'=> "A" ,'cityInfo' => [ ['id' => 1, 'parent_id' => 0, 'city_name' => '北京', 'code' => '110100', 'head' => 'BJ', 'initial' => 'B', 'distance' => 943260, 'distanceStr' => '943.26km'] ]]
                'hotcityList' => [],// 热门城市 [{ cityCode: 110000, city: '北京市' }]
            ];
            // 整理城市和热门城市
            $cityLetterArr = [];
            $cityHot = [];
            foreach($cityList as $k => $v){
                $initial = $v['initial'] ?? '';
                $cityType = $v['city_type'] ?? '';// 类型0普通1热门城市
                // if(isset($v['longitude'])) unset($v['longitude']);
                // if(isset($v['latitude'])) unset($v['latitude']);
                if($cityType == 1) array_push($cityHot, $v); // 热门城市
                $cityLetterArr[$initial]['initial'] = $initial;
                $cityLetterArr[$initial]['cityInfo'][] = $v;
            }
            $resultArr['cityList'] = array_values($cityLetterArr);
            $resultArr['hotcityList'] = $cityHot;
            $cityList = $resultArr;
        }
        return $cityList;
    }


    /**
     *  人员上下班操作
     * @param int  $city_id 城市id
     * @param int  $on_line 是否上班 1下班2上班
     * @param int  $diff_amount 上下班人数
     * @return mixed 订单饱和度[订单数除以在线送餐员人数]
     * @author zouyan(305463219@qq.com)
     */
    public static function cityOnlineOperate($city_id, $on_line = 2, $diff_amount = 1){
        // 获得当前记录
        $infoObj = static::getInfo($city_id, ['id', 'staff_amount', 'order_amount', 'order_saturation'] );
        if(empty($infoObj)) return 0;
        $staff_amount = $infoObj->staff_amount;// 在线接单人数
        $order_amount = $infoObj->order_amount;// 订单数量[待接单]
        // $order_saturation = $infoObj->order_saturation;// 订单饱和度[订单数除以在线送餐员人数]
        if($on_line == 1){// 下班
            $staff_amount -= $diff_amount;
        }else{// 上班
            $staff_amount += $diff_amount;
        }
        if($staff_amount <= 0) $staff_amount = 0;

        $infoObj->staff_amount =  $staff_amount;
        if($staff_amount <= 0 || $order_amount <= 0){// 没有接单人
            $infoObj->order_saturation = 0;
        }else{
            $infoObj->order_saturation = $order_amount / $staff_amount;
        }
        $infoObj->save();

        return $infoObj->order_saturation;
    }

    /**
     *  订单数增减操作
     * @param int  $city_id 城市id
     * @param int  $operate_type 操作类型 1减2 加
     * @param int  $diff_amount 加减订单数
     * @return mixed 订单饱和度[订单数除以在线送餐员人数]
     * @author zouyan(305463219@qq.com)
     */
    public static function cityOrdersOperate($city_id, $operate_type = 2, $diff_amount = 1){
        // 获得当前记录
        $infoObj = static::getInfo($city_id, ['id', 'staff_amount', 'order_amount', 'order_saturation'] );
        if(empty($infoObj)) return 0 ;
        $staff_amount = $infoObj->staff_amount;// 在线接单人数
        $order_amount = $infoObj->order_amount;// 订单数量[待接单]
        // $order_saturation = $infoObj->order_saturation;// 订单饱和度[订单数除以在线送餐员人数]
        if($operate_type == 1){// 减
            $order_amount -= $diff_amount;
        }else{// 加
            $order_amount += $diff_amount;
        }
        if($order_amount <= 0) $order_amount = 0;

        $infoObj->order_amount =  $order_amount;
        if($staff_amount <= 0 || $order_amount <= 0){// 没有接单人
            $infoObj->order_saturation = 0;
        }else{
            $infoObj->order_saturation = $order_amount / $staff_amount;
        }
        $infoObj->save();
        return $infoObj->order_saturation;
    }

    /**
     * 跑城市店铺营业中脚本
     *
     * @param int $id
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public static function autoCityOnLine(){
        $queryParams = [
            'where' => [
                ['is_city_site', '=', 1],
            ],
            'select' => ['id'],
            //   'orderBy' => [ 'id'=>'desc'],//'sort_num'=>'desc',
        ];
        $cityList = static::getAllList($queryParams, '')->toArray();
//        Log::info('自动脚本日志---城市店铺营业中脚本--开始',[]);
        foreach($cityList as $v){
            $city_site_id = $v['id'];
//            Log::info('自动脚本日志---城市店铺营业中脚本--开始执行城市',[$city_site_id]);
            ShopOpenTimeDBBusiness::autoShopOnLine($city_site_id, true);
//            Log::info('自动脚本日志---城市店铺营业中脚本--结束执行城市',[$city_site_id]);
        }
//        Log::info('自动脚本日志---城市店铺营业中脚本--结束',[]);
    }

    /**
     * 跑城市店铺月销量最近30天脚本
     *
     * @param int $id
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public static function autoCityShopSalesVolume(){
        // $currentNow = Carbon::now();
        // $endDateTime = Carbon::now()->toDateTimeString();
        $endDateTime = date('Y-m-d');
        $beginDateTime = Tool::addMinusDate($endDateTime, ['-30 day'], 'Y-m-d', 1, '时间');
        $queryParams = [
            'where' => [
                ['is_city_site', '=', 1],
            ],
            'select' => ['id'],
            //   'orderBy' => [ 'id'=>'desc'],//'sort_num'=>'desc',
        ];
        $cityList = static::getAllList($queryParams, '')->toArray();
        Log::info('自动脚本日志---月销量最近30天脚本--开始',[]);
        foreach($cityList as $v){
            $city_site_id = $v['id'];
//            Log::info('自动脚本日志---月销量最近30天脚本--开始执行城市',[$city_site_id]);
            ShopDBBusiness::autoShopSalesVolume($beginDateTime, $endDateTime, $city_site_id, 0, '', '');// 店铺月销量
//            sleep(2);
            ShopGoodsDBBusiness::autoShopGoodsSalesVolume($beginDateTime, $endDateTime, $city_site_id);// 店铺商品月销量
//            sleep(2);
            ShopGoodsPricesDBBusiness::autoShopGoodsPriceSalesVolume($beginDateTime, $endDateTime, $city_site_id);// 跑城市店铺商品价格月销量最近30天脚本
            sleep(1);
//            Log::info('自动脚本日志---月销量最近30天脚本--结束执行城市',[$city_site_id]);
        }
        Log::info('自动脚本日志---月销量最近30天脚本--结束',[]);
    }

    /**
     * 跑城市订单过期未接单自动关闭脚本--每一分钟跑一次  ;注意这个脚本不在api跑，admin来跑，这里只为它提供数据。
     *
     * @param int $id
     * @return array 需要取消并退款的订单信息  二维数组
        [
            [
                'order_no' => $order_no, // 订单号 , 如果是订单操作必传-- order_no 或 my_order_no 之一不能为空
                'my_order_no' => $my_order_no,//付款 我方单号--与第三方对接用 -- order_no 或 my_order_no 之一不能为空
                'refund_amount' => $amount,// 需要退款的金额--0为全退---单位元
                'refund_reason' => $refund_reason,// 退款的原因--:为空，则后台自己组织内容
            ]
        ]
     * @author zouyan(305463219@qq.com)
     */
    public static function autoCityCancelOrder(){
        // $currentNow = Carbon::now();
        // $endDateTime = Carbon::now()->toDateTimeString();
        $endDateTime = date('Y-m-d H:i:s');
        $beginDateTime = Tool::addMinusDate($endDateTime, ['-24 minute'], 'Y-m-d H:i:s', 1, '时间');
        $queryParams = [
            'where' => [
                ['is_city_site', '=', 1],
            ],
            'select' => ['id'],
            //   'orderBy' => [ 'id'=>'desc'],//'sort_num'=>'desc',
        ];
        $cityList = static::getAllList($queryParams, '')->toArray();
//        Log::info('自动脚本日志---过期未接单自动关闭脚本--开始',[]);
        $resultList = [];
        foreach($cityList as $v){
            $city_site_id = $v['id'];
//            Log::info('自动脚本日志---过期未接单自动关闭脚本--开始执行城市',[$city_site_id]);
              $cancelOrderList = OrdersDBBusiness::getCancelOrderList('', $beginDateTime, $city_site_id);// 店铺月销量
            if(!empty($cancelOrderList)) $resultList = array_merge($resultList, $cancelOrderList);
//            Log::info('自动脚本日志---过期未接单自动关闭脚本--结束执行城市',[$city_site_id]);
        }
//        Log::info('自动脚本日志---过期未接单自动关闭脚本--结束',[]);
        return $resultList;
    }
}
