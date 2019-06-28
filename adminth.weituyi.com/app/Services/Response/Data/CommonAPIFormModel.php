<?php
// api通用请求数据类型，直接通过数据模型获得数据
namespace App\Services\Request\Data;
use App\Services\DB\CommonDB;
use App\Services\Request\CommonRequest;
use App\Services\Tool;
use Illuminate\Http\Request;

class CommonAPIFormModel
{
    // 数据来源类型
    // 1、实例化数据模型，直接通过数据模型，调用数据模型的方法。
    // 2、实例化数据中间层，调用中间层的方法来获得数据
    protected static $dataFromType = 1;

    // 实例化数据模型对象
    public static function requestGetObj(Request $request,&$modelObj = null){
        if (! is_object($modelObj)) {
            $modelName = CommonRequest::get($request, 'Model_name');
            Tool::judgeEmptyParams('Model_name', $modelName);

//            $className = "App\\Models\\" .$modelName;
//            if (! class_exists($className )) {
//                throws('参数[Model_name]不正确！');
//            }
//            $modelObj = new $className();
            CommonDB::getObjByModelName($modelName, $modelObj );
        }
        return $modelObj;
    }

    /**
     * 获得所有列表接口
     * 注意参数是request来的参数
     * @param string 必填 $Model_name model名称
     * @param string 选填 $queryParams 条件数组/json字符
     * @param string 选填 $relations 关系数组/json字符
     * @return  object
     * @author zouyan(305463219@qq.com)
     */
    public static function requestAllDataByModel(Request $request, &$modelObj = null)
    {
        // 查询条件参数 及 查询关系参数
        list($queryParams,$relations) = array_values(CommonRequest::getQueryRelations($request));

        // 获得对象
        static::requestGetObj($request,$modelObj);

        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
            $requestData = CommonDB::getAllModelDatas($modelObj, $queryParams, $relations);
        }else{// 调用中间层的方法来获得数据
            $requestData = $modelObj->getAllList($queryParams, $relations);
        }
        return $requestData;
    }


    /**
     * 获得列表接口--根据条件
     * 注意参数是request来的参数
     * @param string 必填 $Model_name model名称
     * @param string 选填 $queryParams 条件数组/json字符
     * @param string 选填 $relations 关系数组/json字符
     * @return object
     * @author zouyan(305463219@qq.com)
     */
    public static function requestDataByQuery(Request $request, &$modelObj = null)
    {
        // 查询条件参数 及 查询关系参数
        list($queryParams,$relations) = array_values(CommonRequest::getQueryRelations($request));

        // 获得对象
        static::requestGetObj($request,$modelObj);

        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
            $requestData = CommonDB::getList($modelObj, $queryParams, $relations);
        }else{// 调用中间层的方法来获得数据
            $requestData = $modelObj->getList($queryParams, $relations);
        }

        return $requestData;
    }

    /**
     * 获得列表接口
     * 注意参数是request来的参数
     * @param string 必填 $Model_name model名称 或传入 $modelObj 对象
     * @param string 选填 $queryParams 条件数组/json字符
     * @param string 选填 $relations 关系数组/json字符
     * @param int 选填 $page 当前页page [默认1]
     * @param int 选填 $pagesize 每页显示的数量 [默认15]
     * @param int 选填 $total 总记录数,优化方案：传<=0传重新获取总数[默认0]
     * @return array
     * @author zouyan(305463219@qq.com)
     */
    public static function requestListDataByModel(Request $request, &$modelObj = null)
    {
        // 获得翻页的三个关键参数
        list($page, $pagesize, $total) = array_values(CommonRequest::getPageParams($request));

        // 查询条件参数 及 查询关系参数
        list($queryParams,$relations) = array_values(CommonRequest::getQueryRelations($request) );

        // 获得对象
        static::requestGetObj($request,$modelObj);

        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
            $requestData = CommonDB::getModelListDatas($modelObj, $page, $pagesize, $total, $queryParams, $relations);
        }else{// 调用中间层的方法来获得数据
            $requestData = $modelObj->getDataLimit($page, $pagesize, $total, $queryParams, $relations);
        }
        return $requestData;
    }

    public static function requestInfoByID(Request $request, &$modelObj = null){

        $id = CommonRequest::getInt($request, 'id');
        if ($id <=0){
            throws('参数[id]格式不正确！');
        }


        // 查询字段参数--一维数组
        $selectParams = CommonRequest::get($request, 'selectParams');
        if(empty($selectParams)) $selectParams = [];
        // json 转成数组
        jsonStrToArr($selectParams , 1, '参数[$selectParams]格式有误!');

        // 查询关系参数
        $relations = CommonRequest::get($request, 'relations');

        // 获得对象
        static::requestGetObj($request,$modelObj);

        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
            $requestData = CommonDB::getInfoById($modelObj, $id, $selectParams, $relations);
        }else{// 调用中间层的方法来获得数据
            $requestData = $modelObj->getInfo($id, $selectParams, $relations);
        }
        return  $requestData;
    }

    // pagesize 1:返回一维数组,>1 返回二维数组
    public static function requestInfoByQuery(Request $request, &$modelObj = null){
        // 每页显示的数量,取值1 -- 100 条之间,默认20条
        $pagesize = CommonRequest::getInt($request, 'pagesize');
        //if ( (! is_numeric($pagesize)) || $pagesize <= 0 || $pagesize > 100 ){ $pagesize = 15; }
        if ( (! is_numeric($pagesize)) || $pagesize <= 0 || $pagesize > 10000 ){ $pagesize = 1; }

        // 条件数组
        $queryParams = CommonRequest::get($request, 'queryParams');

        // json 转成数组
        jsonStrToArr($queryParams , 1, '参数[queryParams]格式有误!');

        // 查询关系参数
        $relations = CommonRequest::get($request, 'relations');

        // 获得对象
        static::requestGetObj($request,$modelObj);

        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
            $requestData = CommonDB::getInfoByQuery($modelObj, $pagesize, $queryParams, $relations);
        }else{// 调用中间层的方法来获得数据
            $requestData = $modelObj->getInfoByQuery($pagesize, $queryParams, $relations);
        }
        return  $requestData;
    }

    public static function requestDel(Request $request, &$modelObj = null){
        // 条件数组
        $queryParams = CommonRequest::get($request, 'queryParams');

        // json 转成数组
        jsonStrToArr($queryParams , 1, '参数[queryParams]格式有误!');

        // 获得对象
        static::requestGetObj($request,$modelObj);

        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
            // $requestData =$modelObj->where($queryParams)->delete();
            $requestData = CommonDB::del($modelObj, $queryParams);
        }else{// 调用中间层的方法来获得数据
            $requestData = $modelObj->del($queryParams);
        }
        return $requestData;
    }

    public static function requestSync(Request $request, &$modelObj = null){
        $id = CommonRequest::getInt($request, 'id');
        if ($id <=0){
            throws('参数[id]格式不正确！');
        }

        // 查询关系同步参数
        $synces = CommonRequest::get($request, 'synces');
        // json 转成数组
        jsonStrToArr($synces , 1, '参数[synces]格式有误!');

        // 获得对象
        static::requestGetObj($request,$modelObj);

        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
            $successRels = CommonDB::sync($modelObj, $id, $synces);
        }else{// 调用中间层的方法来获得数据
            $successRels = $modelObj->sync($id, $synces);
        }

        return  $successRels;
    }

    public static function requestDetach(Request $request, &$modelObj = null){
        $id = CommonRequest::getInt($request, 'id');
        if ($id <=0){
            throws('参数[id]格式不正确！');
        }

        // 查询关系同步参数
        $detaches = CommonRequest::get($request, 'detaches');
        // json 转成数组
        jsonStrToArr($detaches , 1, '参数[detaches]格式有误!');

        // 获得对象
        static::requestGetObj($request,$modelObj);

        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
            $successRels = CommonDB::detach($modelObj, $id, $detaches);
        }else{// 调用中间层的方法来获得数据
            $successRels = $modelObj->detach($id, $detaches);
        }
        return  $successRels;
    }


    //新加
    public static function requestCreate(Request $request, &$modelObj = null)
    {
        // 字段数组
        $dataParams = CommonRequest::get($request, 'dataParams');

        // json 转成数组
        jsonStrToArr($dataParams , 1, '参数[dataParams]格式有误!');

        // 获得对象
        static::requestGetObj($request,$modelObj);

        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
            // $requestData = $modelObj->create($dataParams);
            $requestData = CommonDB::create($modelObj, $dataParams);
        }else{// 调用中间层的方法来获得数据
            $requestData = $modelObj->create($dataParams);
        }
        return $requestData;
    }

    //批量新加-data只能返回成功true:失败:false
    public static function requestCreateBath(Request $request, &$modelObj = null)
    {
        // 字段数组
        $dataParams = CommonRequest::get($request, 'dataParams');

        // json 转成数组
        jsonStrToArr($dataParams , 1, '参数[dataParams]格式有误!');

        // 获得对象
        static::requestGetObj($request,$modelObj);

        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
            $requestData = CommonDB::insertData($modelObj, $dataParams);
            // $requestData =$modelObj->insert($dataParams);//一维或二维数组;只返回true:成功;false：失败
            // $requestData =$modelObj->insertGetId($dataParams,'id');//只能是一维数组，返回id值
        }else{// 调用中间层的方法来获得数据
            $requestData = $modelObj->addBath($dataParams);
        }
        return $requestData;
    }

    //批量新加-data只能返回成功true:失败:false
    public static function requestCreateBathByPrimaryKey(Request $request, &$modelObj = null)
    {
        // 字段数组
        $dataParams = CommonRequest::get($request, 'dataParams');

        // json 转成数组
        jsonStrToArr($dataParams , 1, '参数[dataParams]格式有误!');

        $primaryKey = CommonRequest::get($request, 'primaryKey');

        // 获得对象
        static::requestGetObj($request,$modelObj);

        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
            $newIds = CommonDB::insertGetId($modelObj, $dataParams, $primaryKey);
        }else{// 调用中间层的方法来获得数据
            $newIds = $modelObj->addBathByPrimaryKey($dataParams, $primaryKey);
        }
        return $newIds;
    }


    // 通过id修改记录
    public static function requestSave(Request $request, &$modelObj = null){
        $id = CommonRequest::getInt($request, 'id');
        if ($id <=0){
            throws('参数[id]格式不正确！');
        }

        // 字段数组
        $dataParams = CommonRequest::get($request, 'dataParams');
        // json 转成数组
        jsonStrToArr($dataParams , 1, '参数[dataParams]格式有误!');

        // 获得对象
        static::requestGetObj($request,$modelObj);

        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
            $result = CommonDB::saveById($modelObj, $dataParams, $id);
        }else{// 调用中间层的方法来获得数据

            $result = $modelObj->saveById($dataParams, $id);
        }
        return $result;
    }

    /**
     * 批量修改设置
     *
     * @param string $Model_name model名称
     * @param string $primaryKey 主键字段,默认为id
     * @param string $dataParams 主键及要修改的字段值 二维数组 数组/json字符
     * @return array
     * @author zouyan(305463219@qq.com)
     */
    public static function batchSaveByPrimaryKey(Request $request)
    {
        // 获得对象
        $modelName = CommonRequest::get($request, 'Model_name');
        Tool::judgeEmptyParams('Model_name', $modelName);

        $dataParams = CommonRequest::get($request, 'dataParams');
        // json 转成数组
        jsonStrToArr($dataParams , 1, '参数[dataParams]格式有误!');

        $primaryKey = CommonRequest::get($request, 'primaryKey');
        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
            $successRels = CommonDB::batchSave($modelName, $dataParams, $primaryKey);
        }else{// 调用中间层的方法来获得数据
            // 获得对象
            static::requestGetObj($request,$modelObj);
            $successRels = $modelObj->saveBathById($dataParams, $primaryKey);
        }
        return  $successRels;
    }

    // 按条件修改
    public static function requestUpdate(Request $request, &$modelObj = null)
    {
        // 条件数组
        $queryParams = CommonRequest::get($request, 'queryParams');
        // json 转成数组
        jsonStrToArr($queryParams , 1, '参数[queryParams]格式有误!');

        // 字段数组
        $dataParams = CommonRequest::get($request, 'dataParams');
        // json 转成数组
        jsonStrToArr($dataParams , 1, '参数[dataParams]格式有误!');

        // 获得对象
        static::requestGetObj($request,$modelObj);

        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
            // $requestData =$modelObj->where($queryParams)->update($dataParams);
            $requestData = CommonDB::updateQuery($modelObj, $dataParams, $queryParams);
        }else{// 调用中间层的方法来获得数据
            $requestData = $modelObj->save($dataParams, $queryParams);
        }
        return $requestData;
    }


    //自增自减,通过条件-data操作的行数
    public static function requestSaveDecIncByQuery(Request $request, &$modelObj = null)
    {
        // 条件数组
        $queryParams = CommonRequest::get($request, 'queryParams');
        // json 转成数组
        jsonStrToArr($queryParams , 1, '参数[queryParams]格式有误!');
        // 增减类型 inc 增 ;dec 减[默认]
        $incDecType = CommonRequest::get($request, 'incDecType');

        // 增减字段
        $incDecField = CommonRequest::get($request, 'incDecField');
        Tool::judgeEmptyParams('incDecField', $incDecField);
        // 增减值
        $incDecVal = CommonRequest::get($request, 'incDecVal');
        if(!is_numeric($incDecVal)){
            throws('参数[incDecVal]必须是数字!');
        }
        // 修改的其它字段 -没有，则传空数组json
        $modifFields = CommonRequest::get($request, 'modifFields');
        jsonStrToArr($modifFields , 1, '参数[modifFields]格式有误!');

        // 获得对象
        static::requestGetObj($request,$modelObj);

        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
            $requestData = CommonDB::saveDecInc($modelObj, $incDecField, $incDecVal, $incDecType, $queryParams, $modifFields);
        }else{// 调用中间层的方法来获得数据
            $requestData = $modelObj->saveDecIncByQuery($incDecField, $incDecVal, $incDecType, $queryParams, $modifFields);
        }
        return $requestData;

    }

    /**
     * 批量修改设置
     *
     * @param string $Model_name model名称
     * @param string $dataParams 主键及要修改的字段值 二维数组 数组/json字符 ;
     *
    $dataParams = [
    [
    'Model_name' => 'model名称',
    'primaryVal' => '主键字段值',
    'incDecType' => '增减类型 inc 增 ;dec 减[默认]',
    'incDecField' => '增减字段',
    'incDecVal' => '增减值',
    'modifFields' => '修改的其它字段 -没有，则传空数组',
    ],
    ];
     * @return array
     * @author zouyan(305463219@qq.com)
     */
    public static function batchSaveDecIncByPrimaryKey(Request $request)
    {
        $dataParams = CommonRequest::get($request, 'dataParams');
        // json 转成数组
        jsonStrToArr($dataParams , 1, '参数[dataParams]格式有误!');

        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
            $successRels = CommonDB::saveDecIncBatchByPrimaryKey( $dataParams );
        }else {// 调用中间层的方法来获得数据
            // 获得对象
            static::requestGetObj($request,$modelObj);
            $successRels = $modelObj->saveDecIncByArr($dataParams);
        }
        return  $successRels;
    }

    //自增自减-data操作的行数
//    public static function requestSaveDecIncqqqqq(Request $request, &$modelObj = null)
//    {
//        // 字段数组
//        $dataParams = CommonRequest::get($request, 'dataParams');
//
//        // json 转成数组
//        jsonStrToArr($dataParams , 1, '参数[dataParams]格式有误!');

//        // 获得对象
//        static::requestGetObj($request,$modelObj);
//        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
//           $requestData =$modelObj->find(7)->increment('validate_num', 5);
//        }else{// 调用中间层的方法来获得数据
//           $requestData = $modelObj->aaaaaaa();
//        }
//        return $requestData;
//    }


    /**
     * 获得 id=> 键值对 或 查询的数据
     *
     * @param array $kv ['key' => 'id字段', 'val' => 'name字段'] 或 [] 返回查询的数据
     * @param array $select 需要获得的字段数组   ['id', 'area_name'] ; 参数 $kv 不为空，则此参数可为空数组
     * @param array $queryParams 查询条件
     * @author zouyan(305463219@qq.com)
     */
    public static function requestGetKV(Request $request, &$modelObj = null){
        // 查询的kv字段数据参数--一维数组
        $kvParams = CommonRequest::get($request, 'kvParams');
        if(empty($kvParams)) $kvParams = [];
        // json 转成数组
        jsonStrToArr($kvParams , 1, '参数[kvParams]格式有误!');

        // 查询字段参数--一维数组
        $selectParams = CommonRequest::get($request, 'selectParams');
        if(empty($selectParams)) $selectParams = [];
        // json 转成数组
        jsonStrToArr($selectParams , 1, '参数[$selectParams]格式有误!');

        // 查询条件参数--二维数组
        $queryParams = CommonRequest::get($request, 'queryParams');
        if(empty($queryParams)) $queryParams = [];
        // json 转成数组
        jsonStrToArr($queryParams , 1, '参数[queryParams]格式有误!');

        // 获得对象
        static::requestGetObj($request,$modelObj);

        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
            $requestData = CommonDB::getKeyVals($modelObj, $kvParams, $selectParams, $queryParams);
        }else{// 调用中间层的方法来获得数据
            $requestData = $modelObj->getKeyVals($kvParams, $selectParams, $queryParams);
        }
        return  $requestData;
    }

    /**
     * 需要使用历史字段时，获得历史id
     *
     * @param string $Model_name model名称
     * @param mixed $primaryVal 主表对象主键值
     * @param obj $historyObj 历史表对象
     * @param string $historyTable 历史表名字
     * @param array $historySearch 历史表查询字段[一维数组][一定要包含主表id的] +  版本号(不用传，自动会加上) 格式 ['字段1'=>'字段1的值','字段2'=>'字段2的值' ... ]
     * @param array $ignoreFields 忽略都有的字段中，忽略主表中的记录 [一维数组] 格式 ['字段1','字段2' ... ]
     * @return int 历史表id
     * @author zouyan(305463219@qq.com)
     */
    public static function requestGetHistoryId(Request $request, &$modelObj = null){

        //主表对象主键值
        $primaryVal = CommonRequest::get($request, 'primaryVal');
        Tool::judgeEmptyParams('primaryVal', $primaryVal);

        // 历史表
        $historyObj = null;
        $historyObjName = CommonRequest::get($request, 'historyObj');//历史表对象名称
        if(static::$dataFromType == 1) CommonDB::getObjByModelName($historyObjName, $historyObj);


        // 历史表名字
        $historyTableName = CommonRequest::get($request, 'historyTable');//历史表名称
        Tool::judgeEmptyParams('historyTable', $historyTableName);

        //  历史表查询字段[一维数组][一定要包含主表id的] +  版本号(不用传，自动会加上)
        $historySearch = CommonRequest::get($request, 'historySearch');
        Tool::judgeEmptyParams('historySearch', $historySearch);
        // json 转成数组
        jsonStrToArr($historySearch , 1, '参数[historySearch]格式有误!');

        // 忽略都有的字段中，忽略主表中的记录 [一维数组]
        $ignoreFields = CommonRequest::get($request, 'ignoreFields');
        //Tool::judgeEmptyParams('ignoreFields', $ignoreFields);
        // json 转成数组
        jsonStrToArr($ignoreFields , 1, '参数[ignoreFields]格式有误!');

        // 获得对象
        static::requestGetObj($request,$modelObj);

        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
            CommonDB::getHistory($modelObj, $primaryVal, $historyObj,$historyTableName, $historySearch, $ignoreFields);
        }else{// 调用中间层的方法来获得数据
            $dbObj = null ;
            $modelObj->getHistoryId($dbObj, $primaryVal, $historyObjName, $historyTableName, $historyObj, $historySearch, $ignoreFields);
        }
        return  $historyObj->id;
    }

    /**
     * 对比主表和历史表是否相同，相同：不更新版本号，不同：版本号+1
     *
     * @param string 必填 $Model_name model名称 或传入 $modelObj 对象
     * @param mixed $primaryVal 主表对象主键值
     * @param obj $historyObj 历史表对象
     * @param string $historyTable 历史表名字
     * @param array $historySearch 历史表查询字段[一维数组][一定要包含主表id的] +  版本号(不用传，自动会加上) 格式 ['字段1'=>'字段1的值','字段2'=>'字段2的值' ... ]
     * @param array $ignoreFields 忽略都有的字段中，忽略主表中的记录 [一维数组] - 必须会有 [历史表中对应主表的id字段] 格式 ['字段1','字段2' ... ]
     * @param int $forceIncVersion 如果需要主表版本号+1,是否更新主表 1 更新 ;0 不更新
     * @return array 不同字段的内容 数组 [ '字段名' => ['原表中的值','历史表中的值']]; 空数组：不用版本号+1;非空数组：版本号+1
     * @author zouyan(305463219@qq.com)
     */
    public static function requestCompareHistoryOrUpdateVersion(Request $request, &$modelObj = null){

        //主表对象主键值
        $primaryVal = CommonRequest::get($request, 'primaryVal');
        Tool::judgeEmptyParams('primaryVal', $primaryVal);

        // 历史表
        $historyObj = null;
        $historyObjName = CommonRequest::get($request, 'historyObj');//历史表对象名称
        if(static::$dataFromType == 1) CommonDB::getObjByModelName($historyObjName, $historyObj);

        // 历史表名字
        $historyTableName = CommonRequest::get($request, 'historyTable');//历史表名称
        Tool::judgeEmptyParams('historyTable', $historyTableName);

        //  历史表查询字段[一维数组][一定要包含主表id的] +  版本号(不用传，自动会加上)
        $historySearch = CommonRequest::get($request, 'historySearch');
        Tool::judgeEmptyParams('historySearch', $historySearch);
        // json 转成数组
        jsonStrToArr($historySearch , 1, '参数[historySearch]格式有误!');

        // 忽略都有的字段中，忽略主表中的记录 [一维数组]
        $ignoreFields = CommonRequest::get($request, 'ignoreFields');
        //Tool::judgeEmptyParams('ignoreFields', $ignoreFields);
        // json 转成数组
        jsonStrToArr($ignoreFields , 1, '参数[ignoreFields]格式有误!');


        $forceIncVersion =  CommonRequest::getInt($request, 'forceIncVersion');

        // 获得对象
        static::requestGetObj($request,$modelObj);

        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
            $diffDataArr = CommonDB::compareHistoryOrUpdateVersion($modelObj, $primaryVal, $historyObj,$historyTableName, $historySearch, $ignoreFields, $forceIncVersion);
        }else{// 调用中间层的方法来获得数据
            $dbObj = null ;
            $diffDataArr = $modelObj->compareHistoryOrUpdateVersion($dbObj, $primaryVal, $historyObjName, $historyTableName, $historyObj, $historySearch, $ignoreFields, $forceIncVersion);
        }
        return  $diffDataArr;
    }

    /**
     * 查找记录,或创建新记录[没有找到] - $searchConditon +  $updateFields 的字段,
     *
     *  @param string 必填 $Model_name model名称 或传入 $modelObj 对象
     * @param array $searchConditon 查询字段[一维数组]
     * @param array $updateFields 表中还需要保存的记录 [一维数组] -- 新建表时会用
     * @return object $mainObj 表对象[一维]
     * @author zouyan(305463219@qq.com)
     */
    public static function requestFirstOrCreate(Request $request, &$modelObj = null){
        $searchConditon = CommonRequest::get($request, 'searchConditon');
        Tool::judgeEmptyParams('searchConditon', $searchConditon);
        // json 转成数组
        jsonStrToArr($searchConditon , 1, '参数[searchConditon]格式有误!');

        $updateFields = CommonRequest::get($request, 'updateFields');
        Tool::judgeEmptyParams('updateFields', $updateFields);
        // json 转成数组
        jsonStrToArr($updateFields , 1, '参数[updateFields]格式有误!');

        // 获得对象
        static::requestGetObj($request,$modelObj);

        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
            CommonDB::firstOrCreate($modelObj, $searchConditon, $updateFields );
        }else{// 调用中间层的方法来获得数据
            $dbObj = null ;
            $modelObj = $modelObj->firstOrCreate($dbObj, $searchConditon, $updateFields);
            // $modelObj = $mainObj;
        }
        return  $modelObj;
    }

    /**
     * 已存在则更新，否则创建新模型--持久化模型，所以无需调用 save()- $searchConditon +  $updateFields 的字段,
     *
     * @param string 必填 $Model_name model名称 或传入 $modelObj 对象
     * @param array $searchConditon 查询字段[一维数组]
     * @param array $updateFields 表中还需要保存的记录 [一维数组] -- 新建表时会用
     * @return object $mainObj 表对象[一维]
     * @author zouyan(305463219@qq.com)
     */
    public static function requestUpdateOrCreate(Request $request, &$modelObj = null){

        $searchConditon = CommonRequest::get($request, 'searchConditon');
        Tool::judgeEmptyParams('searchConditon', $searchConditon);
        // json 转成数组
        jsonStrToArr($searchConditon , 1, '参数[searchConditon]格式有误!');

        $updateFields = CommonRequest::get($request, 'updateFields');
        Tool::judgeEmptyParams('updateFields', $updateFields);
        // json 转成数组
        jsonStrToArr($updateFields , 1, '参数[updateFields]格式有误!');

        // 获得对象
        static::requestGetObj($request,$modelObj);

        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
            CommonDB::updateOrCreate($modelObj, $searchConditon, $updateFields );
        }else{// 调用中间层的方法来获得数据
            $dbObj = null ;
            $modelObj = $modelObj->updateOrCreate($dbObj, $searchConditon, $updateFields);
            // $modelObj = $mainObj;
        }
        return  $modelObj;
    }

    //  @param string 必填 $Model_name model名称 或传入 $modelObj 对象
    public static function requestGetAttr(Request $request, &$modelObj = null){

        $attrName = CommonRequest::get($request, 'attrName');
        Tool::judgeEmptyParams('attrName', $attrName);

        $isStatic = CommonRequest::getInt($request, 'isStatic');

        // 获得对象
        static::requestGetObj($request,$modelObj);

        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
            // $attrVal = CommonDB::getAttr($modelObj, $attrName, $isStatic);
            $attrVal = Tool::getAttr($modelObj, $attrName, $isStatic);
        }else{// 调用中间层的方法来获得数据
            $attrVal = $modelObj->getAttr($attrName, $isStatic);
        }
        return  $attrVal;
    }

    //  @param string 必填 $Model_name model名称 或传入 $modelObj 对象
    public static function requestExeMethod(Request $request, &$modelObj = null){

        $methodName = CommonRequest::get($request, 'methodName');
        Tool::judgeEmptyParams('methodName', $methodName);

        $params = CommonRequest::get($request, 'params');
        // Tool::judgeEmptyParams('params', $params);
        // json 转成数组
        if (!empty($params)) jsonStrToArr($params , 1, '参数[params]格式有误!');
        if (!is_array($params)) $params =[];

        // 获得对象
        static::requestGetObj($request,$modelObj);

        if(static::$dataFromType == 1){// 直接通过数据模型获得数据
            // $result = CommonDB::exeMethod($modelObj, $methodName, $params);
            $result = Tool::exeMethod($modelObj, $methodName, $params);
        }else{// 调用中间层的方法来获得数据
            $result = $modelObj->exeMethod($methodName, $params);
        }
        return  $result;
    }

}