<?php

namespace App\Http\Controllers\Comp;

use App\Services\Request\Data\CommonAPIFormModel;
use App\Services\Request\Data\CommonAPIFromDBBusiness;
use App\Services\Response\Data\CommonAPIFromBusiness;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController;

class CommonController extends CompController
{
    // 公共接口数据来源类型
    // 1、实例化数据模型，直接通过数据模型，调用数据模型的方法。
    // 2、实例化数据中间层，调用中间层的方法来获得数据
    protected static $dataFromType = 2;
    /**
     * 获得首页接口
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $queryParams 条件数组/json字符
     * @param string $relations 关系数组/json字符
     * @return mixed Response
     * @author zouyan(305463219@qq.com)
     */
//    public function index(Request $request)
//    {
//        $this->InitParams($request);
//        $company_id = $this->company_id;
//        // 日志总量
//        $recordCount = CompanyProRecord::where([
//            ['company_id', '=', $company_id],
//        ])->count();
//        // 生产单元
//        $unitCount = CompanyProUnit::where([
//            ['company_id', '=', $company_id],
//        ])->whereIn('status', [1])->count();
//        // 微站访问
//        $visitCount = CompanyVisitCount::where([
//            ['company_id', '=', $company_id],
//        ])->sum('visit_amount');
//        // 用户总量
//        $visitUniqueCount = CompanyVisitUnique::where([
//            ['company_id', '=', $company_id],
//        ])->count();
//        // 平台公告
//        $newList = SiteNews::select(['id','new_title','updated_at'])->limit(10)->orderBy('id', 'desc')->get();
//        // 平台信息
//        $configArr = SiteConfig::get();
//        $configList = [];
//        foreach($configArr as $v){
//            $configList[$v['id']] = $v;
//        }
//        // 获得帮助单条信息
//        $siteIntro = SiteIntro::select(['id','title','created_at','updated_at'])
//            ->limit(10)
//            ->orderBy('sort_num', 'desc')->orderBy('id', 'desc')->get();
//
//        return okArray([
//            'recordCount' => $recordCount,// 日志总量
//            'unitCount' => $unitCount,// 生产单元
//            'visitCount' => $visitCount,// 微站访问
//            'visitUniqueCount' => $visitUniqueCount,// 用户总量
//            'newList' => $newList,// 平台公告
//            'configArr' => $configList,// 平台信息
//            'siteIntro' => $siteIntro,// 帮助单条信息
//        ]);
//    }
    /**
     * 获得首页接口
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $queryParams 条件数组/json字符
     * @param string $relations 关系数组/json字符
     * @return mixed Response
     * @author zouyan(305463219@qq.com)
     */
//    public function admin(Request $request)
//    {
//        $this->InitParams($request);
//        // 会员总数
//        $companyCount = Company::count();
//        // 今日注册
//        $begin_time  = judge_date(day_format_time(1,'',0), 'Y-m-d H:i:s');
//        $end_time  = judge_date(day_format_time(2,'',0) - 1, 'Y-m-d H:i:s');
//        $todayRegCount = Company::whereBetween('created_at', [$begin_time, $end_time])->count();
//        // 今日日志
//        $todayRecordCount = CompanyProRecord::whereBetween('created_at', [$begin_time, $end_time])->count();
//        // 生产单元
//        $unitWaitCount = CompanyProUnit::where([
//            ['status', '=', 0],
//        ])->count();
//        // 最新注册会员
//        $newRegList = Company::select(['id','company_name','company_linkman','company_mobile','created_at','updated_at'])
//            ->limit(10)
//            ->orderBy('id', 'desc')->get();
//        return okArray([
//            'companyCount' => $companyCount,// 会员总数
//            'todayRegCount' => $todayRegCount,// 今日注册
//            'todayRecordCount' => $todayRecordCount,// 今日日志
//            'unitWaitCount' => $unitWaitCount,// 生产单元
//            'newRegList' => $newRegList,// 最新注册会员
//        ]);
//
//    }

    /**
     * 获得所有列表接口
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $queryParams 条件数组/json字符
     * @param string $relations 关系数组/json字符
     * @return mixed Response
     * @author zouyan(305463219@qq.com)
     */
    public function all(Request $request)
    {
        $this->InitParams($request);
        if(static::$dataFromType == 1) {// 直接通过数据模型获得数据
            return okArray(CommonAPIFormModel::requestAllDataByModel($request));
        }else{
            return okArray(CommonAPIFromDBBusiness::requestAllDataByModel($request));
        }
    }

    /**
     * 获得列表接口-根据条件
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $queryParams 条件数组/json字符
     * @param string $relations 关系数组/json字符
     * @return mixed Response
     * @author zouyan(305463219@qq.com)
     */
    public function queryList(Request $request)
    {
        $this->InitParams($request);
        if(static::$dataFromType == 1) {// 直接通过数据模型获得数据
            return okArray(CommonAPIFormModel::requestDataByQuery($request));
        }else{
            return okArray(CommonAPIFromDBBusiness::requestDataByQuery($request));
        }
    }

    /**
     * 获得列表接口
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $queryParams 条件数组/json字符
     * @param string $relations 关系数组/json字符
     * @return mixed mixed Response
     * @author zouyan(305463219@qq.com)
     */
    public function list(Request $request)
    {
        $this->InitParams($request);
        if(static::$dataFromType == 1) {// 直接通过数据模型获得数据
            return okArray(CommonAPIFormModel::requestListDataByModel($request));
        }else{
            return okArray(CommonAPIFromDBBusiness::requestListDataByModel($request));
        }
    }

    /**
     * 获得键值对接口
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $kvParams 查询的kv字段数据参数数组/json字符  ['key' => 'id字段', 'val' => 'name字段'] 或 [] 返回查询的数据
     * @param string $selectParams 查询字段参数数组/json字符 需要获得的字段数组   ['id', 'area_name'] ; 参数 $kv 不为空，则此参数可为空数组
     * @param string $wherParams 查询条件参数数组/json字符
        [
            ['company_id', '=', $company_id],
            ['area_parent_id', '=', $area_parent_id],
        ]
     * @param string $orderByParams 排序参数数组/json字符 一维数组 ['name'=>'desc','name'=>'desc']
     * @return mixed mixed Response
     * @author zouyan(305463219@qq.com)
     */
    public function kv(Request $request)
    {
        $this->InitParams($request);
        if(static::$dataFromType == 1) {// 直接通过数据模型获得数据
            return okArray(CommonAPIFormModel::requestGetKV($request));
        }else{
            return okArray(CommonAPIFromDBBusiness::requestGetKV($request));
        }
    }


    /**
     * 根据id获得详情
     *
     * @param string $Model_name model名称
     * @param int $id
     * @param string $selectParams 查询字段参数--一维数组
     * @param string $relations 关系数组/json字符
     * @return  mixed Response
     * @author zouyan(305463219@qq.com)
     */
    public function getInfo(Request $request)
    {
        $this->InitParams($request);
        if(static::$dataFromType == 1) {// 直接通过数据模型获得数据
            return okArray(CommonAPIFormModel::requestInfoByID($request));
        }else{
            return okArray(CommonAPIFromDBBusiness::requestInfoByID($request));
        }
    }
    /**
     * 根据条件获得详情  pagesize 1:返回一维数组,>1 返回二维数组
     *
     * @param string $Model_name model名称
     * @param int $pagesize  获得的数量 默认 1
     * @param string $queryParams 条件数组/json字符
     * @param string $relations 关系数组/json字符
     * @return  mixed Response
     * @author zouyan(305463219@qq.com)
     */
    public function getInfoByQuery(Request $request)
    {
        $this->InitParams($request);
        if(static::$dataFromType == 1) {// 直接通过数据模型获得数据
            return okArray(CommonAPIFormModel::requestInfoByQuery($request));
        }else{
            return okArray(CommonAPIFromDBBusiness::requestInfoByQuery($request));
        }
    }


    /**
     * 新加接口
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $dataParams 字段数组/json字符
     * @return mixed Response
     * @author zouyan(305463219@qq.com)
     */
    public function add(Request $request)
    {
        $this->InitParams($request);
        if(static::$dataFromType == 1) {// 直接通过数据模型获得数据
            return okArray(CommonAPIFormModel::requestCreate($request));
        }else{
            return okArray(CommonAPIFromDBBusiness::requestCreate($request));
        }
    }

    /**
     * 批量新加接口-data只能返回成功true:失败:false
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $dataParams 字段数组/json字符
     * @return mixed Response
     * @author zouyan(305463219@qq.com)
     */
    public function addBath(Request $request)
    {
        $this->InitParams($request);
        if(static::$dataFromType == 1) {// 直接通过数据模型获得数据
            return okArray(CommonAPIFormModel::requestCreateBath($request));
        }else{
            return okArray(CommonAPIFromDBBusiness::requestCreateBath($request));
        }
    }

    /**
     * 批量新加接口-data只能返回成功true:失败:false
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $dataParams 字段数组/json字符
     * @return mixed Response
     * @author zouyan(305463219@qq.com)
     */
    public function addBathByPrimaryKey(Request $request)
    {
        $this->InitParams($request);
        if(static::$dataFromType == 1) {// 直接通过数据模型获得数据
            return okArray(CommonAPIFormModel::requestCreateBathByPrimaryKey($request));
        }else{
            return okArray(CommonAPIFromDBBusiness::requestCreateBathByPrimaryKey($request));
        }
    }
    /**
     * 修改接口
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $dataParams 字段数组/json字符
     * @param string $queryParams 条件数组/json字符
     * @return mixed Response
     * @author zouyan(305463219@qq.com)
     */
    public function save(Request $request)
    {
        $this->InitParams($request);
        if(static::$dataFromType == 1) {// 直接通过数据模型获得数据
            return okArray(CommonAPIFormModel::requestUpdate($request));
        }else{
            return okArray(CommonAPIFromDBBusiness::requestUpdate($request));
        }
    }


    /**
     * 批量修改设置
     *
     * @param string $Model_name model名称
     * @param string $primaryKey 主键字段,默认为id
     * @param string $dataParams 主键及要修改的字段值 二维数组 数组/json字符
     * @return mixed Response
     * @author zouyan(305463219@qq.com)
     */
    public function saveBathById(Request $request)
    {
        $this->InitParams($request);
        if(static::$dataFromType == 1) {// 直接通过数据模型获得数据
            return okArray(CommonAPIFormModel::batchSaveByPrimaryKey($request));
        }else{
            return okArray(CommonAPIFromDBBusiness::batchSaveByPrimaryKey($request));
        }
    }

    /**
     * 通过id修改接口
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $dataParams 字段数组/json字符
     * @param string $queryParams 条件数组/json字符
     * @return mixed Response
     * @author zouyan(305463219@qq.com)
     */
    public function saveById(Request $request)
    {
        $this->InitParams($request);
        if(static::$dataFromType == 1) {// 直接通过数据模型获得数据
            return okArray(CommonAPIFormModel::requestSave($request));
        }else{
            return okArray(CommonAPIFromDBBusiness::requestSave($request));
        }
    }

    /**
     * 自增自减接口,通过条件-data操作的行数
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $queryParams 条件数组/json字符
     * @param string incDecType 增减类型 inc 增 ;dec 减[默认]
     * @param string incDecField 增减字段
     * @param string incDecVal 增减值
     * @param string modifFields 修改的其它字段 -没有，则传空数组json
     * @return mixed Response
     * @author zouyan(305463219@qq.com)
     */
    public function saveDecIncByQuery(Request $request)
    {
        $this->InitParams($request);
        if(static::$dataFromType == 1) {// 直接通过数据模型获得数据
            return okArray(CommonAPIFormModel::requestSaveDecIncByQuery($request));
        }else{
            return okArray(CommonAPIFromDBBusiness::requestSaveDecIncByQuery($request));
        }
    }

    /**
     * 自增自减接口,通过数组[二维]-data操作的行数数组
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $dataParams 条件数组/json字符
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
     如:
    [
        [
            'Model_name' => 'CompanyProSecurityLabel',
            'primaryVal' => '7',
            'incDecType' => 'inc',
            'incDecField' => 'validate_num',
            'incDecVal' => '2',
            'modifFields' => [],
        ],
        [
            'Model_name' => 'CompanyProSecurityLabel',
            'primaryVal' => '9',
            'incDecType' => 'inc',
            'incDecField' => 'validate_num',
            'incDecVal' => '1',
            'modifFields' => [
                'status' => 1,
            ],
        ],
    ];
     * @return mixed Response
     * @author zouyan(305463219@qq.com)
     */
    public function saveDecIncByArr(Request $request)
    {
        $this->InitParams($request);
        if(static::$dataFromType == 1) {// 直接通过数据模型获得数据
            return okArray(CommonAPIFormModel::batchSaveDecIncByPrimaryKey($request));
        }else{
            return okArray(CommonAPIFromDBBusiness::batchSaveDecIncByPrimaryKey($request));
        }
    }
    /**
     * 根据条件删除接口
     *
     * @param int $company_id 公司id
     * @param string $Model_name model名称
     * @param string $queryParams 条件数组/json字符
     * @return mixed Response
     * @author zouyan(305463219@qq.com)
     */
    public function del(Request $request)
    {
        $this->InitParams($request);
        if(static::$dataFromType == 1) {// 直接通过数据模型获得数据
            return okArray(CommonAPIFormModel::requestDel($request));
        }else{
            return okArray(CommonAPIFromDBBusiness::requestDel($request));
        }
    }

    /**
     * 同步修改关系接口
     *
     * @param string $Model_name model名称
     * @param int $id
     * @param string/array $synces 同步关系数组/json字符
     * @return mixed Response
     * @author zouyan(305463219@qq.com)
     */
    public function sync(Request $request)
    {
        $this->InitParams($request);
        if(static::$dataFromType == 1) {// 直接通过数据模型获得数据
            return okArray(CommonAPIFormModel::requestSync($request));
        }else{
            return okArray(CommonAPIFromDBBusiness::requestSync($request));
        }
    }

    /**
     * 移除关系接口
     *
     * @param string $Model_name model名称
     * @param int $id
     * @param string/array $detaches 移除关系数组/json字符 空：移除所有，id数组：移除指定的
     * @return mixed Response
     * @author zouyan(305463219@qq.com)
     */
    public function detach(Request $request)
    {
        $this->InitParams($request);
        if(static::$dataFromType == 1) {// 直接通过数据模型获得数据
            return okArray(CommonAPIFormModel::requestDetach($request));
        }else{
            return okArray(CommonAPIFromDBBusiness::requestDetach($request));
        }
    }

    /**
     * 根据主表id，获得对应的历史表id
     *
     * @param Request $request
     * @param string $Model_name model 主表对象名称
     * @param mixed $primaryVal 主表对象主键值
     * @param string $historyObj 历史表对象名称
     * @param string $historyTable 历史表名字
     * @param array $historySearch 历史表查询字段[一维数组][一定要包含主表id的] +  版本号(不用传，自动会加上) 格式 ['字段1'=>'字段1的值','字段2'=>'字段2的值' ... ]
     * @param array $ignoreFields 忽略都有的字段中，忽略主表中的记录 [一维数组] 格式 ['字段1','字段2' ... ]
     * @return  int 历史表id
     * @author zouyan(305463219@qq.com)
     */
    public function getHistoryId(Request $request)
    {
        $this->InitParams($request);
        if(static::$dataFromType == 1) {// 直接通过数据模型获得数据
            return okArray(CommonAPIFormModel::requestGetHistoryId($request));
        }else{
            return okArray(CommonAPIFromDBBusiness::requestGetHistoryId($request));
        }
    }

    /**
     * 对比主表和历史表是否相同，相同：不更新版本号，不同：版本号+1
     *
     * @param string $Model_name model名称
     * @param mixed $primaryVal 主表对象主键值
     * @param obj $historyObj 历史表对象
     * @param string $historyTable 历史表名字
     * @param array $historySearch 历史表查询字段[一维数组][一定要包含主表id的] +  版本号(不用传，自动会加上)格式 ['字段1'=>'字段1的值','字段2'=>'字段2的值' ... ]
     * @param array $ignoreFields 忽略都有的字段中，忽略主表中的记录 [一维数组] - 必须会有 [历史表中对应主表的id字段] 格式 ['字段1','字段2' ... ]
     * @param int $forceIncVersion 如果需要主表版本号+1,是否更新主表 1 更新 ;0 不更新
     * @return array 不同字段的内容 数组 [ '字段名' => ['原表中的值','历史表中的值']]; 空数组：不用版本号+1;非空数组：版本号+1
     * @author zouyan(305463219@qq.com)
     */
    public function compareHistoryOrUpdateVersion(Request $request)
    {
        $this->InitParams($request);
        if(static::$dataFromType == 1) {// 直接通过数据模型获得数据
            return okArray(CommonAPIFormModel::requestCompareHistoryOrUpdateVersion($request));
        }else{
            return okArray(CommonAPIFromDBBusiness::requestCompareHistoryOrUpdateVersion($request));
        }
    }

    /**
     * 查找记录,或创建新记录[没有找到] - $searchConditon +  $updateFields 的字段,
     *
     * @param string $Model_name model名称
     * @param array $searchConditon 查询字段[一维数组]
     * @param array $updateFields 表中还需要保存的记录 [一维数组] -- 新建表时会用
     * @return object $mainObj 表对象[一维]
     * @author zouyan(305463219@qq.com)
     */
    public function firstOrCreate(Request $request)
    {
        $this->InitParams($request);
        if(static::$dataFromType == 1) {// 直接通过数据模型获得数据
            return okArray(CommonAPIFormModel::requestFirstOrCreate($request));
        }else{
            return okArray(CommonAPIFromDBBusiness::requestFirstOrCreate($request));
        }
    }

    /**
     * 已存在则更新，否则创建新模型--持久化模型，所以无需调用 save()-- $searchConditon +  $updateFields 的字段,
     *
     * @param string $Model_name model名称
     * @param array $searchConditon 查询字段[一维数组]
     * @param array $updateFields 表中还需要保存的记录 [一维数组] -- 新建表时会用
     * @return object $mainObj 表对象[一维]
     * @author zouyan(305463219@qq.com)
     */
    public function updateOrCreate(Request $request)
    {
        $this->InitParams($request);
        if(static::$dataFromType == 1) {// 直接通过数据模型获得数据
            return okArray(CommonAPIFormModel::requestUpdateOrCreate($request));
        }else{
            return okArray(CommonAPIFromDBBusiness::requestUpdateOrCreate($request));
        }
    }

    /**
     * 获得具体数据模型属性
     *
     * @param string $Model_name model名称 Model的路径和名称 如 RunBuy\LrChinaCity
     * @param string $attrName 属性名称[静态或动态]--注意静态时不要加$ ,与动态属性一样 如 attrTest
     * @param int $isStatic 是否静态属性 0：动态属性 1 静态属性
     * @return string 属性值
     * @author zouyan(305463219@qq.com)
     */
    public function attr(Request $request)
    {
        $this->InitParams($request);
        if(static::$dataFromType == 1) {// 直接通过数据模型获得数据
            return okArray(CommonAPIFormModel::requestGetAttr($request));
        }else{
            return okArray(CommonAPIFromDBBusiness::requestGetAttr($request));
        }
    }

    /**
     * 调用数据模型方法
     *
     * @param string $Model_name model名称 Model的路径和名称 如 RunBuy\LrChinaCity
     * @param string $methodName 方法名称
     * @param array $params 参数数组 没有参数:[]  或 [第一个参数, 第二个参数,....];
     * @return mixed 方法返回值
     * @author zouyan(305463219@qq.com)
     */
    public function exeMethod(Request $request)
    {
        $this->InitParams($request);
        if(static::$dataFromType == 1) {// 直接通过数据模型获得数据
            return okArray(CommonAPIFormModel::requestExeMethod($request));
        }else{
            return okArray(CommonAPIFromDBBusiness::requestExeMethod($request));
        }
    }

    /**
     * 获得具体数据中间层属性
     *
     * @param string $Model_name model名称 Model的路径和名称 如 RunBuy\LrChinaCity
     * @param string $attrName 属性名称[静态或动态]--注意静态时不要加$ ,与动态属性一样 如 attrTest
     * @param int $isStatic 是否静态属性 0：动态属性 1 静态属性
     * @return string 属性值
     * @author zouyan(305463219@qq.com)
     */
    public function businessDBAttr(Request $request)
    {
        $this->InitParams($request);
        return okArray(CommonAPIFromDBBusiness::requestGetBusinessDBAttr($request));
    }

    /**
     * 调用数据中间层方法
     *
     * @param string $Model_name model名称 Model的路径和名称 如 RunBuy\LrChinaCity
     * @param string $methodName 方法名称
     * @param array $params 参数数组 没有参数:[]  或 [第一个参数, 第二个参数,....];
     * @return mixed 方法返回值
     * @author zouyan(305463219@qq.com)
     */
    public function exeBusinessDBMethod(Request $request)
    {
        $this->InitParams($request);
        return okArray(CommonAPIFromDBBusiness::requestExeBusinessDBMethod($request));
    }
    // ******************数据中间Business层*************************************************************
    /**
     * 获得具体数据中间Business层属性
     *
     * @param string $Model_name model名称 Business 下的目录开始, 没有文件名最后的Business如 Controller\DB\CTDBCart
     * @param string $attrName 属性名称[静态或动态]--注意静态时不要加$ ,与动态属性一样 如 attrTest
     * @param int $isStatic 是否静态属性 0：动态属性 1 静态属性
     * @return string 属性值
     * @author zouyan(305463219@qq.com)
     */
    public function businessAttr(Request $request)
    {
        $this->InitParams($request);
        return okArray(CommonAPIFromBusiness::requestGetBusinessAttr($request));
    }

    /**
     * 调用数据中间Business层方法
     *
     * @param string $Model_name model名称 Business 下的目录开始, 没有文件名最后的Business如 Controller\DB\CTDBCart
     * @param string $methodName 方法名称
     * @param array $params 参数数组 没有参数:[]  或 [第一个参数, 第二个参数,....];
     * @return mixed 方法返回值
     * @author zouyan(305463219@qq.com)
     */
    public function exeBusinessMethod(Request $request)
    {
        $this->InitParams($request);
        return okArray(CommonAPIFromBusiness::requestExeBusinessMethod($request, $this));
    }

}
