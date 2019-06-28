<?php
//
namespace App\Business\API;

use App\Business\API\RunBuy\StaffAPIBusiness;
use App\Business\API\RunBuy\StaffHistoryAPIBusiness;
use App\Services\Tool;

class BaseAPIBusiness
{
    public static $model_name = '';
    public static $APIRequestName = '';// 具体的api request请求类名称
    public static $table_name = '';// 表名称

    // 根据API具体名称，返回API对象
    // $modelName Business\Controller 目录下 [Controller|API|Block]\[API|Block|DB]\CTDBCity  [Business]  部分
    public static function getAPIObjByModelName($modelName, &$modelObj = null){
        // App\Services\Request\API\Sites
        $className = "App\\Services\\Request\\API\\" . $modelName ;// . 'Business';
        if (! class_exists($className )) {
            throws('参数[Model_name]不正确！');
        }
        $modelObj = new $className();
        return $modelObj;
    }

    // 实例化API对象
    public static function GetAPIObj(&$modelObj = null){
        if (! is_object($modelObj)) {
//            $modelName = CommonRequest::get($request, 'Model_name');
//            Tool::judgeEmptyParams('Model_name', $modelName);
            $modelName = static::$APIRequestName;
//            $className = "App\\Business\\DB\\RunBuy\\LrChinaCityDBBusiness" ;
//            if (! class_exists($className )) {
//                throws('参数[Model_name]不正确！');
//            }
//            $modelObj = new $className();
            static::getAPIObjByModelName($modelName, $modelObj );
        }
        return $modelObj;
    }

    /**
     * 获得列表数据--所有数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param string $model_name 模型名称  为空，则用对象的属性
     *
     *
     * @param string $company_id 公司id
     * @param array $pageParams 翻页的三个关键参数
        [
            'page' => $page,// 当前页
            'pagesize' => $pagesize,// 每页显示数量
            'total' => $total,// 当前页
        ]
     * @param string $model_name 模型名称
     * @param string $queryParams 条件数组/json字符
     * @param string $relations 关系数组/json字符
     * @param int $oprateBit 操作类型位 1:获得所有的; 2 分页获取[同时有1和2，2优先]；4 返回分页html翻页代码
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
        $result = [
            'data_list'=>$resultDatas,//array(),//数据二维数组
            'total'=>$total,//总记录数 0:每次都会重新获取总数 ;$total :则>0总数据不会重新获取[除第一页]
            'page'=> $page,// 当前页
            'pagesize'=> $pagesize,// 每页显示的数量
            'totalPage'=> $totalPage,// 总页数
            //  'pageInfo' => showPage($totalPage,$page,$total,12,1),
            ];
     * @author zouyan(305463219@qq.com)
     */
    public static function getBaseListData($company_id, $pageParams = [], $model_name = '', $queryParams = '',$relations = '', $oprateBit = 2 + 4,  $notLog = 0){
        // $company_id = $controller->company_id;
        if(empty($model_name)) $model_name = static::$model_name;
        // 获得翻页的三个关键参数
        // $pageParams = CommonRequest::getPageParams($request);
        // 关键字

        list($page, $pagesize, $total) = array_values($pageParams);
        /*
        $queryParams = [
            'where' => [
                ['company_id', $company_id],
                //['mobile', $keyword],
            ],
//            'select' => [
//                'id','company_id','real_name'
//            ],
            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
        ];// 查询条件参数
        $relations = ['CompanyInfo'];// 关系
        */
        $result = [];
        static::GetAPIObj($APIObj);
        if(  ($oprateBit & 2) == 2 ){ //2 分页获取[同时有1和2，2优先]；
            $result = $APIObj::ajaxGetList($model_name, $pageParams, $company_id,$queryParams ,$relations, $notLog);
        }else if(  ($oprateBit & 1) == 1 ){ //1:获得所有的;
            $result = $APIObj::ajaxGetAllList($model_name, $pageParams, $company_id,$queryParams ,$relations, $notLog );
        }
        if(isset($result['dataList'])){
            $resultDatas = $result['dataList'];
            $pagesize = $result['pageSize'] ?? $pagesize;
            $page = $result['page'] ?? $page;

            if ($total <= 0 ) {
                $total = $result['total'] ?? $total;
            }

            // $totalPage = $result['totalPage'] ?? 0;
        }else{
            $resultDatas = $result;
            //if ($total <= 0 ) {
            if(is_array($resultDatas)){
                $total = count($resultDatas);
            }elseif(is_numeric($resultDatas)){
                $total = $resultDatas;
                $resultDatas = [];
            }else{
                $resultDatas = [];
            }
            //}
            if($total > 0) $pagesize = $total;
        }
        // 处理图片地址
        // Tool::resoursceUrl($resultDatas);
        $totalPage = ceil($total/$pagesize);

//        $data_list = [];
//        foreach($resultDatas as $k => $v){
////            // 部门名称
////            $resultDatas[$k]['department_name'] = $v['staff_department']['department_name'] ?? '';
////            if(isset($resultDatas[$k]['staff_department'])) unset($resultDatas[$k]['staff_department']);
////            // 小组名称
////            $resultDatas[$k]['group_name'] = $v['staff_group']['department_name'] ?? '';
////            if(isset($resultDatas[$k]['staff_group'])) unset($resultDatas[$k]['staff_group']);
////            // 职位
////            $resultDatas[$k]['position_name'] = $v['staff_position']['position_name'] ?? '';
////            if(isset($resultDatas[$k]['staff_position'])) unset($resultDatas[$k]['staff_position']);
//
//            $data_list[] = [
//                'id' => $v['id'] ,
//                'company_id' => $v['company_id'] ,
//                'company_name' => $v['company_info']['company_name'] ?? '',//  企业名称
//                //'resource_url' => $v['site_resources'][0]['resource_url'] ?? '' ,
//                //'resource_name' => $v['site_resources'][0]['resource_name'] ?? '' ,
//                'type_name' => $v['type_name'] ,
//                'created_at' => $v['created_at'],
//            ];
//        }
        $result = [
            'has_page'=> $totalPage > $page,
            'data_list'=>$resultDatas,//array(),//数据二维数组
            'total'=>$total,//总记录数 0:每次都会重新获取总数 ;$total :则>0总数据不会重新获取[除第一页]
            'page'=> $page,// 当前页
            'pagesize'=> $pagesize,// 每页显示的数量
            'totalPage'=> $totalPage,// 总页数
            'pageInfo' => "",//showPage($totalPage,$page,$total,12,1),
        ];
        if(  ($oprateBit & 4) == 4 ){
            $result['pageInfo'] = showPage($totalPage,$page,$total,12,1);
        }
        return $result;

    }

    /**
     * 删除单条数据--兼容批量删除
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     *
     * @param string $company_id 公司id
     * @param int $id 删除id
     * @param string $model_name 模型名称 为空，则用对象的属性
     * @param int $notLog 是否需要登陆 0需要1不需要 2已经判断权限，不用判断权限
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function delAjaxBase($company_id, $id, $model_name = '', $notLog = 0){

        if(empty($model_name)) $model_name = static::$model_name;

        // $id = CommonRequest::get($request, 'id');
        Tool::dataValid([["input"=>$id,"require"=>"true","validator"=>"","message"=>'参数id值不能为空']]);

        // $company_id = $controller->company_id;

        // static::GetAPIObj($APIObj);

        // 判断权限
        if(($notLog & 2) == 2 ) {
            $notLog = $notLog - 2 ;
        }else{
//            $judgeData = [
//                'company_id' => $company_id,
//            ];
//            $relations = '';
//            $APIObj::judgePower($id, $judgeData, $model_name, $company_id, $relations, $notLog);
        }

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

        // $resultDatas = $APIObj::ajaxDelApi($model_name, $company_id , $queryParams, $notLog);
        $resultDatas = static::delByQuery($company_id, $model_name, $queryParams, $notLog);
        return $resultDatas;
    }

    /**
     * 删除单条数据---总系统类表--兼容批量删除
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     *
     * @param string $company_id 公司id
     * @param int $id 删除id
     * @param string $model_name 模型名称 为空，则用对象的属性
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function delSysAjaxBase($company_id, $id, $model_name = '', $notLog = 0){

        // $id = CommonRequest::getInt($request, 'id');
        // $company_id = $controller->company_id;

        if(empty($model_name)) $model_name = static::$model_name;

        // 判断权限
//        $judgeData = [
//            'company_id' => $company_id,
//        ];
//        $relations = '';
//        CommonBusiness::judgePower($id, $judgeData, $model_name, $company_id, $relations, $notLog);

        $queryParams =[// 查询条件参数
            'where' => [
//                ['id', $id],
//                ['company_id', $company_id]
            ]
        ];
        if (strpos($id, ',') === false) { // 单条
            array_push($queryParams['where'],['id', $id]);
        }else{
            $queryParams['whereIn']['id'] = explode(',',$id);
        }
        // static::GetAPIObj($APIObj);
        // $resultDatas = $APIObj::ajaxDelApi($model_name, $company_id , $queryParams, $notLog);
        $resultDatas = static::delByQuery($company_id, $model_name, $queryParams, $notLog);
        return $resultDatas;
    }


    /**
     * 根据条件删除记录
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     *
     * @param string $company_id 公司id
     * @param int $id 删除id
     * @param string $model_name 模型名称 为空，则用对象的属性
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function delByQuery($company_id, $model_name = '', $queryParams = [], $notLog = 0){
        if(empty($model_name)) $model_name = static::$model_name;

        static::GetAPIObj($APIObj);
        $resultDatas = $APIObj::ajaxDelApi($model_name, $company_id , $queryParams, $notLog);
        return $resultDatas;
    }

    /**
     * 根据id获得单条数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     *
     *
     * @param string $company_id 公司id
     * @param int $id id
     * @param string $model_name 模型名称 为空，则用对象的属性
     * @param array $selectParams 查询字段参数--一维数组
     * @param json/array $relations 要查询的与其它表的关系
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function getInfoDataBase($company_id, $id, $model_name = '', $selectParams = '', $relations = [], $notLog = 0){
        // $company_id = $controller->company_id;
        // $relations = '';
        if(empty($model_name)) $model_name = static::$model_name;
        static::GetAPIObj($APIObj);
        return $APIObj::getinfoApi($model_name, $selectParams, $relations, $company_id , $id, $notLog);
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
    public static function getInfoByQuery($modelName, $companyId = null,$queryParams='' ,$relations = '', $notLog = 0)
    {
        if(empty($modelName)) $modelName = static::$model_name;
        static::GetAPIObj($APIObj);
        return $APIObj::getInfoByQuery($modelName, $companyId,$queryParams, $relations, $notLog);
    }

    /**
     * 根据model的条件获得一条详情记录 - pagesize 1:返回一维数组,>1 返回二维数组  -- 推荐有这个按条件查询详情
     *
     * @param object $modelObj 当前模型对象
     * @param int $companyId 企业id
     * @param int $pagesize 想获得的记录数量 1 , 2 。。 默认1
     * @param string $queryParams 条件数组/json字符
     * @param string $relations 关系数组/json字符
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function getInfoQuery($modelName, $companyId = null, $pagesize = 1,$queryParams='' ,$relations = '', $notLog = 0)
    {
        if(empty($modelName)) $modelName = static::$model_name;
        static::GetAPIObj($APIObj);
        return $APIObj::getinfoQuery($modelName, $queryParams, $relations, $companyId, $pagesize, $notLog);
    }

    /**
     * 根据id新加或修改单条数据-id 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     *
     * @param string $company_id 公司id
     * @param int $id id
     * @param array $saveData 要保存或修改的数组
     * @param string $model_name 模型名称 为空，则用对象的属性
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  mixed 单条数据 - -维数组 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     * @author zouyan(305463219@qq.com)
     */
    public static function replaceByIdBase($company_id, &$id, $saveData ,$model_name = '', $notLog = 0){
        // $company_id = $controller->company_id;
        if(empty($model_name)) $model_name = static::$model_name;
        static::GetAPIObj($APIObj);
        if($id <= 0){// 新加
            $resultDatas = $APIObj::createApi($model_name, $saveData, $company_id, $notLog);
            $id = $resultDatas['id'] ?? 0;
        }else{// 修改
            $resultDatas = $APIObj::saveByIdApi($model_name, $id, $saveData, $company_id, $notLog);
        }
        return $resultDatas;
    }

    /**
     * 通过id同步修改关系接口
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     *
     * @param string $company_id 公司id
     * @param int $id id
     * @param array $syncParams 要保存或修改的数组
     * @param string $model_name 模型名称 为空，则用对象的属性
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  mixed 单条数据 - -维数组 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     * @author zouyan(305463219@qq.com)
     */
    public static function saveSyncById($company_id, &$id, $syncParams, $model_name = '', $notLog = 0){
        // $company_id = $controller->company_id;
        if(empty($model_name)) $model_name = static::$model_name;
        static::GetAPIObj($APIObj);
        return $APIObj::saveSyncByIdApi($model_name, $id, $syncParams, $company_id, $notLog);
    }

    /**
     * 通过id移除关系接口
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     *
     * @param string $company_id 公司id
     * @param string $model_name 模型名称 为空，则用对象的属性
     * @param array $syncParams 要保存或修改的数组
     * @param int $id id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  mixed 单条数据 - -维数组 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     * @author zouyan(305463219@qq.com)
     */
    public static function detachById($company_id, $modelName, $id, $detachParams, $notLog = 0){
        // $company_id = $controller->company_id;
        if(empty($model_name)) $model_name = static::$model_name;
        static::GetAPIObj($APIObj);
        return $APIObj::detachByIdApi($modelName, $id, $detachParams, $company_id, $notLog);
    }


    /**
     * 获得历史员工记录id, 可缓存
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     *
     * @param string $company_id 公司id
     * @param int $operate_staff_id 操作员工id
     * @param int $cache_sel //是否强制不缓存 1:缓存读,读到则直接返回;2缓存数据
     * @return  int 历史员工记录id
     * @author zouyan(305463219@qq.com)
     */
    public static function getStaffHistoryId($company_id, $operate_staff_id, $cache_sel = 1 + 2){
        // $company_id = $controller->company_id;
        // $operate_staff_id = $controller->operate_staff_id;
        // 获得 redis缓存数据  ; 1:缓存读,读到则直接返回
        // if( ($controller->cache_sel & 1) == 1){
        if( ($cache_sel & 1) == 1 ||  ($cache_sel & 2) == 2){
            $cachePre = 'operate_staff_id_history' ;// __FUNCTION__;// 缓存前缀
            $cacheKey = '';// 缓存键[没算前缀]
            $paramKeyValArr = [$company_id, $operate_staff_id];//[$company_id, $operate_no];// 关键参数  $request->input()
            // $cacheResult = $controller->getCacheData($cachePre,$cacheKey, $paramKeyValArr,2, 1);
            $cacheResult = Tool::getCacheData($cachePre, $cacheKey, $paramKeyValArr, 2, 1);
            if($cacheResult !== false && ($cache_sel & 1) == 1) {
                return $cacheResult;
            }
        }

        // 获得操作员工历史记录id
        $operate_staff_id_history = static::getHistoryId(StaffAPIBusiness::$model_name, $operate_staff_id
            , StaffHistoryAPIBusiness::$model_name, StaffHistoryAPIBusiness::$table_name, ['staff_id' => $operate_staff_id], []
            , $company_id, 0);
        // ['company_id' => $company_id,'staff_id' => $operate_staff_id]

        // 缓存数据 10分钟
        // if( ($controller->cache_sel & 2) == 2) {
        if( ($cache_sel & 2) == 2) {
            // $controller->setCacheData($cachePre, $cacheKey, $operate_staff_id_history, 10*60, 2);
            Tool::cacheData($cachePre, $cacheKey, $operate_staff_id_history, 10*60, 2);
        }
        return $operate_staff_id_history;

    }


    /**
     * 根据id新加或修改单条数据-id 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     *
     * @param string $company_id 公司id
     * @param int $operate_staff_id 操作员工id
     * @param array $saveData 需要操作的数组 [一维或二维数组]
     * @param int $cache_sel //是否强制不缓存 1:缓存读,读到则直接返回;2缓存数据
     * @return  mixed 单条数据 - -维数组 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     * @author zouyan(305463219@qq.com)
     */
    public static function addOprate($company_id, $operate_staff_id, &$saveData, $cache_sel = 1 + 2){
        // $company_id = $controller->company_id;
        // $operate_staff_id = $controller->operate_staff_id;
        $operate_staff_id_history = static::getStaffHistoryId($company_id, $operate_staff_id, $cache_sel);// self::getStaffHistoryId($request, $controller);
        // 加入操作人员信息
        $oprateArr = [
            'operate_staff_id' => $operate_staff_id,// $controller->operate_staff_id,
            'operate_staff_id_history' => $operate_staff_id_history,// $controller->operate_staff_id_history,
        ];
        $isMultiArr = false; // true:二维;false:一维
        foreach($saveData as $k => $v){
            if(is_array($v)){
                $isMultiArr = true;
            }
            break;
        }
        if($isMultiArr){ //二维

            foreach($saveData as $k => $v){
                $v = array_merge($v, $oprateArr);
                $saveData[$k] = $v;
            }
        }else{// 一维
            $saveData = array_merge($saveData, $oprateArr);
        }
        return $saveData;
    }

    /**
     * 对比主表和历史表是否相同，相同：不更新版本号，不同：版本号+1
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param string $modelName 主表对象名称  为空，则用对象的属性
     * @param mixed $primaryVal 主表对象主键值
     * @param string $historyObj 历史表对象名称
     * @param obj $HistoryTableName 历史表名字
     * @param array $historySearch 历史表查询字段[一维数组][一定要包含主表id的] +  版本号(不用传，自动会加上)  格式 ['字段1'=>'字段1的值','字段2'=>'字段2的值' ... ]
     * @param array $ignoreFields 忽略都有的字段中，忽略主表中的记录 [一维数组] 必须会有 [历史表中对应主表的id字段]  格式 ['字段1','字段2' ... ]
     * @param int $forceIncVersion 如果需要主表版本号+1,是否更新主表 1 更新 ;0 不更新
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  mixed 历史记录表id
     * @author zouyan(305463219@qq.com)
     */
    public static function compareHistoryOrUpdateVersion($modelName, $primaryVal, $historyObj, $HistoryTableName, $historySearch, $ignoreFields = [], $forceIncVersion= 1, $companyId = null , $notLog = 0){
        if(empty($modelName)) $modelName = static::$model_name;
        static::GetAPIObj($APIObj);
        return $APIObj::compareHistoryOrUpdateVersionApi($modelName, $primaryVal, $historyObj, $HistoryTableName, $historySearch, $ignoreFields, $forceIncVersion, $companyId, $notLog);
    }

    /**
     * 根据主表id，获得对应的历史表id
     *
     * @param string $modelName 主表对象名称  为空，则用对象的属性
     * @param mixed $primaryVal 主表对象主键值
     * @param string $historyObj 历史表对象名称
     * @param obj $HistoryTableName 历史表名字
     * @param array $historySearch 历史表查询字段[一维数组][一定要包含主表id的] +  版本号(不用传，自动会加上) 格式 ['字段1'=>'字段1的值','字段2'=>'字段2的值' ... ]
     * @param array $ignoreFields 忽略都有的字段中，忽略主表中的记录 [一维数组] 格式 ['字段1','字段2' ... ]
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  int 历史记录表id
     * @author zouyan(305463219@qq.com)
     */
    public static function getHistoryId( $modelName, $primaryVal, $historyObj, $HistoryTableName, $historySearch, $ignoreFields = [], $companyId = null , $notLog = 0){
        if(empty($modelName)) $modelName = static::$model_name;
        static::GetAPIObj($APIObj);
        return $APIObj::getHistoryIdApi($modelName, $primaryVal, $historyObj, $HistoryTableName, $historySearch, $ignoreFields, $companyId, $notLog);
    }


    /**
     * 查找记录,或创建新记录[没有找到] - $searchConditon +  $updateFields 的字段,
     *
     * @param string $modelName 主表对象名称  为空，则用对象的属性
     * @param array $searchConditon 查询字段[一维数组]
     * @param array $updateFields 表中还需要保存的记录 [一维数组] -- 新建表时会用
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return array $mainObj 表对象[一维]
     * @author zouyan(305463219@qq.com)
     */
    public static function updateOrCreate($modelName, $searchConditon, $updateFields, $companyId = null , $notLog = 0){
        if(empty($modelName)) $modelName = static::$model_name;
        static::GetAPIObj($APIObj);
        return $APIObj::updateOrCreateApi($modelName, $searchConditon, $updateFields, $companyId, $notLog);
    }

    /**
     * 根据主健批量修改记录
     *
     * @param object $modelObj 当前模型对象   为空，则用对象的属性
     * @param array $saveData 要保存或修改的数组
     * @param string $queryParams 条件数组/json字符
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function saveBathById($modelName, $saveData= [], $primaryKey = 'id', $companyId = null, $notLog = 0 )
    {
        if(empty($modelName)) $modelName = static::$model_name;
        static::GetAPIObj($APIObj);
        return $APIObj::saveBathById($modelName, $saveData, $primaryKey, $companyId, $notLog);
    }

    /**
     * 通过id修改接口
     *
     * @param object $modelObj 当前模型对象   为空，则用对象的属性
     * @param int $id id
     * @param int $companyId 企业id
     * @param array $saveData 要保存或修改的数组
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function saveByIdApi($modelName, $id, $saveData, $companyId = null, $notLog = 0)
    {
        if(empty($modelName)) $modelName = static::$model_name;
        static::GetAPIObj($APIObj);
        return $APIObj::saveByIdApi($modelName, $id, $saveData, $companyId, $notLog);
    }

    /**
     * 判断权限
     *
     * @param array $infoData 记录数组 一维
     * @param array $judgeArr 需要判断的下标及值
     * @author zouyan(305463219@qq.com)
     */
    public static function judgePowerByObj($infoData, $judgeArr = [] )
    {
        static::GetAPIObj($APIObj);
        $APIObj::judgePowerByObj($infoData, $judgeArr);
    }

    /**
     * 判断权限
     *
     * @param int $id id ,多个用,号分隔
     * @param array $judgeArr 需要判断的下标[字段名]及值 一维数组
     * @param string $model_name 模型名称   为空，则用对象的属性
     * @param int $companyId 企业id
     * @param json/array $relations 要查询的与其它表的关系
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return array 一维数组[单条] 二维数组[多条]
     * @author zouyan(305463219@qq.com)
     */
    public static function judgePower($id, $judgeArr = [] , $model_name = '', $company_id = '', $relations = '', $notLog  = 0)
    {
        if(empty($model_name)) $model_name = static::$model_name;
        static::GetAPIObj($APIObj);
        return $APIObj::judgePower($id, $judgeArr, $model_name, $company_id, $relations, $notLog);

    }

    /**
     * 根据条件，获得kv数据
     *
     * @param object $modelName 当前模型对象  为空，则用对象的属性
     * @param array $kvParams 查询的kv字段数据参数数组/json字符  ['key' => 'id字段', 'val' => 'name字段'] 或 [] 返回查询的数据
     * @param array $selectParams 查询字段参数数组/json字符 需要获得的字段数组   ['id', 'area_name'] ; 参数 $kv 不为空，则此参数可为空数组
     * @param array $queryParams 查询条件
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return array kv数组
     * @author zouyan(305463219@qq.com)
     */
    public static function getKVBS($modelName, $kvParams = '', $selectParams = '', $queryParams = '', $companyId = null , $notLog = 0){
        if(empty($modelName)) $modelName = static::$model_name;
        static::GetAPIObj($APIObj);
        return $APIObj::getKVApi($modelName, $kvParams, $selectParams, $queryParams, $companyId, $notLog);
    }

    /**
     * 获得数据模型属性
     *
     * @param object $modelObj 当前模型对象 Model的路径和名称 如 RunBuy\LrChinaCity   为空，则用对象的属性
     * @param string $attrName 属性名称[静态或动态]--注意静态时不要加$ ,与动态属性一样 如 attrTest
     * @param int $isStatic 是否静态属性 0：动态属性 1 静态属性
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function getAttrBS($modelName, $attrName = '', $isStatic = 0, $companyId = null , $notLog = 0){
        if(empty($modelName)) $modelName = static::$model_name;
        static::GetAPIObj($APIObj);
        return $APIObj::getAttrApi($modelName, $attrName, $isStatic, $companyId , $notLog);
    }

    /**
     * 调用数据模型方法
     *
     * @param object $modelObj 当前模型对象 Model的路径和名称 如 RunBuy\LrChinaCity 为空，则用对象的属性
     * @param string $methodName 方法名称
     * @param array $params 参数数组 没有参数:[]  或 [第一个参数, 第二个参数,....];
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function exeMethodBS($modelName, $methodName = '', $params = [], $companyId = null , $notLog = 0){
        if(empty($modelName)) $modelName = static::$model_name;
        static::GetAPIObj($APIObj);
        return $APIObj::exeMethodApi($modelName, $methodName, $params, $companyId , $notLog);
    }

    /**
     * 获得中间Business-DB层属性
     *
     * @param object $modelObj 当前模型对象 Model的路径和名称 如 RunBuy\LrChinaCity 为空，则用对象的属性
     * @param string $attrName 属性名称[静态或动态]--注意静态时不要加$ ,与动态属性一样 如 attrTest
     * @param int $isStatic 是否静态属性 0：动态属性 1 静态属性
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function getBusinessDBAttrBS($modelName, $attrName = '', $isStatic = 0, $companyId = null , $notLog = 0){
        if(empty($modelName)) $modelName = static::$model_name;
        static::GetAPIObj($APIObj);
        return $APIObj::getBusinessDBAttrApi($modelName, $attrName, $isStatic, $companyId, $notLog);
    }


    /**
     * 调用中间Business-DB层方法
     *
     * @param object $modelObj 当前模型对象 Model的路径和名称 如 RunBuy\LrChinaCity  为空，则用对象的属性
     * @param string $methodName 方法名称
     * @param array $params 参数数组 没有参数:[]  或 [第一个参数, 第二个参数,....];
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function exeDBBusinessMethodBS($modelName, $methodName = '', $params = [], $companyId = null , $notLog = 0){
        if(empty($modelName)) $modelName = static::$model_name;
        static::GetAPIObj($APIObj);
        return $APIObj::exeDBBusinessMethodApi($modelName, $methodName, $params, $companyId, $notLog);
    }

    /**
     * 获得中间Business层属性
     *
     * @param object $modelObj 当前模型对象 为空，则用对象的属性
     * @param string $attrName 属性名称[静态或动态]--注意静态时不要加$ ,与动态属性一样 如 attrTest
     * @param int $isStatic 是否静态属性 0：动态属性 1 静态属性
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function getBusinessAttrBS($modelName, $attrName = '', $isStatic = 0, $companyId = null , $notLog = 0){
        if(empty($modelName)) $modelName = static::$model_name;
        static::GetAPIObj($APIObj);
        return $APIObj::getBusinessAttrApi($modelName, $attrName, $isStatic, $companyId, $notLog);
    }

    /**
     * 调用中间Business层方法
     *
     * @param object $modelObj 当前模型对象 为空，则用对象的属性
     * @param string $methodName 方法名称
     * @param array $params 参数数组 没有参数:[]  或 [第一个参数, 第二个参数,....];
     * @param int $companyId 企业id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function exeBusinessMethodBS($modelName, $methodName = '', $params = [], $companyId = null , $notLog = 0){
        if(empty($modelName)) $modelName = static::$model_name;
        static::GetAPIObj($APIObj);
        return $APIObj::exeBusinessMethodApi($modelName, $methodName, $params, $companyId, $notLog);
    }
}