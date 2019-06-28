<?php
// 人员操作记录
namespace App\Business\DB\RunBuy;
use App\Services\Tool;
use Carbon\Carbon;

/**
 *
 */
class StaffRecordOnlineDBBusiness extends BasePublicDBBusiness
{
    public static $model_name = 'RunBuy\StaffRecordOnline';
    public static $table_name = 'staff_record_online';// 表名称

    /**
     *  上下班操作
     *
     * @param int $staff_id 用户id
     * @param int $city_site_id 城市id
     * @param int $operate_staff_id 操作员工id
     * @param int $operate_staff_history_id 操作员工历史id
     * @param int  $on_line 是否上班 1下班2上班
     * @param string $logContent 操作说明
     * @return null
     * @author zouyan(305463219@qq.com)
     */
    public static function saveRecord($staff_id , $city_site_id, $operate_staff_id , $operate_staff_id_history, $on_line, $logContent = ''){
        // 获得最近的操作记录
        $queryParams = [
            'where' => [
                ['staff_id', $staff_id],
                // ['staff_id', $operate_staff_id],
            ],
            // 'select' => ['id', 'status', 'pay_run_price', 'has_refund', 'total_run_price' ]
            'orderBy' => ['id'=>'desc'],
        ];
        // 获得订单详情
        $infoObj = static::getInfoByQuery(1, $queryParams, []);
        // if(empty($infoObj)) throws('记录不存在 !');
        if($on_line == 1){// 下班
            if(empty($infoObj)) return ;// 没有记录
            CityDBBusiness::cityOnlineOperate($city_site_id, 1, 1);// 下班
        }else{// 上班
            // 前面没有下班，先进行下班操作
            if(!empty($infoObj) && $infoObj->on_line == 2){
                static::saveRecord($staff_id, $city_site_id, $operate_staff_id , $operate_staff_id_history, 1, '下班：上班操作前有未下班操作，系统自动下班。');
            }
            CityDBBusiness::cityOnlineOperate($city_site_id, 2, 1);// 上班
        }

        $currentNow = Carbon::now();
        // 工单操作日志
        $Record = [
            'staff_id' => $staff_id,
            'on_line' => $on_line,
            'content' => $logContent, // 操作内容
            'count_date' => $currentNow->toDateString(),
            'count_year' => $currentNow->year,
            'count_month' => $currentNow->month,
            'count_day' => $currentNow->day,
            'operate_staff_id' => $operate_staff_id,//$orderObj->operate_staff_id,
            'operate_staff_id_history' => $operate_staff_id_history,//$orderObj->operate_staff_id_history,
        ];
        static::create($Record);

    }


    /**
     *  获得已经工作时长
     *
     * @param int $staff_id 用户id
     * @return int 时间差 开始、结束日期 差--单位秒
     * @author zouyan(305463219@qq.com)
     */
    public static function getWorkTime($staff_id ){
        // 获得最近的操作记录
        $queryParams = [
            'where' => [
                ['staff_id', $staff_id],
                // ['staff_id', $operate_staff_id],
            ],
            // 'select' => ['id', 'status', 'pay_run_price', 'has_refund', 'total_run_price' ]
            'orderBy' => ['id'=>'desc'],
        ];
        // 获得订单详情
        $infoObj = static::getInfoByQuery(1, $queryParams, []);
        if(empty($infoObj)) return 0;// 没有记录
        //  下班中
        if($infoObj->on_line == 1) return 0;
        // 上班中
        return Tool::diffDate($infoObj->created_at);
    }

    /**
     *  获得正在上班的时间
     *
     * @param int $staff_id 用户id
     * @return int 0 未上班 其它:上班时间
     * @author zouyan(305463219@qq.com)
     */
    public static function getOnWorkTime($staff_id ){
        // 获得最近的操作记录
        $queryParams = [
            'where' => [
                ['staff_id', $staff_id],
                // ['staff_id', $operate_staff_id],
            ],
            // 'select' => ['id', 'status', 'pay_run_price', 'has_refund', 'total_run_price' ]
            'orderBy' => ['id'=>'desc'],
        ];
        // 获得订单详情
        $infoObj = static::getInfoByQuery(1, $queryParams, []);
        if(empty($infoObj)) return 0;// 没有记录
        //  下班中
        if($infoObj->on_line == 1) return 0;
        // 上班中
        return $infoObj->created_at;
    }
}
