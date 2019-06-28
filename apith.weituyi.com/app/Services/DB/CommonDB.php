<?php
// 通用工具服务类-- 操作数据库
namespace App\Services\DB;

use App\Services\Tool;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * 通用工具服务类-- 操作数据库
 */
class CommonDB
{
    //写一些通用方法
    /*
    public static function test(){
        echo 'aaa';die;
    }
    */

    // 根据数据模型名称，反回对象
    public static function getObjByModelName($modelName, &$modelObj = null){
        if (! is_object($modelObj)) {
            $className = "App\\Models\\" . $modelName;
            if (!class_exists($className)) {
                throws('参数[Model_name]不正确！');
            }
            $modelObj = new $className();
        }
        return $modelObj;
    }


    /**
     * 解析sql条件
     *
     * @param object &$tbObj
     * @param string $params array || json string
     * @author zouyan(305463219@qq.com)
     */
    public static function resolveSqlParams(&$tbObj ,$params = [])
    {
        if (empty($params) ) {
            return $tbObj;
        }
        if (jsonStrToArr($params , 2, '') === false){
            return $tbObj;
        }
        foreach($params as $key => $param){
            switch($key){
                case 'select':   // 使用一维数组
                    // 查询（Select）--
                    // select('name', 'email as user_email')->get();
                    // ->select(['id','company_id','phonto_name']);
                    if (! empty($param)){
                        $tbObj = $tbObj->select($param);
                    }
                    break;
                case 'addSelect': // 单个字段的字符
                    // 添加一个查询列到已存在的 select 子句，可以使用 addSelect 方法
                    // addSelect('age')->get();
                    if ( (! empty($param)) && is_string($param)){
                        $tbObj = $tbObj->addSelect($param);
                    }
                    break;
                case 'distinct': // 空字符
                    // distinct 方法允许你强制查询返回不重复的结果集
                    // $users = DB::table('users')->distinct()->get();
                    $tbObj = $tbObj->distinct();
                case 'where': //使用如下的二维数组.注意，如果是=,第二个参数可以不需要
                    /*[
                            ['status', '=', '1'],
                            ['subscribed', '<>', '1'],
                        ]
                    */
                    // Where 子句
                    // ->where('votes', '=', 100)
                    // ->where('votes', 100)
                    /* ->where([
                        ['status', '=', '1'],
                            ['subscribed', '<>', '1'],
                        ])
                    */
                    if ( (! empty($param)) && is_array($param)){
                        $tbObj = $tbObj->where($param);
                    }
                    break;
                case 'orWhere':// orWhere  子句
                    if ( (! empty($param)) && is_array($param)){
                        $tbObj = $tbObj->orWhere($param);
                    }
                    break;
                case 'whereDate': // 同where
                    // whereDate 方法用于比较字段值和日期
                    // ->whereDate('created_at', '2016-10-10')
                    if ( (! empty($param)) && is_array($param)){
                        $tbObj = $tbObj->whereDate($param);
                    }
                    break;
                case 'whereMonth':// 同where
                    // whereMonth 方法用于比较字段值和一年中的指定月份
                    // ->whereMonth('created_at', '10')
                    if ( (! empty($param)) && is_array($param)){
                        $tbObj = $tbObj->whereMonth($param);
                    }
                    break;
                case 'whereDay':// 同where
                    // whereDay 方法用于比较字段值和一月中的指定日期
                    // ->whereDay('created_at', '10')
                    if ( (! empty($param)) && is_array($param)){
                        $tbObj = $tbObj->whereDay($param);
                    }
                    break;
                case 'whereYear':// 同where
                    // whereYear 方法用于比较字段值和指定年
                    // ->whereYear('created_at', '2017')
                    if ( (! empty($param)) && is_array($param)){
                        $tbObj = $tbObj->whereYear($param);
                    }
                    break;
                case 'whereTime':// 同where
                    // whereTime 方法用于比较字段值和指定时间
                    // ->whereTime('created_at', '=', '11:20')
                    if ( (! empty($param)) && is_array($param)){
                        $tbObj = $tbObj->whereTime($param);
                    }
                    break;
                case 'whereBetween':// 数组 [1, 100]
                    // whereBetween  子句
                    // ->whereBetween('votes', [1, 100])
                    if ( (! empty($param)) && is_array($param)){
                        foreach($param as $betweenField => $rangeValsArr){
                            if(!is_array($rangeValsArr) || count($rangeValsArr) != 2) continue;
                            $tbObj = $tbObj->whereBetween($betweenField, $rangeValsArr);
                        }
                    }
                    break;
                case 'whereNotBetween':// 数组 [1, 100]
                    // whereNotBetween  子句
                    // ->whereNotBetween('votes', [1, 100])
                    if ( (! empty($param)) && is_array($param)){
                        foreach($param as $betweenField => $rangeValsArr){
                            if(!is_array($rangeValsArr) || count($rangeValsArr) != 2) continue;
                            $tbObj = $tbObj->whereNotBetween($betweenField, $rangeValsArr);
                        }
                    }
                    break;
                case 'whereIn': // 数组 [1, 2, 3] 二维数组 [ [字段名=>[多个字段值]],....]
                    // whereIn  子句
                    // ->whereIn('id', [1, 2, 3])
                    if ( (! empty($param)) && is_array($param)){
                        foreach($param as $field => $vals){
                            $tbObj = $tbObj->whereIn($field,$vals);
                        }
                    }

                    break;
                case 'whereNotIn':// 数组 [1, 2, 3] 二维数组 [ [字段名=>[多个字段值]],....]
                    // whereNotIn  子句
                    // ->whereNotIn('id', [1, 2, 3])
//                    if ( (! empty($param)) && is_array($param)){
//                        $tbObj = $tbObj->whereNotIn($param);
//                    }
                    if ( (! empty($param)) && is_array($param)){
                        foreach($param as $field => $vals){
                            $tbObj = $tbObj->whereNotIn($field,$vals);
                        }
                    }
                    break;
                case 'whereNull': // 字段字符 一维数组 ['字段名1',...]
                    // whereNull 方法验证给定列的值为 NULL
                    // ->whereNull('updated_at')
//                    if ( (! empty($param)) && is_string($param)){
//                        $tbObj = $tbObj->whereNull($param);
//                    }
                    if ( (! empty($param)) && is_array($param)){
                        foreach($param as $field ){
                            $tbObj = $tbObj->whereNull($field);
                        }
                    }
                    break;
                case 'whereNotNull':// 字段字符  一维数组 ['字段名1',...]
                    // whereNotNull 方法验证给定列的值不是 NULL
                    // ->whereNotNull('updated_at')
//                    if ( (! empty($param)) && is_string($param)){
//                        $tbObj = $tbObj->whereNotNull($param);
//                    }
                    if ( (! empty($param)) && is_array($param)){
                        foreach($param as $field ){
                            $tbObj = $tbObj->whereNotNull($field);
                        }
                    }
                    break;
                case 'whereColumn':// 同where -二维数组
                    // whereColumn 方法用于验证两个字段是否相等
                    // ->whereColumn('first_name', 'last_name')
                    // 还可以传递一个比较运算符到该方法
                    // ->whereColumn('updated_at', '>', 'created_at')
                    // 还可以传递多条件数组到 whereColumn 方法，这些条件通过 and 操作符进行连接
                    /*
                        ->whereColumn([
                            ['first_name', '=', 'last_name'],
                            ['updated_at', '>', 'created_at']
                        ])
                     */
                    if ( (! empty($param)) && is_array($param)){
                        $tbObj = $tbObj->whereColumn($param);
                    }
                    break;
                case 'orderBy':// 一维数组 ['name'=>'desc','name'=>'desc']
                    // orderBy 的第一个参数应该是你希望排序的字段，第二个参数控制着排序的方向 —— asc 或 desc
                    // ->orderBy('name', 'desc')
                    // ->orderBy('name', 'desc')
                    if ( (! empty($param)) && is_array($param)){
                        foreach($param as $orderField => $orderType){
                            $tbObj = $tbObj->orderBy($orderField,$orderType);
                        }

                    }
                    break;
                case 'latest':
                    // latest 和 oldest 方法允许你通过日期对结果进行排序，默认情况下，结果集根据 created_at 字段进行排序，或者，你可以按照你想要排序的字段作为字段名传入
                    // ->latest()
                    $tbObj = $tbObj->latest();
                    break;
                case 'oldest'://
                    $tbObj = $tbObj->oldest();
                    break;
                case 'inRandomOrder':// inRandomOrder 方法可用于对查询结果集进行随机排序，比如，你可以用该方法获取一个随机用户
                    $tbObj = $tbObj->inRandomOrder();
                    break;
                case 'groupBy':// 字段字符 或 一维数组 ['字段一','字段二']
                    // groupBy / having-对结果集进行分组
                    /*
                    ->groupBy('account_id')
                    ->having('account_id', '>', 100)
                    */
                    if ( (! empty($param)) && is_string($param)){
                        $tbObj = $tbObj->groupBy($param);
                    }else if(is_array($param)){
                        foreach($param as $groupField ){
                            $tbObj = $tbObj->groupBy($groupField);
                        }
                    }
                    break;
                case 'having':// 一维数组 [$havingField,$havingOperator,$havingValue]
                    if ( (! empty($param)) && is_array($param)){
                        $havingField = $param[0] ?? '';
                        $havingOperator = $param[1] ?? '';
                        $havingValue = $param[2] ?? '';
                        $tbObj = $tbObj->having($havingField, $havingOperator,$havingValue);
                    }
                    break;
                case 'skip': // 数字
                    // skip / take-限定查询返回的结果集的数目
                    // ->skip(10)->take(5)
                    if ( (! empty($param)) && is_numeric($param)){
                        $tbObj = $tbObj->skip($param);
                    }
                    break;
                case 'take':// 数字
                    if ( (! empty($param)) && is_numeric($param)){
                        $tbObj = $tbObj->take($param);
                    }
                    break;
                case 'limit':// 数字
                    // 为替代方法，还可以使用 limit 和 offset 方法
                    /*  ->offset(10)
                        ->limit(5)
                    */
                    if ( (! empty($param)) && is_numeric($param)){
                        $tbObj = $tbObj->limit($param);
                    }
                    break;
                case 'offset':// 数字
                    if ( (! empty($param)) && is_numeric($param)){
                        $tbObj = $tbObj->offset($param);
                    }
                    break;
                case 'find':// 单个数字 或 数组
                    // find 和 first 获取单个记录
                    // App\Flight::find(1);
                    // App\Flight::find([1, 2, 3]);
                    if ( (! empty($param))){
                        $tbObj = $tbObj->find($param);
                    }
                    break;
                case 'first':
                    // ->first();
                    $tbObj = $tbObj->first();
                    break;
                case 'findOrFail':// 单个数字 或 数组
                    // findOrFail 和 firstOrFail方法会获取查询到的第一个结果。不过，如果没有任何查询结果，Illuminate\Database\Eloquent\ModelNotFoundException 异常将会被抛出
                    if ( (! empty($param))){
                        $tbObj = $tbObj->findOrFail($param);
                    }
                    break;
                case 'firstOrFail':// bbb  子句
                    $tbObj = $tbObj->firstOrFail();
                    break;
                case 'value': // 字段名
                    // 不需要完整的一行，可以使用 value 方法从结果中获取单个值，该方法会直接返回指定列的值
                    // ->value('email');
                    if ( (! empty($param)) && is_string($param)){
                        $tbObj = $tbObj->value($param);
                    }
                    break;
                case 'pluck':// 字符 '字段名'或 ['字段名'] 或  ['别名'=>'字段名']
                    // 获取包含单个列值的数组，可以使用 pluck 方法
                    /*
                    $titles = DB::table('roles')->pluck('title');

                    foreach ($titles as $title) {
                        echo $title;
                    }

                    列值指定自定义键
                    ->pluck('title', 'name');

                    */
                    if ( (! empty($param)) && is_array($param)){
                        foreach($param as $k => $v){
                            if(is_string($k)){
                                $tbObj = $tbObj->pluck($v,$k);
                            }else{
                                $tbObj = $tbObj->pluck($v);
                            }
                        }
                    }else if( (! empty($param)) && is_string($param)){
                        $tbObj = $tbObj->pluck($param);
                    }

                    break;
                case 'count':// 获取聚合结果-  count, max, min, avg 和 sum
                    // ->count();
                    $tbObj = $tbObj->count();
                    break;
                case 'max':// 字段名
                    // ->max('price')
                    if ( (! empty($param)) && is_string($param)){
                        $tbObj = $tbObj->max($param);
                    }
                    break;
                case 'avg':// 字段名
                    // ->avg('price');
                    if ( (! empty($param)) && is_string($param)){
                        $tbObj = $tbObj->avg($param);
                    }
                    break;
                case 'sum':// bbb  子句
                    $tbObj = $tbObj->sum();
                    break;
                case 'exists':// 判断记录是否存在- exists 或 doesntExist 方法
                    // return DB::table('orders')->where('finalized', 1)->exists();
                    $tbObj = $tbObj->exists();
                    break;
                case 'doesntExist':// bbb  子句
                    // return DB::table('orders')->where('finalized', 1)->doesntExist();
                    $tbObj = $tbObj->doesntExist();
                    break;
                default:
            }

        }
        return $tbObj;
    }

    /**
     * 解析表关系
     *
     * @param object &$tbObj
     * @param string $relations array || json string
     * @return object
     * @author zouyan(305463219@qq.com)
     */
    public static function resolveRelations(&$tbObj ,$relations = [])
    {
        if (empty($relations)) {
            return $tbObj;
        }

        if (jsonStrToArr($relations , 2, '') === false){
            return $tbObj;
        }

        // 层关系
        $tbObj->load($relations);
        return $tbObj;
    }

    /**
     * 获得记录-根据条件
     *
     * @param object $modelObj 当前模型对象
     * @param array $queryParams 查询条件   有count下标则是查询数量--是否是查询总数
     * @param array $relations 要查询的与其它表的关系
     * @return object 数据对象
     * @author zouyan(305463219@qq.com)
     */
    public static function getList(&$modelObj,$queryParams,$relations){

        if(isset($queryParams['count']) ){// 查询总数
            $requestData = self::resolveSqlParams($modelObj, $queryParams);
        }else{
            // 查询条件
            self::resolveSqlParams($modelObj, $queryParams);
            $requestData = $modelObj->get();
            // 查询关系参数
            self::resolveRelations($requestData, $relations);
            // $requestData->load($relations);
        }
        return $requestData;
    }

    /**
     * 获得model所有记录--分批获取[推荐]
     *
     * @param object $modelObj 当前模型对象
     * @param array $queryParams 查询条件   有count下标则是查询数量--是否是查询总数
     * @param array $relations 要查询的与其它表的关系
     * @return object 数据对象
     * @author zouyan(305463219@qq.com)
     */
    public static function getAllModelDatas(&$modelObj, $queryParams, $relations){
        /*
        $queryParams = [
            'where' => [
                // ['id', '1'],
                // ['phonto_name', 'like', '%知的标题1%']
            ],
            'orderBy' => ['id'=>'desc','company_id'=>'asc'],
        ];
        $relations = ['siteResources','CompanyInfo.proUnits.proRecords','CompanyInfo.companyType'];
        */
        // 有count下标则是查询数量--是否是查询总数
        if(isset($queryParams['count'])){
            if (isset($queryParams['count'])) unset($queryParams['count']);
            if (isset($queryParams['limit'])) unset($queryParams['limit']);
            if (isset($queryParams['offset'])) unset($queryParams['offset']);
            if (isset($queryParams['take'])) unset($queryParams['take']);
            if (isset($queryParams['skip'])) unset($queryParams['skip']);
            if (isset($queryParams['orderBy'])) {
                $limitParams['orderBy'] = $queryParams['orderBy'];
                unset($queryParams['orderBy']);
            }
            // 获得总数量
            self::resolveSqlParams($modelObj, $queryParams);
            return $modelObj->count();
        }

        // 查询条件
        self::resolveSqlParams($modelObj, $queryParams);
        $limit = $queryParams['limit'] ?? 0;
        $offset = $queryParams['offset'] ?? 0;
        $isChunk = true;// 是否分批获取 true 分批获取，false:直接获取
        if($limit > 0 || $offset > 0){
            $isChunk = false;
        }

        if ($isChunk) {// 在处理大量数据集合时能够有效减少内存消耗
            $requestData = collect([]);
            $modelObj->chunk(500, function ($flights) use (&$requestData, $relations) {
                self::resolveRelations($flights, $relations);
                // $flights->load('siteResources');

                $requestData= $requestData->concat($flights);
                /*
                  foreach ($flights as $flight) {
                      //
                  }
                */
            });
        } else {
            $requestData = $modelObj->get();
            // 查询关系参数
            self::resolveRelations($requestData, $relations);
            // $requestData->load($relations);

            //return $infos;
        }
        return $requestData;
    }


    /**
     * 获得指定条件的多条数据
     *
     * @param int 选填 $page 当前页page [默认1]
     * @param int 选填 $pagesize 每页显示的数量 [默认10]
     * @param int 选填 $total 总记录数,优化方案：传<=0传重新获取总数[默认0];=-5:只统计条件记录数量，不返回数据
     * @param string 选填 $queryParams 条件数组/json字符
     * @param string 选填 $relations 关系数组/json字符
     * @return array 数据
        $listData = [
            'pageSize' => $pagesize,
            'page' => $page,
            'total' => $total,
            'totalPage' => ceil($total/$pagesize),
            'dataList' => $requestData,
        ];
     * @author zouyan(305463219@qq.com)
     */
    public static function getModelListDatas(&$modelObj,  $page = 1, $pagesize = 10, $total = 0, $queryParams = [], $relations = [] ){
        // 偏移量
        $offset = ($page-1) * $pagesize;

        $limitParams = [
            'limit' => $pagesize,
            // 'take' => $pagesize,
            'offset' => $offset,
            // 'skip' => $offset,
        ];
        /*
        $queryParams = [
            'where' => [
                ['id', '>', '0'],
                //  ['phonto_name', 'like', '%知的标题1%']
            ],
            'orderBy' => ['id'=>'desc','company_id'=>'asc'],
            // 'limit' => $pagesize,
            // 'take' => $pagesize,
            // 'offset' => $offset,
            // 'skip' => $offset,
        ];
        $relations = ['siteResources','CompanyInfo.proUnits.proRecords','CompanyInfo.companyType'];
        */
        $needDataList = true;
        if ($total <= 0){ // 需要获得总页数
            if($total == -5){
                $needDataList = false;
            }
            if (isset($queryParams['limit'])) unset($queryParams['limit']);
            if (isset($queryParams['offset'])) unset($queryParams['offset']);
            if (isset($queryParams['take'])) unset($queryParams['take']);
            if (isset($queryParams['skip'])) unset($queryParams['skip']);
            if (isset($queryParams['orderBy'])) {
                $limitParams['orderBy'] = $queryParams['orderBy'];
                unset($queryParams['orderBy']);
            }

            // 获得总数量
            self::resolveSqlParams($modelObj, $queryParams);
            $total = $modelObj->count();
        } else {
            $limitParams = array_merge($queryParams,$limitParams);
        }
        $requestData = [];
        if($needDataList) {

            // 获得数据
            self::resolveSqlParams($modelObj, $limitParams);
            $requestData = $modelObj->get();

            // 获得关联系关系
            self::resolveRelations($requestData, $relations);
        }
        $listData = [
            'pageSize' => $pagesize,
            'page' => $page,
            'total' => $total,
            'totalPage' => ceil($total/$pagesize),
            'dataList' => $requestData,
        ];
        return $listData;
    }

    public static function getInfoById(&$modelObj, $id, $selectParams, $relations = ''){
        if (!empty($selectParams) && is_array($selectParams))  $modelObj = $modelObj::select($selectParams);

        $requestData = $modelObj->find($id);
        // 查询关系参数
        self::resolveRelations($requestData, $relations);
        return $requestData;
    }

    // 根据条件，获得单条记录数据 1:返回一维数组,>1 返回二维数组
    //  $pagesize 每页显示的数量 [默认1]
    public static function getInfoByQuery(&$modelObj, $pagesize = 1, $queryParams = [], $relations = [] ){
        $listData = self::getModelListDatas($modelObj,  1, $pagesize, $pagesize, $queryParams, $relations);
        $dataList = $listData['dataList'] ?? [];
        if($pagesize > 1) return $dataList;
        return $dataList[0] ?? [];
    }

    public static function del(&$modelObj, $queryParams){
        // 查询条件
        self::resolveSqlParams($modelObj, $queryParams);
        $requestData = $modelObj->delete();
        return $requestData;
    }

    // 根据id，删除记录 id,单条记录或 多条[,号分隔]
    public static function delByIds(&$modelObj, $id){
        $queryParams =[// 查询条件参数
            'where' => [
                // ['id', $id],
                // ['company_id', $company_id]
            ]
        ];
        if (strpos($id, ',') === false) { // 单条
            array_push($queryParams['where'],['id', $id]);
        }else{
            $queryParams['whereIn']['id'] = explode(',',$id);
        }
        return static::del($modelObj, $queryParams);
    }

    // $synces  格式 [ '关系方法名' =>[关系id,...],...可多个....]
    public static function sync(&$modelObj, $id, $synces){
        $requestData = $modelObj->find($id);
        // 同步修改关系 TODO ；以后改为事务
        DB::beginTransaction();
        $successRels = [
            'success' => [],
            'fail' => [],
        ];
        foreach($synces as $rel => $relIds){
            try {
                $requestData->{$rel}()->sync($relIds);
                array_push($successRels['success'],$relIds);
            } catch ( \Exception $e) {
                DB::rollBack();
                array_push($successRels['fail'],[ 'ids'=> $relIds,'msg'=>$e->getMessage() ]);
                throws('同步关系[' . $rel . ']失败；信息[' . $e->getMessage() . ']');
                // throws($e->getMessage());

            }
        }
        DB::commit();
        return $successRels;
    }


    // $detaches 可多个 ;格式 [ '关系方法名' =>关系id数组[1,2,3] 或空数组[](全部移除), ...]
    public static function detach(&$modelObj, $id, $detaches){
        $requestData = $modelObj->find($id);
        // 同步修改关系 TODO ；以后改为事务
        DB::beginTransaction();
        $successRels = [
            'success' => [],
            'fail' => [],
        ];
        foreach($detaches as $rel => $relIds){
            try {
                if(empty($relIds)){
                    $requestData->{$rel}()->detach();
                }else{
                    $requestData->{$rel}()->detach($relIds);
                }
                array_push($successRels['success'],$rel);
            } catch ( \Exception $e) {
                DB::rollBack();
                array_push($successRels['fail'],[$rel =>$e->getMessage() ]);
                throws('移除关系[' . $rel . ']失败；信息[' . $e->getMessage() . ']');
                // throws($e->getMessage());

            }
        }
        DB::commit();
        return $successRels;
    }

    //创建对象
    public  static function create(&$modelObj, $dataParams){
        return $modelObj->create($dataParams);
    }

    // 批量新加-data只能返回成功true:失败:false
    public static function insertData(&$modelObj, $dataParams){
        $requestData =$modelObj->insert($dataParams);//一维或二维数组;只返回true:成功;false：失败
        // $requestData =$modelObj->insertGetId($dataParams,'id');//只能是一维数组，返回id值
        return $requestData;
    }

    /**
     * 批量新加--返回新加的主键值-一维数组
     *
     * @param object $modelObj 对象
     * @param array $dataParams 需要新的数据-- 二维数组
     * @param string $primaryKey 默认自增列被命名为 id，如果你想要从其他“序列”获取ID
     * @return array 返回新加的主键值-一维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function insertGetId(&$modelObj, $dataParams, $primaryKey = 'id'){
        if(empty($primaryKey)) $primaryKey = 'id';
        $newIds = [];
        DB::beginTransaction();
        // 保存记录
        try {
            foreach($dataParams as $info){
                $newId =$modelObj->insertGetId($info,$primaryKey);//只能是一维数组，返回id值
                array_push($newIds,$newId);
            }
        } catch ( \Exception $e) {
            DB::rollBack();
            throws('保存失败；信息[' . $e->getMessage() . ']');
            // throws($e->getMessage());
        }
        DB::commit();
        return $newIds;
    }


    // 通过id修改记录
    public static function saveById(&$modelObj, $dataParams, $id){
        // $requestData = $modelObj->find($id);
        $modelObj = $modelObj->find($id);

        foreach($dataParams as $field => $val){
            // $requestData->{$field} = $val;
            $modelObj->{$field} = $val;
        }
        // $result = $requestData->save();
        $result = $modelObj->save();
        return $result;
    }

    /**
     * 批量修改设置-- 根据主键
     *
     * @param string $Model_name model名称
     * @param string $primaryKey 主键字段,默认为id
     * @param string $dataParams 主键及要修改的字段值 二维数组 数组/json字符
     * @return array
     * @author zouyan(305463219@qq.com)
     */
    public static function batchSave($modelName, $dataParams = [], $primaryKey = 'id'){
        if(empty($primaryKey)) $primaryKey = 'id';

        $className = "App\\Models\\" .$modelName;
        if (! class_exists($className )) {
            throws('参数[Model_name]不正确！');
        }

        $successRels = [
            'success' => [],
            'fail' => [],
        ];
        DB::beginTransaction();
        foreach($dataParams as $info){
            // 保存记录
            $id = $info[$primaryKey] ?? '';
            try {
                $temObj = $className::find($id);
                unset($info[$primaryKey]);
                if(empty($info)){
                    continue;
                }
                foreach($info as $field => $val){
                    $temObj->{$field} = $val;
                }
                $res = $temObj->save();
                array_push($successRels['success'],[$id => $res]);
            } catch ( \Exception $e) {
                DB::rollBack();
                array_push($successRels['fail'],[ 'id'=> $id,'msg'=>$e->getMessage() ]);
                throws('修改[' . $id . ']失败；信息[' . $e->getMessage() . ']');
                // throws($e->getMessage());
            }
        }
        DB::commit();
        return $successRels;
    }

    // 按条件修改
    public static function updateQuery(&$modelObj, $dataParams, $queryParams){
        // 查询条件
        self::resolveSqlParams($modelObj, $queryParams);
        $requestData = $modelObj->update($dataParams);
        return $requestData;
    }

    //自增自减,通过条件-data操作的行数
    public static function saveDecInc(&$modelObj, $incDecField, $incDecVal = 1, $incDecType = 'inc', $queryParams = [], $modifFields = []){
        // 查询条件
        self::resolveSqlParams($modelObj, $queryParams);

        $operate = 'decrement'; // 减
        if($incDecType == 'inc'){
            $operate = 'increment';// 增
        }
        if(is_array($modifFields) && (!empty($modifFields))){
            $requestData = $modelObj->{$operate}($incDecField, $incDecVal,$modifFields);
        }else{
            $requestData = $modelObj->{$operate}($incDecField, $incDecVal);
        }
        return $requestData;
    }

    /**
     * 批量修改设置
     *
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
    public static function saveDecIncBatchByPrimaryKey($dataParams ){

        $successRels = [];
        DB::beginTransaction();
        foreach($dataParams as $info){
            try {
                $primaryVal = $info['primaryVal'] ?? '';
                if(empty($primaryVal)){
                    throws('参数[primaryVal]不能为空！');
                }
                // 获得对象
                $modelName = $info['Model_name'] ?? '';
                Tool::judgeEmptyParams('Model_name', $modelName);

                $className = "App\\Models\\" .$modelName;
                if (! class_exists($className )) {
                    throws('参数[Model_name]不正确！');
                }

                // 增减类型 inc 增 ;dec 减[默认]
                $incDecType = $info['incDecType'] ?? 'dec';

                // 增减字段
                $incDecField = $info['incDecField'] ?? '';
                Tool::judgeEmptyParams('incDecField', $incDecField);
                // 增减值
                $incDecVal = $info['incDecVal'] ?? '';
                if(!is_numeric($incDecVal)){
                    throws('参数[incDecVal]必须是数字!');
                }
                // 修改的其它字段 -没有，则传空数组json
                $modifFields = $info['modifFields'] ?? [];
                // jsonStrToArr($modifFields , 1, '参数[modifFields]格式有误!');


                // 保存记录
                $operate = 'decrement'; // 减
                if($incDecType == 'inc'){
                    $operate = 'increment';// 增
                }
                $temObj = $className::find($primaryVal);
                if(is_array($modifFields) && (!empty($modifFields))){
                    $res = $temObj->{$operate}($incDecField, $incDecVal,$modifFields);
                }else{
                    $res = $temObj->{$operate}($incDecField, $incDecVal);
                }
                array_push($successRels,$res);
            } catch ( \Exception $e) {
                DB::rollBack();
                throws('保存[' . $primaryVal . ']失败；信息[' . $e->getMessage() . ']');
                // throws($e->getMessage());
            }
        }
        DB::commit();
        return $successRels;
    }



    /**
     * 需要使用历史字段时，获得历史id
     *
     * @param object $mainObj 主表对象
     * @param mixed $primaryVal 主表对象主键值
     * @param object $historyObj 历史表对象
     * @param string $historyTable 历史表名字
     * @param array $historySearch 历史表查询字段[一维数组][一定要包含主表id的] +  版本号(不用传，自动会加上) 格式 ['字段1'=>'字段1的值','字段2'=>'字段2的值' ... ]
     * @param array $ignoreFields 忽略都有的字段中，忽略历史表中的记录 [一维数组] 格式 ['字段1','字段2' ... ]
     * @return int 历史表id
     * @author zouyan(305463219@qq.com)
     */
    public static function getHistory(&$mainObj, $primaryVal, &$historyObj, $HistoryTableName, $historySearch, $ignoreFields ){
        // 获得员操作员工信息
        $mainObj = $mainObj::find($primaryVal);
        if(empty($mainObj)){
            throws("原记录[" . $primaryVal  . "] 不存在");
        }
        $versionNum = $mainObj->version_num;
        // 获得所有字段
        $historyColumns = Schema::getColumnListing($HistoryTableName);

        // 历史表需要保存的字段
        $historyData = [];// 要保存的历史记录
        $historySearchConditon = [];// 历史表查询字段
        $ignoreFields = array_merge($ignoreFields,['id','updated_at']);
        foreach($historyColumns as $field){
            if(isset($mainObj->$field) && !in_array($field,$ignoreFields) ){
                $historyData[$field] = $mainObj->$field;
            }
            // 去掉不在历史表中的历史表查询字段
            if(isset($historySearch[$field])){
                $historySearchConditon[$field] = $historySearch[$field] ;
            }
        }
        if(isset($mainObj->updated_at)){// 记录历史表记录是主表的修改时间
            $historyData['created_at'] = $mainObj->updated_at;
        }

        // 查询加入版本号
        $historySearchConditon["version_num"] = $versionNum;
        // 查找历史表当前版本
        self::firstOrCreate($historyObj, $historySearchConditon, $historyData );
        // $historyObj = $historyObj::firstOrCreate($historySearchConditon, $historyData);
        return $historyObj->id ;
    }

    /**
     * 对比主表和历史表是否相同，相同：不更新版本号，不同：版本号+1
     *
     * @param object $mainObj 主表对象
     * @param mixed $primaryVal 主表对象主键值
     * @param object $historyObj 历史表对象
     * @param string $historyTable 历史表名字
     * @param array $historySearch 历史表查询字段[一维数组][一定要包含主表id的值] +  版本号(不用传，自动会加上) 格式 ['字段1'=>'字段1的值','字段2'=>'字段2的值' ... ]
     * @param array $ignoreFields 忽略都有的字段中，忽略历史中的记录 [一维数组] - 必须会有 [历史表中对应主表的id字段] 格式 ['字段1','字段2' ... ]
     * @param int $forceIncVersion 如果需要主表版本号+1,是否更新主表 1 更新 ;0 不更新
     * @return array 不同字段的内容 数组 [ '字段名' => ['原表中的值','历史表中的值']]; 空数组：不用版本号+1;非空数组：版本号+1
     * @author zouyan(305463219@qq.com)
     */
    public static function compareHistoryOrUpdateVersion(&$mainObj, $primaryVal, &$historyObj, $HistoryTableName, $historySearch, $ignoreFields, $forceIncVersion = 1)
    {
        $diffArr = []; // 记录不同的字段及值

        // 获得员操作员工信息
        $mainObj = $mainObj::find($primaryVal);
        if(empty($mainObj)){
            throws("原记录[" . $primaryVal  . "] 不存在");
        }
        $versionNum = $mainObj->version_num;// 当前记录版本号

        // 获得所有字段-历史表
        $historyColumns = Schema::getColumnListing($HistoryTableName);

        // 过滤查询条件中不在字段中的
        $historySearchConditon = [];
        // 去掉不在历史表中的历史表查询字段
        foreach($historyColumns as $field){
            if(isset($historySearch[$field])){
                $historySearchConditon[$field] = $historySearch[$field] ;
            }
        }
        // 查询条件加上版本号
        $historySearchConditon["version_num"] = $versionNum;

        // 查询条件转为二维数组
        $where = [];
        foreach($historySearchConditon as $k => $v){
            $where[] = [$k ,"=" , $v];
        }

        // 查找当前版本在历史表中的记录
        $historyObj = $historyObj::where($where)->limit(1)->get();
        $historyInfoObj = $historyObj[0] ?? [] ;
        if(empty($historyInfoObj)) return $diffArr;// 没有历史记录,不用更新版本

        // 忽略的比较字段
        $ignoreFields = array_merge($ignoreFields,['id', 'created_at', 'updated_at', 'version_num', 'staff_id', 'operate_staff_id_history']);

        // 比较字段
        foreach($historyColumns as $field){
            if( in_array($field,$ignoreFields) ) continue;
            if($mainObj->$field != $historyInfoObj->$field){ // 字段值不同
                $diffArr[$field] = [$mainObj->$field,$historyInfoObj->$field];
            }
        }
        if(!empty($diffArr)){// 有不同的值，则需要版本号+1
            if ($forceIncVersion) {
                $mainObj->version_num++;
                $mainObj->save();
            }
            return $diffArr;
        }
        return $diffArr;
    }

   /**
     * 查找记录,或创建新记录[没有找到] - $searchConditon +  $updateFields 的字段,
     *
     * @param object $mainObj 主表对象
     * @param array $searchConditon 查询字段[一维数组]
     * @param array $updateFields 表中还需要保存的记录 [一维数组] -- 新建表时会用
     * @return object $mainObj 表对象[一维]
     * @author zouyan(305463219@qq.com)
     */
    public static function firstOrCreate(&$mainObj, $searchConditon, $updateFields )
    {
        $mainObj = $mainObj::firstOrCreate($searchConditon, $updateFields);
        return $mainObj;
    }

    /**
     * 已存在则更新，否则创建新模型--持久化模型，所以无需调用 save()- $searchConditon +  $updateFields 的字段,
     *
     * @param object $mainObj 主表对象
     * @param array $searchConditon 查询字段[一维数组]
     * @param array $updateFields 表中还需要保存的记录 [一维数组] -- 新建表时会用
     * @return object $mainObj 表对象[一维]
     * @author zouyan(305463219@qq.com)
     */
    public static function updateOrCreate(&$mainObj, $searchConditon, $updateFields )
    {
        $mainObj = $mainObj::updateOrCreate($searchConditon, $updateFields);
        return $mainObj;
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
    public static function getAttr(&$modelObj, $attrName, $isStatic = 0){
        return Tool::getAttr($modelObj, $attrName, $isStatic);
//        if ( !property_exists($modelObj, $attrName)) {
//            throws("未定义[" . $attrName  . "] 属性");
//        }
//        // 静态
//        if($isStatic == 1) return $modelObj::${$attrName};
//        return $modelObj->{$attrName};
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
    public static function exeMethod(&$modelObj, $methodName, $params = []){
        return Tool::exeMethod($modelObj, $methodName, $params);
//        if(!method_exists($modelObj,$methodName)){
//            throws("未定义[" . $methodName  . "] 方法");
//        }
//        return $modelObj->{$methodName}(...$params);
    }

    /**
     * 获得 id=> 键值对 或 查询的数据
     *
     * @param array $kv ['key' => 'id字段', 'val' => 'name字段'] 或 [] 返回查询的数据
     * @param array $select 需要获得的字段数组   ['id', 'area_name'] ; 参数 $kv 不为空，则此参数可为空数组
     * @param array $queryParams 查询条件
     * @author zouyan(305463219@qq.com)
     */
    public static function getKeyVals(&$modelObj, $kv = [], $select =[], $queryParams = []){
//        $areaCityList = $modelObj::select(['id', 'area_name'])
//            ->orderBy('sort_num', 'desc')->orderBy('id', 'desc')
//            ->where([
//                ['company_id', '=', $company_id],
//                ['area_parent_id', '=', $area_parent_id],
//            ])
//            ->get()->toArray();
//        if(!$is_kv) return $areaCityList;
//        return Tool::formatArrKeyVal($areaCityList, 'id', 'area_name');
        if ( isset($kv['key']) && isset($kv['val']) ) {
            if(!in_array($kv['key'], $select)) array_push($select, $kv['key']);
            if(!in_array($kv['val'], $select)) array_push($select, $kv['val']);
        }
        if (!empty($select) && is_array($select))  $modelObj = $modelObj::select($select);
        // if (!empty($where) && is_array($where))  $modelObj = $modelObj->where($where);

        self::resolveSqlParams($modelObj, $queryParams);

        $areaCityList = $modelObj->get()->toArray();
        if ( !isset($kv['key']) || !isset($kv['val']) ) return $areaCityList;
        return Tool::formatArrKeyVal($areaCityList, $kv['key'], $kv['val']);
    }
}
