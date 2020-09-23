<?php

namespace App\Business\Controller\API;

use App\Services\Response\Data\CommonAPIFromBusiness;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as Controller;

class BasicCTAPIBusiness extends APIOperate
{
    public static $model_name = '';// 中间层 App\Business\API 下面的表名称 API\RunBuy\CountSenderRegAPI


    /**
     * $model_name 转换为其它格式  API\QualityControl\CTAPIStaffBusiness =》 QualityControl\{CTAPI}Staff
     *  如调用：CTAPIStaffBusiness::modelNameFormat($request, $this);
     * @param string $preStr 表名前面加的关键字
     * @return string
     * @author zouyan(305463219@qq.com)
     */
    public static function modelNameFormat(Request $request, Controller $controller, $preStr = 'CTAPI'){
        // API\QualityControl\StaffAPI
        // API\QualityControl\CTAPIStaffBusiness
        $model_name = static::$model_name;
        $needArr = [];
        $temArr = explode('\\', $model_name);
        $arrCount = count($temArr);
        foreach($temArr as $k => $v){
            if($k <= 0 ) continue;
            // 最后一个
            if($k == $arrCount -1){
                // 去掉最后的API
                if(substr($v,-3) == 'API'){
                    $v = $preStr . substr($v,0,-3);
                }
            }
            array_push($needArr, $v);
        }
        $needStr = implode('\\', $needArr);// QualityControl\CTAPIStaff
        return $needStr;
    }

    /**
     * 修改 Request的值
     *
     * @param array $params 需要修改的键值数组 ['foo' => 'bar', ....]
     * @return null
     * @author zouyan(305463219@qq.com)
     */
    public static function mergeRequest(Request $request, Controller $controller, $params = [])
    {
        // 合并输入，如果有相同的key，用户输入的值会被替换掉，否则追加到 input
         $request->merge($params);

        // 替换所有输入
        // $request->replace($params);
    }

    /**
     * 删除 Request的值
     *
     * @param array $params 需要修改的键值数组 ['foo', 'bar', ....]
     * @return null
     * @author zouyan(305463219@qq.com)
     */
    public static function removeRequest(Request $request, Controller $controller, $params = [])
    {
        foreach($params as $key){
            unset($request[$key]);
        }
    }
}
