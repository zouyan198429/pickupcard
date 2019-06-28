<?php
// 人员操作记录
namespace App\Business\DB\RunBuy;

/**
 *
 */
class StaffRecordDBBusiness extends BasePublicDBBusiness
{
    public static $model_name = 'RunBuy\StaffRecord';
    public static $table_name = 'staff_record';// 表名称


    /**
     * 日志
     *
     * @param int  $staff_id 员工id
     * @param int $operate_staff_id 操作员工id
     * @param int $operate_staff_history_id 操作员工历史id
     * @param string $logContent 操作说明
     * @return null
     * @author zouyan(305463219@qq.com)
     */
    public static function saveLog($staff_id , $operate_staff_id , $operate_staff_id_history, $logContent){
        // 操作日志
        $Log = [
            'staff_id' => $staff_id,
            'content' => $logContent,// "创建工单", // 操作内容
            'operate_staff_id' => $operate_staff_id,//$orderObj->operate_staff_id,
            'operate_staff_id_history' => $operate_staff_id_history,//$orderObj->operate_staff_id_history,
        ];
        static::create($Log);
    }
}
