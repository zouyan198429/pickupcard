<?php
// 城市信息
namespace App\Business\DB\RunBuy;

/**
 *
 */
class LrChinaCityDBBusiness extends BasePublicDBBusiness
{
    public static $model_name = 'RunBuy\LrChinaCity';
    public static $table_name = 'lr_china_city';// 表名称

    public static $attrTest = '中间层属性';

    public  $attrTestaa = '中间层属性aaa';

    public static function testMethod($aaa  = '', $bbb = ''){
        return '中间层方法:参数--' . $aaa . '----' . $bbb;
    }
}
