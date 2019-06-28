<?php
//
namespace App\Services\Response\Data;

use App\Services\Request\CommonRequest;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;
class CommonAPIFromBusiness
{

    // 根据数据模型名称，返回数据中间层对象
    // $modelName Business\Controller 目录下 [Controller|API|Block]\[API|Block|DB]\CTDBCity  [Business]  部分
    public static function getBusinessObjByModelName($modelName, &$modelObj = null){
        $className = "App\\Business\\" . $modelName . 'Business';
        if (! class_exists($className )) {
            throws('参数[Model_name]不正确！');
        }
        $modelObj = new $className();
        return $modelObj;
    }

    // 实例化数据中间层对象
    public static function requestGetObj(Request $request, &$modelObj = null){
        if (! is_object($modelObj)) {
            $modelName = CommonRequest::get($request, 'Model_name');
            Tool::judgeEmptyParams('Model_name', $modelName);

//            $className = "App\\Business\\DB\\RunBuy\\LrChinaCityDBBusiness" ;
//            if (! class_exists($className )) {
//                throws('参数[Model_name]不正确！');
//            }
//            $modelObj = new $className();
            self::getBusinessObjByModelName($modelName, $modelObj );
        }
        return $modelObj;
    }

    /**
     * 调用模型方法
     *  模型中方法定义:注意参数尽可能给默认值
        public function aaa($aa = [], $bb = []){
            echo $this->getTable() . '<BR/>';
            print_r($aa);
            echo  '<BR/>';
            print_r($bb);
            echo  '<BR/>';
            echo 'aaaaafunction';
        }
     * @param object $modelObj 对象
     * @param string $methodName 方法名称
     * @param array $params 参数数组 没有参数:[]  或 [第一个参数, 第二个参数,....];
     * @return mixed 返回值
     * @author zouyan(305463219@qq.com)
     */
    public static function exeMethod(Request $request,Controller $Controller,&$modelObj, $methodName, $params = []){
        if(!method_exists($modelObj,$methodName)){
            throws("未定义[" . $methodName  . "] 方法");
        }
        $params = array_values($params);
        return $modelObj->{$methodName}($request, $Controller, ...$params);
    }

    // 实例化数据中间层 ，获得中间层属性
    //  @param string 必填 $Model_name model名称 或传入 $modelObj 对象
    public static function requestGetBusinessAttr(Request $request, &$modelObj = null){

        $attrName = CommonRequest::get($request, 'attrName');
        Tool::judgeEmptyParams('attrName', $attrName);

        $isStatic = CommonRequest::getInt($request, 'isStatic');

        // 获得对象
        static::requestGetObj($request,$modelObj);

        $attrVal = Tool::getAttr($modelObj, $attrName, $isStatic);
        return  $attrVal;
    }

    // 实例化数据中间层 ，执行中间层方法
    //  @param string 必填 $Model_name model名称 或传入 $modelObj 对象
    public static function requestExeBusinessMethod(Request $request,Controller $Controller, &$modelObj = null){

        $methodName = CommonRequest::get($request, 'methodName');
        Tool::judgeEmptyParams('methodName', $methodName);

        $params = CommonRequest::get($request, 'params');
        Tool::judgeEmptyParams('params', $params);
        // json 转成数组
        if (!empty($params))  jsonStrToArr($params , 1, '参数[params]格式有误!');
        if (!is_array($params)) $params =[];

        // 获得对象
        static::requestGetObj($request,$modelObj);

        $result = self::exeMethod($request, $Controller,$modelObj, $methodName, $params);
        return  $result;
    }
}