<?php
// 人员操作记录
namespace App\Business\DB\RunBuy;

use App\Services\Tool;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class ActivityCodeDBBusiness extends BasePublicDBBusiness
{
    public static $model_name = 'RunBuy\ActivityCode';
    public static $table_name = 'activity_code';// 表名称

    /**
     * 根据id开启单条或多条数据
     *
     * @param int  $company_id 企业id
     * @param int  $activity_id 企业id
     * @param string/array $id id 数组或字符串
     * @param int $open_status 操作 状态 1待启用 -- 关闭     2已启用  --- 开启
     * @param int $operate_staff_id 操作人id
     * @param int $modifAddOprate 修改时是否加操作人，1:加;0:不加[默认]
     * @return  array 记录id值，--一维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function openStatusById($company_id, $activity_id = 0, $id = 0, $open_status = 1, $operate_staff_id = 0, $modifAddOprate = 0){

        if(is_string($id)) $id = explode(',', $id);// 是字符，则转为数组
        $id = array_values(array_unique($id));
        $retIds = $id;
        // 一维数组，则转为二维数组
        // Tool::isMultiArr($id, true);
        $operateData = [];
        foreach($id as $primary_id){
            array_push($operateData, ['id' => $primary_id]);
        }
        $saveData = [
            'activity_id' => $activity_id,
            'open_status' => $open_status
        ];
        DB::beginTransaction();
        try {
            if($modifAddOprate) static::addOprate($saveData, $operate_staff_id);

            Tool::arrAppendKeys($operateData, $saveData);
            ActivityCodeDBBusiness::saveBathById($operateData, 'id');
        } catch ( \Exception $e) {
            DB::rollBack();
//            throws('操作失败；信息[' . $e->getMessage() . ']');
            throws($e->getMessage());
        }
        DB::commit();
        return $retIds;
    }


    /**
     * 根据id开启单条或多条数据
     *
     * @param int  $company_id 企业id
     * @param int  $activity_id 企业id
     * @param int $open_status 操作 状态 1待启用 -- 关闭     2已启用  --- 开启
     * @param int $operate_staff_id 操作人id
     * @param int $modifAddOprate 修改时是否加操作人，1:加;0:不加[默认]
     * @return  array 记录id值，--一维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function openStatusAll($company_id, $activity_id = 0, $open_status = 1, $operate_staff_id = 0, $modifAddOprate = 0){
        $result = 0;
        $saveData = [
            'activity_id' => $activity_id,
            'open_status' => $open_status
        ];
        DB::beginTransaction();
        try {
            if($modifAddOprate) static::addOprate($saveData, $operate_staff_id);

            $saveQueryParams = [
                    'where' => [
                         ['activity_id', $activity_id],
                        // ['staff_id', $operate_staff_id],
                        // ['status', 1],
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
            $result = static::save($saveData, $saveQueryParams);
        } catch ( \Exception $e) {
            DB::rollBack();
//            throws('操作失败；信息[' . $e->getMessage() . ']');
            throws($e->getMessage());
        }
        DB::commit();
        return $result;

    }
}
