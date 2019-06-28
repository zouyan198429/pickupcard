<?php

namespace App\Business\Controller\DB;

use App\Services\Request\Data\CommonAPIFromDBBusiness;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;

class BasicCTDBBusiness
{
    public static $model_name = '';// 中间层 App\Business\DB 下面的表名称 RunBuy\CountSenderReg

    // 实例化数据中间层对象
    public static function requestGetObj(Request $request, Controller $controller, &$modelObj = null){
        if (! is_object($modelObj)) {
//            $modelName = CommonRequest::get($request, 'Model_name');
//            Tool::judgeEmptyParams('Model_name', $modelName);
            $modelName = static::$model_name;

//            $className = "App\\Business\\DB\\RunBuy\\LrChinaCityDBBusiness" ;
//            if (! class_exists($className )) {
//                throws('参数[Model_name]不正确！');
//            }
//            $modelObj = new $className();
            CommonAPIFromDBBusiness::getBusinessDBObjByModelName($modelName, $modelObj );
        }
        return $modelObj;
    }

}