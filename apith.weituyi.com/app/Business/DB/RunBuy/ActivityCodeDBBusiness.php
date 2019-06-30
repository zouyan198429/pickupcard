<?php
// 人员操作记录
namespace App\Business\DB\RunBuy;

use Illuminate\Support\Facades\DB;

/**
 *
 */
class ActivityCodeDBBusiness extends BasePublicDBBusiness
{
    public static $model_name = 'RunBuy\ActivityCode';
    public static $table_name = 'activity_code';// 表名称

}
