<?php
// 人员操作记录
namespace App\Business\DB\RunBuy;

use App\Services\Tool;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class ActivityDBBusiness extends BasePublicDBBusiness
{
    public static $model_name = 'RunBuy\Activity';
    public static $table_name = 'activity';// 表名称

    /**
     * 根据id新加或修改单条数据-id 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     *
     * @param array $saveData 要保存或修改的数组  必要参数 ower_type , ower_id
     * @param int  $company_id 企业id
     * @param int $id id
     * @param int $operate_staff_id 操作人id
     * @param int $modifAddOprate 修改时是否加操作人，1:加;0:不加[默认]
     * @return  int 记录id值，
     * @author zouyan(305463219@qq.com)
     */
    public static function replaceById($saveData, $company_id, &$id, $operate_staff_id = 0, $modifAddOprate = 0){

        if(isset($saveData['product_id']) && empty($saveData['product_id'])  ){
            throws('所属商品不能为空！');
        }

        if(isset($saveData['activity_name']) && empty($saveData['activity_name'])  ){
            throws('活动标题不能为空！');
        }

        if(isset($saveData['begin_time']) && empty($saveData['begin_time'])  ){
            throws('开始日期不能为空！');
        }

        if(isset($saveData['end_time']) && empty($saveData['end_time'])  ){
            throws('结束日期不能为空！');
        }

        if(isset($saveData['begin_num']) && empty($saveData['begin_num'])  ){
            throws('起始编号不能为空！');
        }

        if(isset($saveData['total_num']) && empty($saveData['total_num'])  ){
            throws('编号数量不能为空！');
        }


        // 判断时间
        if(isset($saveData['begin_time'])  ){
            // 判断开始结束日期[ 可为空,有值的话-；4 开始日期 不能大于 >  当前日；32 结束日期 不能大于 >  当前日;256 开始日期 不能大于 >  结束日期]
            Tool::judgeBeginEndDate($saveData['begin_time'], '', 1 );

        }

        if(isset($saveData['end_time'])  ){
            // 判断开始结束日期[ 可为空,有值的话-；4 开始日期 不能大于 >  当前日；32 结束日期 不能大于 >  当前日;256 开始日期 不能大于 >  结束日期]
            Tool::judgeBeginEndDate('', $saveData['end_time'], 2);

        }

        if(isset($saveData['begin_time']) && isset($saveData['end_time']) ){
            // 判断开始结束日期[ 可为空,有值的话-；4 开始日期 不能大于 >  当前日；32 结束日期 不能大于 >  当前日;256 开始日期 不能大于 >  结束日期]
            Tool::judgeBeginEndDate($saveData['begin_time'], $saveData['end_time'], 1 + 2 + 256);

        }

        if(isset($saveData['begin_num']) && ($saveData['begin_num'] <= 0 || $saveData['begin_num'] > 99999) ){
            throws('起始编号必须>0且<=99999！');
        }

        if(isset($saveData['total_num']) && ($saveData['total_num'] <= 0 || $saveData['total_num'] > 99999) ){
            throws('编号数量必须>0且<=99999！');
        }
        // 判断商品是否存在
        $pre_code = '';// 编码前缀
        if( isset($saveData['product_id']) && $saveData['product_id'] > 0 ){
            $productInfo = ProductDBBusiness::getInfo($saveData['product_id'], ['id', 'pre_code']);
            if(empty($productInfo)) throws('商品记录不存在');
            $pre_code = $productInfo['pre_code'] ?? '';
        }
        $product_id = $saveData['product_id'] ?? 0;
        $begin_num = $saveData['begin_num'] ?? 1;
        $total_num = $saveData['total_num'] ?? 1;

        $recreateCode = true;// 是否需要重新生成兑换码及密码

        // 获得当前记录，判断是否可以进行修改操作
        if($id > 0){
            $activityInfo = static::getInfo($id, ['status', 'begin_num', 'total_num']);
            if(empty($activityInfo)) throws('活动记录不存在');
            if($activityInfo['status'] != 1) throws('活动状态非未开始状态，不可修改');// 状态1未开始2进行中4已结束
            if( $begin_num == $activityInfo['begin_num'] && $total_num == $activityInfo['total_num'] )  $recreateCode = false;
        }
        // 生成兑换码
        $codeListArr = [];
        $strLen = 5;// 兑换码长度
        if($recreateCode){
            for($k = 0; $k < $total_num; $k++){
                // 生成兑换码
                $recordNum = $begin_num + $k;
                $temCode =  $pre_code . str_pad(substr($recordNum, -$strLen), $strLen, 0, STR_PAD_LEFT);;
                // 生成密码
                $temPassword = Tool::createRandChars([
                    [
                        'type' => 1 + 2,// 字符串类型 可以加起来  1小写字母 ;2大写字母;4数字;8自定义字符串
                        'length' => 2,// 字符串长度
                        'repeated' => true,// 是否可重复 true：可重复 ; false：不可重复
                        'charsRemove' => 'oO',// 需要排除/移除的字符
                        'charsSelf' => '',// 字定义字符串[type=8时：加入字符]
                    ],
                    [
                        'type' => 4,// 字符串类型 可以加起来  1小写字母 ;2大写字母;4数字;8自定义字符串
                        'length' => 4,// 字符串长度
                        'repeated' => true,// 是否可重复 true：可重复 ; false：不可重复
                        'charsRemove' => '',// 需要排除/移除的字符
                        'charsSelf' => '',// 字定义字符串[type=8时：加入字符]
                    ],
                ]);
                array_push($codeListArr, [
                    // 'id' => 0,
                    'code' => $temCode,
                    'code_password' => $temPassword,
                ]);
            }
        }


        DB::beginTransaction();
        try {
            $isModify = false;
            $operate_staff_id_history = 0;
            $product_id_history = 0;
            // 如果有商品，则更新商品历史
            if( isset($saveData['product_id']) && $saveData['product_id'] > 0 ){
                $product_id_history = ProductDBBusiness::getIdHistory($saveData['product_id']);
                 $saveData['product_id_history'] = $product_id_history;
            }

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

            // 当前活动已有的兑换码
            $codeList = [];
            $hasCodeTableIds =  [];// 已有的兑换码id数组-一维数组
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


                // 获得当前已有的兑换码
                if($recreateCode){
                    $queryParams = [
                        'where' => [
                            ['activity_id', $id],
                        ],
                        'select' => [
                            'id'
//                        'id','title','sort_num','volume'
//                        ,'operate_staff_id','operate_staff_id_history'
//                        ,'created_at' ,'updated_at'
                        ],
                        //   'orderBy' => [ 'id'=>'desc'],//'sort_num'=>'desc',
                    ];
                    $codeList = ActivityCodeDBBusiness::getAllList($queryParams, '')->toArray();
                    $hasCodeTableIds = array_column($codeList,'id');
                }

            }
            if($recreateCode){
                Tool::arrAppendKeys($codeListArr, ['product_id' => $product_id, 'product_id_history' => $product_id_history
                    , 'activity_id' => $id, 'status' => 1
                    , 'operate_staff_id' => $operate_staff_id, 'operate_staff_id_history' => $operate_staff_id_history]);
//            if($isModify){
//                static::compareHistory($id, 1);
//            }
                $codeModifyList = [];
                // 遍历兑换码
                foreach($codeListArr as $k => $v){
                    if( !isset($hasCodeTableIds[$k]) ) break;
                    $v['id'] = $hasCodeTableIds[$k];
                    array_push($codeModifyList, $v);
                    unset($hasCodeTableIds[$k]);
                    unset($codeListArr[$k]);
                }
                $hasCodeTableIds = array_values($hasCodeTableIds);
                $codeListArr = array_values($codeListArr);
                // 批量新加总换码
                if( !empty($codeListArr) ) ActivityCodeDBBusiness::addBath($codeListArr);
                // 修改兑换码
                if( !empty($codeModifyList)) ActivityCodeDBBusiness::saveBathById($codeModifyList, 'id');
                // 删除多除的兑换码
                if( !empty($hasCodeTableIds)) ActivityCodeDBBusiness::deleteByIds($hasCodeTableIds);
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
     * 根据id删除
     *
     * @param int  $company_id 企业id
     * @param int $id id
     * @param int $operate_staff_id 操作人id
     * @param int $modifAddOprate 修改时是否加操作人，1:加;0:不加[默认]
     * @return  int 记录id值
     * @author zouyan(305463219@qq.com)
     */
    public static function delById($company_id, $id, $operate_staff_id = 0, $modifAddOprate = 0){

        if(strlen($id) <= 0){
            throws('操作记录标识不能为空！');
        }
        $activityInfo = static::getInfo($id, ['status']);
        if(empty($activityInfo)) throws('活动记录不存在');
        if($activityInfo['status'] != 1) throws('活动状态非未开始状态，不可修改');// 状态1未开始2进行中4已结束
        DB::beginTransaction();
        try {
            // 删除 兑换码
            $queryParams = [
                'where' => [
                    //  ['id', '&' , '16=16'],
                    ['activity_id', $id],
                    //['mobile', $keyword],
                    //['admin_type',self::$admin_type],
                ],
                //                    'whereIn' => [
                //                        'id' => $cityPids,
                //                    ],
                //            'select' => [
                //                'id','company_id','type_name','sort_num'
                //                //,'operate_staff_id','operate_staff_id_history'
                //                ,'created_at'
                //            ],
                // 'orderBy' => ['id'=>'desc'],
            ];
            ActivityCodeDBBusiness::del($queryParams);
            // 删除活动
            static::deleteByIds($id);
        } catch ( \Exception $e) {
            DB::rollBack();
            throws($e->getMessage());
            // throws($e->getMessage());
        }
        DB::commit();
        return $id;
    }

    /**
     * 跑提货活动开始及过期脚本
     *
     * @param int $id
     * @param int $db_force 是否强制从数据库获得营业中店铺 true:强制;false：从redis读
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public static function autoChangeStatus(){
        // 状态1未开始2进行中4已结束

        // 1未开始=>2进行中

        $nowDate =  date('Y-m-d');// 当前日期
        $queryParams = [
            'where' => [
                ['begin_time', '<=', $nowDate],
                ['end_time', '>=', $nowDate],
                ['status', '=', 1],
            ],
            'select' => ['id'],
            //   'orderBy' => [ 'id'=>'desc'],//'sort_num'=>'desc',
        ];

        $dataList = static::getAllList($queryParams, '')->toArray();
        $doingIds = array_column($dataList, 'id');

        // 2进行中=>4已结束

        $queryParams = [
            'where' => [
                // ['begin_time', '<=', $nowDate],
                ['end_time', '<', $nowDate],
                ['status', '=', 2],
            ],
            'select' => ['id'],
            //   'orderBy' => [ 'id'=>'desc'],//'sort_num'=>'desc',
        ];

        $temDataList = static::getAllList($queryParams, '')->toArray();
        $overIds = array_column($temDataList, 'id');

        // 1未开始=>4已结束

        $queryParams = [
            'where' => [
                // ['begin_time', '<=', $nowDate],
                ['end_time', '<', $nowDate],
                ['status', '=', 1],
            ],
            'select' => ['id'],
            //   'orderBy' => [ 'id'=>'desc'],//'sort_num'=>'desc',
        ];

        $temDataList = static::getAllList($queryParams, '')->toArray();
        $temOverIds = array_column($temDataList, 'id');
        if(!empty($temOverIds)) $overIds = array_merge($overIds, $temOverIds);
        $temOverIds = array_values($temOverIds);
        $overIds = array_values($overIds);
        // 都为空，则不执行
        if(empty($doingIds) && empty($overIds)) return true;


        DB::beginTransaction();
        try {
            // 改为进行中的
            if(!empty($doingIds)){
                $saveQueryParams = [
//                    'where' => [
//                        // ['order_type', 4],
//                        // ['staff_id', $operate_staff_id],
////                        ['status', 1],
////                        ['status_business', 2],
//                        // ['status_business', '!=', 1],
//                    ],
                    /*
                     *
                    'select' => [
                        'id','title','sort_num','volume'
                    ],
                     *
                     */
                    //   'orderBy' => [ 'id'=>'desc'],//'sort_num'=>'desc',
                ];
                $saveQueryParams['whereIn']['id'] = $doingIds;
                $saveDate = [
                    'status' => 2,
                ];
                static::save($saveDate, $saveQueryParams);
            }

            // 过期
            if(!empty($overIds)){
                $saveQueryParams = [
//                    'where' => [
//                        // ['order_type', 4],
//                        // ['staff_id', $operate_staff_id],
//                        // ['status', 1],
////                        ['status_business', 1],
//                        // ['status_business', '!=', 1],
//                    ],
                    /*
                     *
                    'select' => [
                        'id','title','sort_num','volume'
                    ],
                     *
                     */
                    //   'orderBy' => [ 'id'=>'desc'],//'sort_num'=>'desc',
                ];
                $saveQueryParams['whereIn']['id'] = $overIds;
                $saveDate = [
                    'status' => 4,
                ];
                static::save($saveDate, $saveQueryParams);

                $saveQueryParams = [
                    'where' => [
                        // ['order_type', 4],
                        // ['staff_id', $operate_staff_id],
                         ['status', 1],
//                        ['status_business', 1],
                        // ['status_business', '!=', 1],
                    ],
                    /*
                     *
                    'select' => [
                        'id','title','sort_num','volume'
                    ],
                     *
                     */
                    //   'orderBy' => [ 'id'=>'desc'],//'sort_num'=>'desc',
                ];
                $saveQueryParams['whereIn']['activity_id'] = $overIds;
                ActivityCodeDBBusiness::save(['status' => 4], $saveQueryParams);
            }
        } catch ( \Exception $e) {
            DB::rollBack();
            throws('活动状态改为进行中或过期操作失败；信息[' . $e->getMessage() . ']');
            // throws($e->getMessage());
        }
        DB::commit();
    }
}
