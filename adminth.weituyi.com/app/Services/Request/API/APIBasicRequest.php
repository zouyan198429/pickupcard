<?php

namespace App\Services\Request\API;

use App\Services\Tool;
/**
 *具体业务通用接口类--请求api--公有接口
 *  如果是自己的数据库系统，可以继承此公用方法
 *  第三方其它的，不用继承此类
 */
class APIBasicRequest
{
    /**
     * 获得请求地址-- 子类必须重写此方法
     *
     * @return string 接口地址
     * @author zouyan(305463219@qq.com)
     */
    public static function getUrl(){
        return '';//config('public.apiUrl');
    }

    /**
     * 对比主表和历史表是否相同，相同：不更新版本号，不同：版本号+1
     *
     * @param string $modelName 主表对象名称
     * @param mixed $primaryVal 主表对象主键值
     * @param string $historyObj 历史表对象名称
     * @param string $HistoryTableName 历史表名字
     * @param array $historySearch 历史表查询字段[一维数组][一定要包含主表id的] +  版本号(不用传，自动会加上)  格式 ['字段1'=>'字段1的值','字段2'=>'字段2的值' ... ]
     * @param array $ignoreFields 忽略都有的字段中，忽略主表中的记录 [一维数组]必须会有 [历史表中对应主表的id字段]  格式 ['字段1','字段2' ... ]
     * @param int $forceIncVersion 如果需要主表版本号+1,是否更新主表 1 更新 ;0 不更新
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return array 不同字段的内容 数组 [ '字段名' => ['原表中的值','历史表中的值']]; 空数组：不用版本号+1;非空数组：版本号+1
     * @author zouyan(305463219@qq.com)
     */
    public static function compareHistoryOrUpdateVersionApi($modelName, $primaryVal, $historyObj, $HistoryTableName, $historySearch, $ignoreFields = [], $forceIncVersion= 1, $companyId = null , $notLog = 0){
//        $url = config('public.apiUrl') . config('apiUrl.common.compareHistoryOrUpdateVersionApi');
        $url = static::getUrl() . config('apiUrl.common.compareHistoryOrUpdateVersionApi');
        $requestData = [
            'Model_name' => $modelName,
            'primaryVal' => $primaryVal,
            'historyObj' => $historyObj,
            'historyTable' => $HistoryTableName,
            'historySearch' => $historySearch,
            'ignoreFields' => $ignoreFields,
            'forceIncVersion' => $forceIncVersion ,
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        // 生成带参数的测试get请求
         $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }


    /**
     * 根据主表id，获得对应的历史表id
     *
     * @param string $modelName 主表对象名称
     * @param mixed $primaryVal 主表对象主键值
     * @param string $historyObj 历史表对象名称
     * @param string $HistoryTableName 历史表名字
     * @param array $historySearch 历史表查询字段[一维数组][一定要包含主表id的] +  版本号(不用传，自动会加上) 格式 ['字段1'=>'字段1的值','字段2'=>'字段2的值' ... ]
     * @param array $ignoreFields 忽略都有的字段中，忽略主表中的记录 [一维数组] 格式 ['字段1','字段2' ... ]
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  mixed 历史记录表id
     * @author zouyan(305463219@qq.com)
     */
    public static function getHistoryIdApi($modelName, $primaryVal, $historyObj, $HistoryTableName, $historySearch, $ignoreFields = [], $companyId = null , $notLog = 0){
//        $url = config('public.apiUrl') . config('apiUrl.common.getHistoryIdApi');
        $url = static::getUrl() . config('apiUrl.common.getHistoryIdApi');
        $requestData = [
            'Model_name' => $modelName,
            'primaryVal' => $primaryVal,
            'historyObj' => $historyObj,
            'historyTable' => $HistoryTableName,
            'historySearch' => $historySearch,
            'ignoreFields' => $ignoreFields,
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 查找记录,或创建新记录[没有找到] - $searchConditon +  $updateFields 的字段,
     *
     * @param string $modelName 主表对象名称
     * @param array $searchConditon 查询字段[一维数组]
     * @param array $updateFields 表中还需要保存的记录 [一维数组] -- 新建表时会用
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return array $mainObj 表对象[一维]
     * @author zouyan(305463219@qq.com)
     */
    public static function firstOrCreateApi($modelName, $searchConditon, $updateFields, $companyId = null , $notLog = 0){
//        $url = config('public.apiUrl') . config('apiUrl.common.firstOrCreateApi');
        $url = static::getUrl() . config('apiUrl.common.firstOrCreateApi');
        $requestData = [
            'Model_name' => $modelName,
            'searchConditon' => $searchConditon,
            'updateFields' => $updateFields,
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 查找记录,或创建新记录[没有找到] - $searchConditon +  $updateFields 的字段,
     *
     * @param string $modelName 主表对象名称
     * @param array $searchConditon 查询字段[一维数组]
     * @param array $updateFields 表中还需要保存的记录 [一维数组] -- 新建表时会用
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return array $mainObj 表对象[一维]
     * @author zouyan(305463219@qq.com)
     */
    public static function updateOrCreateApi($modelName, $searchConditon, $updateFields, $companyId = null , $notLog = 0){
//        $url = config('public.apiUrl') . config('apiUrl.common.updateOrCreateApi');
        $url = static::getUrl() . config('apiUrl.common.updateOrCreateApi');
        $requestData = [
            'Model_name' => $modelName,
            'searchConditon' => $searchConditon,
            'updateFields' => $updateFields,
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 根据model的id获得详情记录
     *
     * @param string $modelName 主表对象名称
     * @param array $selectParams 查询字段参数--一维数组
     * @param array $relations 要查询的与其它表的关系
     * @param int $companyId 企业id
     * @param int $id id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function getinfoApi($modelName, $selectParams = [], $relations = [], $companyId = null , $id = null, $notLog = 0){
//        $url = config('public.apiUrl') . config('apiUrl.common.getinfoApi');
        $url = static::getUrl() . config('apiUrl.common.getinfoApi');
        $requestData = [
            // 'company_id' => $companyId,
            // 'id' => $id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        if(empty($id) || (!is_numeric($id))){
            //throws('需要获取的记录id有误!', $this->source);
            throws('需要获取的记录id有误!');
        }
        //if (is_numeric($id) && $id > 0) {
        $requestData['id'] = $id ;
        //}

        if (!empty($selectParams)) {
            $requestData['selectParams'] = $selectParams ;
        }

        if (!empty($relations)) {
            $requestData['relations'] = $relations ;
        }
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 根据model的条件获得一条详情记录 - 一维
     *
     * @param object $modelObj 当前模型对象
     * @param int $companyId 企业id
     * @param string $queryParams 条件数组/json字符
     * @param string $relations 关系数组/json字符
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function getInfoByQuery($modelName, $companyId = null,$queryParams='' ,$relations = '', $notLog = 0){
        $pageParams = [
            'page' =>1,
            'pagesize' => 1,
            'total' => 1,
        ];

        $resultDatas = static::ajaxGetList($modelName, $pageParams, $companyId,$queryParams ,$relations,$notLog);
        $dataList = $resultDatas['dataList'] ?? [];
        return $dataList[0] ?? [];
    }


    /**
     * 根据model的id获得详情记录 pagesize 1:返回一维数组,>1 返回二维数组   -- 推荐有这个按条件查询详情
     *
     * @param string $modelName 主表对象名称
     * @param array $queryParams 查询条件参数
     * @param array $relations 要查询的与其它表的关系
     * @param int $companyId 企业id
     * @param int $pagesize id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function getinfoQuery($modelName, $queryParams = [], $relations = [], $companyId = null , $pagesize = 1, $notLog = 0){
//        $url = config('public.apiUrl') . config('apiUrl.common.getinfoApi');
        $url = static::getUrl() . config('apiUrl.common.getinfoQueryApi');
        $requestData = [
            // 'company_id' => $companyId,
            // 'id' => $id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        if(empty($pagesize) || (!is_numeric($pagesize))){
            //throws('需要获取的记录id有误!', $this->source);
            throws('需要获取的pagesize有误!');
        }
        //if (is_numeric($pagesize) && $pagesize > 0) {
        $requestData['pagesize'] = $pagesize ;
        //}

        if (!empty($queryParams)) {
            $requestData['queryParams'] = $queryParams ;
        }

        if (!empty($relations)) {
            $requestData['relations'] = $relations ;
        }
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }


    /**
     * 根据条件，获得kv数据
     *
     * @param object $modelName 当前模型对象
     * @param array $kvParams 查询的kv字段数据参数数组/json字符  ['key' => 'id字段', 'val' => 'name字段'] 或 [] 返回查询的数据
     * @param array $selectParams 查询字段参数数组/json字符 需要获得的字段数组   ['id', 'area_name'] ; 参数 $kv 不为空，则此参数可为空数组
     * @param array $queryParams 查询条件
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return array kv数组
     * @author zouyan(305463219@qq.com)
     */
    public static function getKVApi($modelName, $kvParams = '', $selectParams = '', $queryParams = '', $companyId = null , $notLog = 0){
//        $url = config('public.apiUrl') . config('apiUrl.common.getkvApi');
        $url = static::getUrl() . config('apiUrl.common.getkvApi');
        $requestData = [
            // 'company_id' => $companyId,
            // 'id' => $id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
//        if(empty($id) || (!is_numeric($id))){
//            //throws('需要获取的记录id有误!', $this->source);
//            throws('需要获取的记录id有误!');
//        }

        if (!empty($kvParams)) {
            $requestData['kvParams'] = $kvParams ;
        }

        if (!empty($selectParams)) {
            $requestData['selectParams'] = $selectParams ;
        }

        if (!empty($queryParams)) {
            $requestData['queryParams'] = $queryParams ;
        }

        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 获得数据模型属性
     *
     * @param object $modelObj 当前模型对象 Model的路径和名称 如 RunBuy\LrChinaCity
     * @param string $attrName 属性名称[静态或动态]--注意静态时不要加$ ,与动态属性一样 如 attrTest
     * @param int $isStatic 是否静态属性 0：动态属性 1 静态属性
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function getAttrApi($modelName, $attrName = '', $isStatic = 0, $companyId = null , $notLog = 0){
        // $url = config('public.apiUrl') . config('apiUrl.common.attrApi');
        $url = static::getUrl() . config('apiUrl.common.attrApi');
        $requestData = [
            // 'company_id' => $companyId,
            // 'id' => $id,
            'Model_name' => $modelName, // 模型
            'isStatic' => $isStatic,
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
//        if(empty($id) || (!is_numeric($id))){
//            //throws('需要获取的记录id有误!', $this->source);
//            throws('需要获取的记录id有误!');
//        }

        if (!empty($attrName)) {
            $requestData['attrName'] = $attrName ;
        }

        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 调用数据模型方法
     *
     * @param object $modelObj 当前模型对象 Model的路径和名称 如 RunBuy\LrChinaCity
     * @param string $methodName 方法名称
     * @param array $params 参数数组 没有参数:[]  或 [第一个参数, 第二个参数,....];
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function exeMethodApi($modelName, $methodName = '', $params = [], $companyId = null , $notLog = 0){
//        $url = config('public.apiUrl') . config('apiUrl.common.exeMethodApi');
        $url = static::getUrl() . config('apiUrl.common.exeMethodApi');
        $requestData = [
            // 'company_id' => $companyId,
            // 'id' => $id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
//        if(empty($id) || (!is_numeric($id))){
//            //throws('需要获取的记录id有误!', $this->source);
//            throws('需要获取的记录id有误!');
//        }

        if (!empty($methodName)) {
            $requestData['methodName'] = $methodName ;
        }

        if (!empty($params)) {
            $requestData['params'] = $params ;
        }

        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 获得中间Business-DB层属性
     *
     * @param object $modelObj 当前模型对象 Model的路径和名称 如 RunBuy\LrChinaCity
     * @param string $attrName 属性名称[静态或动态]--注意静态时不要加$ ,与动态属性一样 如 attrTest
     * @param int $isStatic 是否静态属性 0：动态属性 1 静态属性
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function getBusinessDBAttrApi($modelName, $attrName = '', $isStatic = 0, $companyId = null , $notLog = 0){
//        $url = config('public.apiUrl') . config('apiUrl.common.businessDBAttrApi');
        $url = static::getUrl() . config('apiUrl.common.businessDBAttrApi');
        $requestData = [
            // 'company_id' => $companyId,
            // 'id' => $id,
            'Model_name' => $modelName, // 模型
            'isStatic' => $isStatic,
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
//        if(empty($id) || (!is_numeric($id))){
//            //throws('需要获取的记录id有误!', $this->source);
//            throws('需要获取的记录id有误!');
//        }

        if (!empty($attrName)) {
            $requestData['attrName'] = $attrName ;
        }

        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 调用中间Business-DB层方法
     *
     * @param object $modelObj 当前模型对象 Model的路径和名称 如 RunBuy\LrChinaCity
     * @param string $methodName 方法名称
     * @param array $params 参数数组 没有参数:[]  或 [第一个参数, 第二个参数,....];
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function exeDBBusinessMethodApi($modelName, $methodName = '', $params = [], $companyId = null , $notLog = 0){
//        $url = config('public.apiUrl') . config('apiUrl.common.exeBusinessDBMethodApi');
        $url = static::getUrl() . config('apiUrl.common.exeBusinessDBMethodApi');
        $requestData = [
            // 'company_id' => $companyId,
            // 'id' => $id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
//        if(empty($id) || (!is_numeric($id))){
//            //throws('需要获取的记录id有误!', $this->source);
//            throws('需要获取的记录id有误!');
//        }

        if (!empty($methodName)) {
            $requestData['methodName'] = $methodName ;
        }

        if (!empty($params)) {
            $requestData['params'] = $params ;
        }
        // 生成带参数的测试get请求
        $requestTesUrl = splicQuestAPI($url , $requestData);
        // pr($requestTesUrl);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 获得中间Business层属性
     *
     * @param object $modelObj 当前模型对象
     * @param string $attrName 属性名称[静态或动态]--注意静态时不要加$ ,与动态属性一样 如 attrTest
     * @param int $isStatic 是否静态属性 0：动态属性 1 静态属性
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function getBusinessAttrApi($modelName, $attrName = '', $isStatic = 0, $companyId = null , $notLog = 0){
//        $url = config('public.apiUrl') . config('apiUrl.common.businessAttrApi');
        $url = static::getUrl() . config('apiUrl.common.businessAttrApi');
        $requestData = [
            // 'company_id' => $companyId,
            // 'id' => $id,
            'Model_name' => $modelName, // 模型
            'isStatic' => $isStatic,
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
//        if(empty($id) || (!is_numeric($id))){
//            //throws('需要获取的记录id有误!', $this->source);
//            throws('需要获取的记录id有误!');
//        }

        if (!empty($attrName)) {
            $requestData['attrName'] = $attrName ;
        }

        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 调用中间Business层方法
     *
     * @param object $modelObj 当前模型对象
     * @param string $methodName 方法名称
     * @param array $params 参数数组 没有参数:[]  或 [第一个参数, 第二个参数,....];
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function exeBusinessMethodApi($modelName, $methodName = '', $params = [], $companyId = null , $notLog = 0){
//        $url = config('public.apiUrl') . config('apiUrl.common.exeBusinessMethodApi');
        $url = static::getUrl() . config('apiUrl.common.exeBusinessMethodApi');
        $requestData = [
            // 'company_id' => $companyId,
            // 'id' => $id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
//        if(empty($id) || (!is_numeric($id))){
//            //throws('需要获取的记录id有误!', $this->source);
//            throws('需要获取的记录id有误!');
//        }

        if (!empty($methodName)) {
            $requestData['methodName'] = $methodName ;
        }

        if (!empty($params)) {
            $requestData['params'] = $params ;
        }

        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }


    /**
     * ajax指定条件删除记录
     *
     * @param object $modelObj 当前模型对象
     * @param int $companyId 企业id
     * @param string $queryParams 条件数组/json字符
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function ajaxDelApi($modelName, $companyId = null,$queryParams =''  , $notLog = 0){

        $requestData = [
            // 'company_id' => $this->company_id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
            'queryParams' =>$queryParams,//[// 查询条件参数
            //    'where' => [
            //        ['id', $id],
            //        ['company_id', $this->company_id]
            //    ]
            //],
        ];
        //$where = [];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
            // array_push($where,['company_id', $companyId]) ;
        }
//        if (is_numeric($id) && $id > 0) {
//            array_push($where,['id', $id]) ;
//        }
//        if(!empty($where)){
//            $requestData['queryParams']['where'] = $where ;
//        }
//        $url = config('public.apiUrl') . config('apiUrl.common.delApi');
        $url = static::getUrl() . config('apiUrl.common.delApi');

        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }


    /**
     * ajax获得列表记录
     *
     * @param object $modelObj 当前模型对象
     * @param int $companyId 企业id
     * @param array $pageParams
         [
            'page' => $page,
            'pagesize' => $pagesize,
            'total' => $total,
        ]
     * @param string $queryParams 条件数组/json字符
     * @param string $relations 关系数组/json字符
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function ajaxGetAllList($modelName, $pageParams, $companyId = null,$queryParams='' ,$relations = '', $notLog = 0){
        $requestData = [
            // 'company_id' => $companyId,
            'Model_name' => $modelName, // 模型
            'queryParams' => $queryParams, // 查询条件参数
            'relations' => $relations, // 查询关系参数
            'not_log' => $notLog,
        ];
        //$where = [];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
            //   array_push($where,['company_id', $companyId]) ;
        }
//        if(!empty($where)){
//            if(!is_array($queryParams)){
//                $queryParams = [];
//            }
//            if(!isset($queryParams['where'])){
//                $queryParams['where'] = [];
//            }
//            if(isset($queryParams['where']) && empty($queryParams['where'])) {
//                $queryParams['where'] = [];
//            }
//            $queryParams['where'] = array_merge($queryParams['where'],$where);
//            $requestData['queryParams'] = $queryParams ;
//        }

        // $requestData = array_merge($requestData,$pageParams);
//        $url = config('public.apiUrl') . config('apiUrl.common.getAllApi');
        $url = static::getUrl() . config('apiUrl.common.getAllApi');
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }


    /**
     * ajax获得列表记录
     *
     * @param object $modelObj 当前模型对象
     * @param int $companyId 企业id
     * @param array $pageParams
         [
            'page' => $page,
            'pagesize' => $pagesize,
            'total' => $total,
        ]
     * @param string $queryParams 条件数组/json字符
     * @param string $relations 关系数组/json字符
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function ajaxGetQueryList($modelName, $pageParams, $companyId = null,$queryParams='' ,$relations = '', $notLog = 0){
        $requestData = [
            // 'company_id' => $companyId,
            'Model_name' => $modelName, // 模型
            'queryParams' => $queryParams, // 查询条件参数
            'relations' => $relations, // 查询关系参数
            'not_log' => $notLog,
        ];
        //$where = [];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
            //   array_push($where,['company_id', $companyId]) ;
        }
//        if(!empty($where)){
//            if(!is_array($queryParams)){
//                $queryParams = [];
//            }
//            if(!isset($queryParams['where'])){
//                $queryParams['where'] = [];
//            }
//            if(isset($queryParams['where']) && empty($queryParams['where'])) {
//                $queryParams['where'] = [];
//            }
//            $queryParams['where'] = array_merge($queryParams['where'],$where);
//            $requestData['queryParams'] = $queryParams ;
//        }

        // $requestData = array_merge($requestData,$pageParams);
//        $url = config('public.apiUrl') . config('apiUrl.common.getListQueryApi');
        $url = static::getUrl() . config('apiUrl.common.getListQueryApi');
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }


    /**
     * ajax获得列表记录
     *
     * @param object $modelObj 当前模型对象
     * @param int $companyId 企业id
     * @param array $pageParams
         [
            'page' => $page,
            'pagesize' => $pagesize,
            'total' => $total,
        ]
     * @param string $queryParams 条件数组/json字符
     * @param string $relations 关系数组/json字符
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function ajaxGetList($modelName, $pageParams, $companyId = null,$queryParams='' ,$relations = '', $notLog = 0)
    {
        $requestData = [
            // 'company_id' => $companyId,
            'Model_name' => $modelName, // 模型
            'queryParams' => $queryParams, // 查询条件参数
            'relations' => $relations, // 查询关系参数
            'not_log' => $notLog,
        ];
        // $where = [];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
            // array_push($where,['company_id', $companyId]) ;
        }
//        if(!empty($where)){
//            if(!is_array($queryParams)){
//                $queryParams = [];
//            }
//            if(!isset($queryParams['where'])){
//                $queryParams['where'] = [];
//            }
//            if(isset($queryParams['where']) && empty($queryParams['where'])) {
//                $queryParams['where'] = [];
//            }
//            $queryParams['where'] = array_merge($queryParams['where'],$where);
//            $requestData['queryParams'] = $queryParams ;
//        }

        $requestData = array_merge($requestData,$pageParams);

//        $url = config('public.apiUrl') . config('apiUrl.common.getlistApi');
        $url = static::getUrl() . config('apiUrl.common.getlistApi');
        // 生成带参数的测试get请求
        $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }


    /**
     * 修改或新加记录
     *
     * @param object $modelObj 当前模型对象
     * @param array $saveData 要保存或修改的数组
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function createApi($modelName,$saveData= [], $companyId = null, $notLog = 0 )
    {
        $requestData = [
            // 'company_id' => $this->company_id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        $requestData['dataParams'] = $saveData;
        // 新加用户
//        $url = config('public.apiUrl') . config('apiUrl.common.addnewApi');
        $url = static::getUrl() . config('apiUrl.common.addnewApi');
        // 生成带参数的测试get请求
        // echo $requestTesUrl = splicQuestAPI($url , $requestData); die;
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 批量新加-data只能返回成功true:失败:false
     *
     * @param object $modelObj 当前模型对象
     * @param array $saveData 要保存或修改的数组-二维数组
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function createBathApi($modelName, $saveData= [], $companyId = null, $notLog = 0 )
    {
        $requestData = [
            // 'company_id' => $this->company_id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        $requestData['dataParams'] = $saveData;
        // 新加用户
//        $url = config('public.apiUrl') . config('apiUrl.common.addnewBathApi');
        $url = static::getUrl() . config('apiUrl.common.addnewBathApi');
        // 生成带参数的测试get请求
        // echo $requestTesUrl = splicQuestAPI($url , $requestData); die;
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }


    /**
     * 批量新加-data返回成功的id数组
     *
     * @param object $modelObj 当前模型对象
     * @param array $saveData 要保存或修改的数组-二维数组
     * @param string $primaryKey 表的主键字段名称
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function createBathByPrimaryKeyApi($modelName,$saveData= [], $primaryKey = 'id', $companyId = null, $notLog = 0 )
    {
        $requestData = [
            // 'company_id' => $this->company_id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        $requestData['dataParams'] = $saveData;
        if(!empty($primaryKey)){
            $requestData['primaryKey'] = $primaryKey;
        }
        // 新加用户
//        $url = config('public.apiUrl') . config('apiUrl.common.addnewBathByIdApi');
        $url = static::getUrl() . config('apiUrl.common.addnewBathByIdApi');
        // 生成带参数的测试get请求
        // echo $requestTesUrl = splicQuestAPI($url , $requestData); die;
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }


    /**
     * 根据条件修改记录
     *
     * @param object $modelObj 当前模型对象
     * @param array $saveData 要保存或修改的数组
     * @param string $queryParams 条件数组/json字符
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function ModifyByQueyApi($modelName, $saveData= [], $queryParams='', $companyId = null, $notLog = 0 ){

        $requestData = [
            // 'company_id' => $this->company_id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
            'queryParams' => $queryParams, // 查询条件参数
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        $requestData['dataParams'] = $saveData;
        // 修改
//        $url = config('public.apiUrl') . config('apiUrl.common.saveApi');
        $url = static::getUrl() . config('apiUrl.common.saveApi');
        //$requestData['queryParams'] =[// 查询条件参数
        //    'where' => [
        //        ['id', $id],
        //       ['company_id', $company_id]
        //    ]
        //];
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 根据主健批量修改记录
     *
     * @param object $modelObj 当前模型对象
     * @param array $saveData 要保存或修改的数组
     * @param string $queryParams 条件数组/json字符
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function saveBathById($modelName, $saveData= [], $primaryKey = 'id', $companyId = null, $notLog = 0 ){

        $requestData = [
            // 'company_id' => $this->company_id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
            'primaryKey' => $primaryKey, // 记录主键
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        $requestData['dataParams'] = $saveData;
        // 修改
//        $url = config('public.apiUrl') . config('apiUrl.common.saveBathById');
        $url = static::getUrl() . config('apiUrl.common.saveBathById');
        // 生成带参数的测试get请求
        $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 通过id修改接口
     *
     * @param object $modelObj 当前模型对象
     * @param int $id id
     * @param int $companyId 企业id
     * @param array $saveData 要保存或修改的数组
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function saveByIdApi($modelName, $id, $saveData, $companyId = null, $notLog = 0){
        if(empty($id) ){
            // throws('需要更新的记录id不能为空!', $this->source);
            throws('需要更新的记录id不能为空!');
        }
        if(empty($saveData)){
            // throws('需要更新的数据不能为空!', $this->source);
            throws('需要更新的数据不能为空!');
        }
//        $url = config('public.apiUrl') . config('apiUrl.common.saveByIdApi');
        $url = static::getUrl() . config('apiUrl.common.saveByIdApi');
        $requestData = [
            // 'company_id' => $companyId,
            // 'id' => $id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        $requestData['dataParams'] = $saveData; // 需要更新的数据

        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        if (is_numeric($id) && $id > 0) {
            $requestData['id'] = $id ;
        }
        // 生成带参数的测试get请求
         $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 自增自减接口,通过条件-data操作的行数
     *
     * @param object $modelObj 当前模型对象
     * @param string $queryParams 条件数组/json字符
     * @param string incDecType 增减类型 inc 增 ;dec 减[默认]
     * @param string incDecField 增减字段
     * @param string incDecVal 增减值
     * @param array $saveData 要保存或修改的数组  修改的其它字段 -没有，则传空数组[]
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function incDecByQueyApi($modelName, $queryParams='', $incDecType = 'dec', $incDecField = '', $incDecVal = 0, $saveData= [], $companyId = null, $notLog = 0 ){

        $requestData = [
            // 'company_id' => $this->company_id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
            'queryParams' => $queryParams, // 查询条件参数
            'incDecType' => $incDecType, // 增减类型 inc 增 ;dec 减[默认]
            'incDecField' => $incDecField, // 增减字段
            'incDecVal' => $incDecVal, // 增减值
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        $requestData['dataParams'] = $saveData;
        // 修改
//        $url = config('public.apiUrl') . config('apiUrl.common.saveDecIncByQueryApi');
        $url = static::getUrl() . config('apiUrl.common.saveDecIncByQueryApi');
        //$requestData['queryParams'] =[// 查询条件参数
        //    'where' => [
        //        ['id', $id],
        //       ['company_id', $company_id]
        //    ]
        //];
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }



    /**
     * 自增自减接口,通过条件-data操作的行数
     *
     * @param object $modelObj 当前模型对象
     * @param array $saveData 要保存或修改的数组
        $saveData = [
            [
                'Model_name' => 'model名称',
                'primaryVal' => '主键字段值',
                'incDecType' => '增减类型 inc 增 ;dec 减[默认]',
                'incDecField' => '增减字段',
                'incDecVal' => '增减值',
                'modifFields' => '修改的其它字段 -没有，则传空数组',
            ],
        ];
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function bathIncDecByArrApi($modelName, $saveData= [], $companyId = null, $notLog = 0 ){

        $requestData = [
            'Model_name' => $modelName, // 模型
            // 'company_id' => $this->company_id,
            'not_log' => $notLog,
        ];
        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        $requestData['dataParams'] = $saveData;
        // 修改
//        $url = config('public.apiUrl') . config('apiUrl.common.saveDecIncByArrApi');
        $url = static::getUrl() . config('apiUrl.common.saveDecIncByArrApi');
        //$requestData['queryParams'] =[// 查询条件参数
        //    'where' => [
        //        ['id', $id],
        //       ['company_id', $company_id]
        //    ]
        //];
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 通过id同步修改关系接口
     *
     * @param object $modelObj 当前模型对象
     * @param int $id id
     * @param int $companyId 企业id
     * @param array $syncParams 要保存或修改的关系数组;可多个 ;格式 [ '关系方法名' =>关系值及相关字段]
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function saveSyncByIdApi($modelName, $id, $syncParams, $companyId = null, $notLog = 0){

        if(empty($id) ){
            // throws('需要更新的记录id不能为空!', $this->source);
            throws('需要更新的记录id不能为空!');
        }
        if(empty($syncParams)){
            // throws('需要更新的数据不能为空!', $this->source);
            throws('需要更新的数据不能为空!');
        }

//        $url = config('public.apiUrl') . config('apiUrl.common.saveSyncByIdApi');
        $url = static::getUrl() . config('apiUrl.common.saveSyncByIdApi');
        $requestData = [
            // 'company_id' => $companyId,
            // 'id' => $id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        $requestData['synces'] = $syncParams; // 需要更新的数据

        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        if (is_numeric($id) && $id > 0) {
            $requestData['id'] = $id ;

        }
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }


    /**
     * 通过id移除关系接口
     *
     * @param object $modelObj 当前模型对象
     * @param int $id id
     * @param int $companyId 企业id
     * @param array $detachParams 要移除的关系数组;可多个 ;格式 [ '关系方法名' =>关系id或空(全部移除)]
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function detachByIdApi($modelName, $id, $detachParams, $companyId = null, $notLog = 0){

        if(empty($id) ){
            // throws('需要更新的记录id不能为空!', $this->source);
            throws('需要更新的记录id不能为空!');
        }
        if(empty($detachParams)){
            // throws('需要移除的数据不能为空!', $this->source);
            throws('需要移除的数据不能为空!');
        }

//        $url = config('public.apiUrl') . config('apiUrl.common.detachApi');
        $url = static::getUrl() . config('apiUrl.common.detachApi');
        $requestData = [
            // 'company_id' => $companyId,
            // 'id' => $id,
            'Model_name' => $modelName, // 模型
            'not_log' => $notLog,
            // 'relations' => '', // 查询关系参数
        ];
        $requestData['detaches'] = $detachParams; // 需要移除的数据

        if (is_numeric($companyId) && $companyId > 0) {
            $requestData['company_id'] = $companyId ;
        }
        if (is_numeric($id) && $id > 0) {
            $requestData['id'] = $id ;

        }
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    // **************************************************************************************************************************

    // 判断权限-----开始
    // 判断权限 ,返回当前记录[可再进行其它判断], 有其它主字段的，可以重新此方法
    /**
     * 判断权限
     *
     * @param int $id id ,多个用,号分隔
     * @param array $judgeArr 需要判断的下标[字段名]及值 一维数组
     * @param string $model_name 模型名称
     * @param int $companyId 企业id
     * @param json/array $relations 要查询的与其它表的关系
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return array 一维数组[单条] 二维数组[多条]
     * @author zouyan(305463219@qq.com)
     */
    public static function judgePower($id, $judgeArr = [] , $model_name = '', $company_id = '', $relations = '', $notLog  = 0){
        // $this->InitParams($request);
//        if(empty($model_name)){
//            $model_name = $this->model_name;
//        }
        $dataList = [];
        $isSingle = true;// 是否单条记录 true:是;false：否
        if (strpos($id, ',') === false) { // 单条
            // 获得当前记录
            $dataList[] =  static::getinfoApi($model_name, '', $relations, $company_id , $id, $notLog);
        }else{
            $isSingle = false;
            $queryParams =  [
                'where' => [
                    //['company_id', $company_id],
                    //['mobile', $keyword],
                ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//            ],
                // 'orderBy' => ['id'=>'desc'],
            ];
            if($company_id != ''){
                array_push($queryParams['where'],['company_id', $company_id]);
            }
            $queryParams['whereIn']['id'] = explode(',',$id);
            $dataList = static::ajaxGetAllList($model_name, [], $company_id,$queryParams ,$relations, $notLog );
        }
        foreach($dataList as $infoData){
            static::judgePowerByObj($infoData, $judgeArr);
        }
        return $isSingle ? $dataList[0] : $dataList;
    }

    public static function judgePowerByObj($infoData, $judgeArr = [] ){
        if(empty($infoData)){
            // throws('记录不存!', $this->source);
            throws('记录不存!');
        }
        foreach($judgeArr as $field => $val){
            if(!isset($infoData[$field])){
                // throws('字段[' . $field . ']不存在!', $this->source);
                throws('字段[' . $field . ']不存在!');
            }
            if( $infoData[$field] != $val ){
                // throws('没有操作此记录权限!信息字段[' . $field . ']', $this->source);
                throws('没有操作此记录权限!信息字段[' . $field . ']');
            }
        }
    }

    // 判断权限-----结束
}
