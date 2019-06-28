<?php
// api通用请求数据类型，实例化数据中间层，调用中间层的方法来获得数据
namespace App\Services\Request\Data;

use App\Services\Request\CommonRequest;
use App\Services\Tool;
use Illuminate\Http\Request;

class CommonAPIFromDBBusiness extends CommonAPIFormModel
{
    // 数据来源类型
    // 1、实例化数据模型，直接通过数据模型，调用数据模型的方法。
    // 2、实例化数据中间层，调用中间层的方法来获得数据
    protected static $dataFromType = 2;

    // 根据数据模型名称，返回数据中间层对象
    public static function getBusinessDBObjByModelName($modelName, &$modelObj = null){
        $className = "App\\Business\\DB\\" . $modelName . 'DBBusiness';
        if (! class_exists($className )) {
            throws('参数[Model_name]不正确！');
        }
        $modelObj = new $className();
        return $modelObj;
    }

    // 实例化数据中间层对象
    public static function requestGetObj(Request $request,&$modelObj = null){
        if (! is_object($modelObj)) {
            $modelName = CommonRequest::get($request, 'Model_name');
            Tool::judgeEmptyParams('Model_name', $modelName);

//            $className = "App\\Business\\DB\\RunBuy\\LrChinaCityDBBusiness" ;
//            if (! class_exists($className )) {
//                throws('参数[Model_name]不正确！');
//            }
//            $modelObj = new $className();
            self::getBusinessDBObjByModelName($modelName, $modelObj );
        }
        return $modelObj;
    }

    /**
     * 获得属性
     *
     * @param object $modelObj 对象
     * @param string $attrName 属性名称[静态或动态]--注意静态时不要加$ ,与动态属性一样 如 attrTest
     * @param int $isStatic 是否静态属性 0：动态属性 1 静态属性
     * @return string 属性值
     * @author zouyan(305463219@qq.com)
     */
//    public static function getAttr(&$modelObj, $attrName, $isStatic = 0){
//        if ( !property_exists($modelObj, $attrName)) {
//            throws("未定义[" . $attrName  . "] 属性");
//        }
//        // 静态
//        if($isStatic == 1) return $modelObj::${$attrName};
//        return $modelObj->{$attrName};
//    }

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
//    public static function exeMethod(&$modelObj, $methodName, $params = []){
//        if(!method_exists($modelObj,$methodName)){
//            throws("未定义[" . $methodName  . "] 方法");
//        }
//        return $modelObj->{$methodName}(...$params);
//    }

    // 实例化数据中间层 ，获得中间层属性
    //  @param string 必填 $Model_name model名称 或传入 $modelObj 对象
    public static function requestGetBusinessDBAttr(Request $request, &$modelObj = null){

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
    public static function requestExeBusinessDBMethod(Request $request, &$modelObj = null){

        $methodName = CommonRequest::get($request, 'methodName');
        Tool::judgeEmptyParams('methodName', $methodName);

        $params = CommonRequest::get($request, 'params');
        if(!empty($params)){
            Tool::judgeEmptyParams('params', $params);
            // json 转成数组
            if (!empty($params))  jsonStrToArr($params , 1, '参数[params]格式有误!');
        }
        if (!is_array($params)) $params =[];

        // 获得对象
        static::requestGetObj($request,$modelObj);

        $result = Tool::exeMethod($modelObj, $methodName, $params);
        return  $result;
    }
}