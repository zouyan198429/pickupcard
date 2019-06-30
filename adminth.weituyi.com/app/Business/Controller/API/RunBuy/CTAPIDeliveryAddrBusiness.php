<?php
// 商家
namespace App\Business\Controller\API\RunBuy;

// use App\Business\API\RunBuy\SellerHistoryAPIBusiness;
use App\Services\Excel\ImportExport;
use App\Services\Request\API\HttpRequest;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Services\Request\CommonRequest;
use App\Http\Controllers\BaseController as Controller;
class CTAPIDeliveryAddrBusiness extends BasicPublicCTAPIBusiness
{
    public static $model_name = 'API\RunBuy\DeliveryAddrAPI';
    // 状态1未发货2已发货4已收货
    public static $statusArr = [
        '1' => '未发货',
        '2' => '已发货',
//        '4' => '已收货',
    ];

    /**
     * 获得列表数据--所有数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $oprateBit 操作类型位 1:获得所有的; 2 分页获取[同时有1和2，2优先]；4 返回分页html翻页代码
     * @param string $queryParams 条件数组/json字符
     * @param mixed $relations 关系
     * @param array $extParams 其它扩展参数，
     *    $extParams = [
     *        'useQueryParams' => '是否用来拼接查询条件，true:用[默认];false：不用'
     *        'sqlParams' => [// 其它sql条件[覆盖式],下面是常用的，其它的也可以
     *           'where' => '如果有值，则替换where'
     *           'select' => '如果有值，则替换select'
     *           'orderBy' => '如果有值，则替换orderBy'
     *           'whereIn' => '如果有值，则替换whereIn'
     *           'whereNotIn' => '如果有值，则替换whereNotIn'
     *           'whereBetween' => '如果有值，则替换whereBetween'
     *           'whereNotBetween' => '如果有值，则替换whereNotBetween'
     *       ]
     *   ];
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function getList(Request $request, Controller $controller, $oprateBit = 2 + 4, $queryParams = [], $relations = '', $extParams = [], $notLog = 0){
        $company_id = $controller->company_id;

        // 获得数据
        $defaultQueryParams = [
            'where' => [
//                ['company_id', $company_id],
//                //['mobile', $keyword],
            ],
//            'select' => [
//                'id','company_id','position_name','sort_num'
//                //,'operate_staff_id','operate_staff_id_history'
//                ,'created_at'
//            ],
            'orderBy' => ['id'=>'desc'],// 'sort_num'=>'desc',
        ];// 查询条件参数
        if(empty($queryParams)){
            $queryParams = $defaultQueryParams;
        }
        $isExport = 0;

        $useSearchParams = $extParams['useQueryParams'] ?? true;// 是否用来拼接查询条件，true:用[默认];false：不用
        // 其它sql条件[覆盖式]
        $sqlParams = $extParams['sqlParams'] ?? [];
        $sqlKeys = array_keys($sqlParams);
        foreach($sqlKeys as $tKey){
            // if(isset($sqlParams[$tKey]) && !empty($sqlParams[$tKey]))  $queryParams[$tKey] = $sqlParams[$tKey];
            if(isset($sqlParams[$tKey]) )  $queryParams[$tKey] = $sqlParams[$tKey];
        }

        if($useSearchParams) {
            // $params = self::formatListParams($request, $controller, $queryParams);
            $product_id = CommonRequest::getInt($request, 'product_id');
            if($product_id > 0 )  array_push($queryParams['where'], ['product_id', '=', $product_id]);

            $product_id_history = CommonRequest::getInt($request, 'product_id_history');
            if($product_id_history > 0 )  array_push($queryParams['where'], ['product_id_history', '=', $product_id_history]);

            $activity_id = CommonRequest::getInt($request, 'activity_id');
            if($activity_id > 0 )  array_push($queryParams['where'], ['activity_id', '=', $activity_id]);

            $code_id = CommonRequest::getInt($request, 'code_id');
            if($code_id > 0 )  array_push($queryParams['where'], ['code_id', '=', $code_id]);


            $status = CommonRequest::get($request, 'status');
            if(is_numeric($status) )  array_push($queryParams['where'], ['status', '=', $status]);

            $province_id = CommonRequest::getInt($request, 'province_id');
            if($province_id > 0 )  array_push($queryParams['where'], ['province_id', '=', $province_id]);

            $province_id_history = CommonRequest::getInt($request, 'province_id_history');
            if($province_id_history > 0 )  array_push($queryParams['where'], ['province_id_history', '=', $province_id_history]);

            $city_id = CommonRequest::getInt($request, 'city_id');
            if($city_id > 0 )  array_push($queryParams['where'], ['city_id', '=', $city_id]);

            $city_id_history = CommonRequest::getInt($request, 'city_id_history');
            if($city_id_history > 0 )  array_push($queryParams['where'], ['city_id_history', '=', $city_id_history]);

            $area_id = CommonRequest::getInt($request, 'area_id');
            if($area_id > 0 )  array_push($queryParams['where'], ['area_id', '=', $area_id]);

            $area_id_history = CommonRequest::getInt($request, 'area_id_history');
            if($area_id_history > 0 )  array_push($queryParams['where'], ['area_id_history', '=', $area_id_history]);


            $operate_staff_id = CommonRequest::getInt($request, 'operate_staff_id');
            if($operate_staff_id > 0 )  array_push($queryParams['where'], ['operate_staff_id', '=', $operate_staff_id]);

            $operate_staff_id_history = CommonRequest::getInt($request, 'operate_staff_id_history');
            if($operate_staff_id_history > 0 )  array_push($queryParams['where'], ['operate_staff_id_history', '=', $operate_staff_id_history]);

            $field = CommonRequest::get($request, 'field');
            $keyWord = CommonRequest::get($request, 'keyword');
            if (!empty($field) && !empty($keyWord)) {
                array_push($queryParams['where'], [$field, 'like', '%' . $keyWord . '%']);
            }

            $ids = CommonRequest::get($request, 'ids');// 多个用逗号分隔,
            if (!empty($ids)) {
                if (strpos($ids, ',') === false) { // 单条
                    array_push($queryParams['where'], ['id', $ids]);
                } else {
                    $queryParams['whereIn']['id'] = explode(',', $ids);
                }
            }
            $isExport = CommonRequest::getInt($request, 'is_export'); // 是否导出 0非导出 ；1导出数据
            if ($isExport == 1) $oprateBit = 1;
        }
        // $relations = ['CompanyInfo'];// 关系
        // $relations = '';//['CompanyInfo'];// 关系
        $result = static::getBaseListData($request, $controller, '', $queryParams, $relations , $oprateBit, $notLog);

        // 格式化数据
        $data_list = $result['data_list'] ?? [];
        foreach($data_list as $k => $v){
            // 省
            $temProvinceName = $v['province']['city_name'] ?? '';
            // $temProvinceId = $v['province']['id'] ?? 0;
            if(empty($temProvinceName)) {
                $temProvinceName = $v['province_history']['city_name'] ?? '';
               // $temProvinceId = $v['province_history']['city_table_id'] ?? 0;
            }
            $data_list[$k]['province_name'] =  $temProvinceName;
            // $data_list[$k]['province_id'] =  $temProvinceId;
            if(isset($data_list[$k]['province'])) unset($data_list[$k]['province']);
            if(isset($data_list[$k]['province_history'])) unset($data_list[$k]['province_history']);

            // 市
            $temCityName = $v['city']['city_name'] ?? '';
            // $temCityId  = $v['area']['id'] ?? 0;
            if(empty($temCityName)) {
                $temCityName = $v['city_history']['city_name'] ?? '';
                // $temCityId = $v['city_history']['city_table_id'] ?? 0;
            }
            $data_list[$k]['city_name'] =  $temCityName;
            // $data_list[$k]['city_id'] =  $temCityId;
            if(isset($data_list[$k]['city'])) unset($data_list[$k]['city']);
            if(isset($data_list[$k]['city_history'])) unset($data_list[$k]['city_history']);

            // 县区
            $temAreaName = $v['area']['city_name'] ?? '';
            // $temAreaId = $v['area']['id'] ?? 0;
            if(empty($temAreaName)) {
                $temAreaName = $v['area_history']['city_name'] ?? '';
                // $temAreaId = $v['area_history']['city_table_id'] ?? 0;
            }
            $data_list[$k]['area_name'] = $temAreaName ;
            // $data_list[$k]['area_id'] = $temAreaId ;
            if(isset($data_list[$k]['area'])) unset($data_list[$k]['area']);
            if(isset($data_list[$k]['area_history'])) unset($data_list[$k]['area_history']);

            // 商品名称
//            $data_list[$k]['product_name'] = $v['product_info']['product_name'] ?? '';
//            $data_list[$k]['pre_code'] = $v['product_info']['pre_code'] ?? '';// 编码前缀
            // $data_list[$k]['product_id'] = $v['product_info']['id'] ?? 0;

            $tem_product_name = $v['product_info']['product_name'] ?? '';
            if(empty($tem_product_name)) $tem_product_name = $v['product_history_info']['product_name'] ?? '';
            $tem_pre_code = $v['product_info']['pre_code'] ?? '';// 编码前缀
            if(empty($tem_pre_code)) $tem_pre_code = $v['product_history_info']['pre_code'] ?? '';
            $tem_product_id = $v['product_info']['id'] ?? 0;
            if(empty($tem_product_id)) $tem_product_id = $v['product_history_info']['product_id'] ?? '';


            $data_list[$k]['product_name'] = $tem_product_name;
            $data_list[$k]['pre_code'] = $tem_pre_code;// 编码前缀
            // $data_list[$k]['product_id'] = $tem_product_id;

            if(isset($data_list[$k]['product_info'])) unset($data_list[$k]['product_info']);
            if(isset($data_list[$k]['product_history_info'])) unset($data_list[$k]['product_history_info']);
            // 添加人
            $oprate_name = $v['oprate_staff']['real_name'] ?? '';
            if(empty($oprate_name)) $oprate_name = $v['oprate_staff_history']['real_name'] ?? '';
            $data_list[$k]['oprate_name'] = $oprate_name;
            if(isset($data_list[$k]['oprate_staff'])) unset($data_list[$k]['oprate_staff']);
            if(isset($data_list[$k]['oprate_staff_history'])) unset($data_list[$k]['oprate_staff_history']);

        }
        $result['data_list'] = $data_list;
        // 导出功能
        if($isExport == 1){
//            $headArr = ['work_num'=>'工号', 'department_name'=>'部门'];
//            ImportExport::export('','excel文件名称',$data_list,1, $headArr, 0, ['sheet_title' => 'sheet名称']);
            die;
        }
        // 非导出功能
        return ajaxDataArr(1, $result, '');
    }

    /**
     * 根据id获得单条数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $id id
     * @param array $selectParams 查询字段参数--一维数组
     * @param mixed $relations 关系
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 单条数据 - -维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function getInfoData(Request $request, Controller $controller, $id , $selectParams = [], $relations = '', $notLog = 0){
        $company_id = $controller->company_id;
        // $relations = '';
        // $resultDatas = APIRunBuyRequest::getinfoApi(self::$model_name, '', $relations, $company_id , $id);
        $info = static::getInfoDataBase($request, $controller,'', $id, $selectParams, $relations, $notLog);
        // 判断权限
//        $judgeData = [
//            // 'company_id' => $company_id,
//            'id' => $company_id,
//        ];
//        static::judgePowerByObj($request, $controller, $info, $judgeData );

        // 省
        $temProvinceName = $info['province']['city_name'] ?? '';
        // $temProvinceId = $info['province']['id'] ?? 0;
        if(empty($temProvinceName)) {
            $temProvinceName = $info['province_history']['city_name'] ?? '';
            // $temProvinceId = $info['province_history']['city_table_id'] ?? 0;
        }
        $info['province_name'] =  $temProvinceName;
        // $info['province_id'] =  $temProvinceId;
        if(isset($info['province'])) unset($info['province']);
        if(isset($info['province_history'])) unset($info['province_history']);

        // 市
        $temCityName = $info['city']['city_name'] ?? '';
        // $temCityId  = $info['area']['id'] ?? 0;
        if(empty($temCityName)) {
            $temCityName = $info['city_history']['city_name'] ?? '';
            // $temCityId = $info['city_history']['city_table_id'] ?? 0;
        }
        $info['city_name'] =  $temCityName;
        // $info['city_id'] =  $temCityId;
        if(isset($info['city'])) unset($info['city']);
        if(isset($info['city_history'])) unset($info['city_history']);

        // 县区
        $temAreaName = $info['area']['city_name'] ?? '';
        // $temAreaId = $info['area']['id'] ?? 0;
        if(empty($temAreaName)) {
            $temAreaName = $info['area_history']['city_name'] ?? '';
            // $temAreaId = $info['area_history']['city_table_id'] ?? 0;
        }
        $info['area_name'] = $temAreaName ;
        // $info['area_id'] = $temAreaId ;
        if(isset($info['area'])) unset($info['area']);
        if(isset($info['area_history'])) unset($info['area_history']);


        // 所属商品
        $product_name = $info['product_info']['product_name'] ?? '';
        if(empty($product_name)) $product_name = $info['product_history_info']['product_name'] ?? '';
        $info['product_name'] = $product_name;

        $tem_pre_code = $info['product_info']['pre_code'] ?? '';// 编码前缀
        if(empty($tem_pre_code)) $tem_pre_code = $info['product_history_info']['pre_code'] ?? '';
        $info['pre_code'] = $tem_pre_code;

//        $tem_product_id = $info['product_info']['id'] ?? 0;
//        if(empty($tem_product_id)) $tem_product_id = $info['product_history_info']['product_id'] ?? '';
//        $info['product_id'] = $tem_product_id;

        $now_product_state = 0;// 最新的城市代理 0没有变化 ;1 已经删除  2 试卷不同
        if(isset($info['product_history_info']) && isset($info['product_info'])){
            $history_version_num = $info['product_history_info']['version_num'] ?? '';
            $version_num = $info['product_info']['version_num'] ?? '';
            if(empty($info['product_info'])){
                $now_product_state = 1;
            }elseif($version_num != '' && $history_version_num != $version_num){
                $now_product_state = 2;
            }
        }
        if(isset($info['product_history_info'])) unset($info['product_history_info']);
        if(isset($info['product_info'])) unset($info['product_info']);

        // 添加人
        $oprate_name = $info['oprate_staff']['real_name'] ?? '';
        if(empty($oprate_name)) $oprate_name = $info['oprate_staff_history']['real_name'] ?? '';
        $info['oprate_name'] = $oprate_name;
        if(isset($info['oprate_staff'])) unset($info['oprate_staff']);
        if(isset($info['oprate_staff_history'])) unset($info['oprate_staff_history']);

        return $info;
    }

    /**
     * 格式化列表查询条件-暂不用
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param string $queryParams 条件数组/json字符
     * @return  array 参数数组 一维数据
     * @author zouyan(305463219@qq.com)
     */
//    public static function formatListParams(Request $request, Controller $controller, &$queryParams = []){
//        $params = [];
//        $title = CommonRequest::get($request, 'title');
//        if(!empty($title)){
//            $params['title'] = $title;
//            array_push($queryParams['where'],['title', 'like' , '%' . $title . '%']);
//        }
//
//        $ids = CommonRequest::get($request, 'ids');// 多个用逗号分隔,
//        if (!empty($ids)) {
//            $params['ids'] = $ids;
//            if (strpos($ids, ',') === false) { // 单条
//                array_push($queryParams['where'],['id', $ids]);
//            }else{
//                $queryParams['whereIn']['id'] = explode(',',$ids);
//                $params['idArr'] = explode(',',$ids);
//            }
//        }
//        return $params;
//    }

    /**
     * 获得当前记录前/后**条数据--二维数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $id 当前记录id
     * @param int $nearType 类型 1:前**条[默认]；2后**条 ; 4 最新几条;8 有count下标则是查询数量, 返回的数组中total 就是真实的数量
     * @param int $limit 数量 **条
     * @param int $offset 偏移数量
     * @param string $queryParams 条件数组/json字符
     * @param mixed $relations 关系
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据 - 二维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function getNearList(Request $request, Controller $controller, $id = 0, $nearType = 1, $limit = 1, $offset = 0, $queryParams = [], $relations = '', $notLog = 0)
    {
        $company_id = $controller->company_id;
        // 前**条[默认]
        $defaultQueryParams = [
            'where' => [
               // ['company_id', $company_id],
//                ['id', '>', $id],
            ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//                //,'operate_staff_id','operate_staff_id_history'
//                ,'created_at'
//            ],
//            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
            'orderBy' => ['id'=>'asc'],
            'limit' => $limit,
            'offset' => $offset,
            // 'count'=>'0'
        ];
        if(($nearType & 1) == 1){// 前**条
            $defaultQueryParams['orderBy'] = ['id'=>'asc'];
            array_push($defaultQueryParams['where'],['id', '>', $id]);
        }

        if(($nearType & 2) == 2){// 后*条
            array_push($defaultQueryParams['where'],['id', '<', $id]);
            $defaultQueryParams['orderBy'] = ['id'=>'desc'];
        }

        if(($nearType & 4) == 4){// 4 最新几条
            $defaultQueryParams['orderBy'] = ['id'=>'desc'];
        }

        if(($nearType & 8) == 8){// 8 有count下标则是查询数量, 返回的数组中total 就是真实的数量
            $defaultQueryParams['count'] = 0;
        }

        if(empty($queryParams)){
            $queryParams = $defaultQueryParams;
        }
        $result = static::getList($request, $controller, 1 + 0, $queryParams, $relations, [], $notLog);
        // 格式化数据
        $data_list = $result['result']['data_list'] ?? [];
        if($nearType == 1) $data_list = array_reverse($data_list); // 相反;
//        foreach($data_list as $k => $v){
//            // 公司名称
//            $data_list[$k]['company_name'] = $v['company_info']['company_name'] ?? '';
//            if(isset($data_list[$k]['company_info'])) unset($data_list[$k]['company_info']);
//        }
//        $result['result']['data_list'] = $data_list;
        return $data_list;
    }

    /**
     * 导入模版
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function importTemplate(Request $request, Controller $controller)
    {
//        $headArr = ['work_num'=>'工号', 'department_name'=>'部门'];
//        $data_list = [];
//        ImportExport::export('','员工导入模版',$data_list,1, $headArr, 0, ['sheet_title' => '员工导入模版']);
        die;
    }
    /**
     * 删除单条数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function delAjax(Request $request, Controller $controller, $notLog = 0)
    {
        $company_id = $controller->company_id;
        // $id = CommonRequest::getInt($request, 'id');
        return static::delAjaxBase($request, $controller, '', $notLog);

    }


    /**
     * 根据id新加或修改单条数据-id 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param array $saveData 要保存或修改的数组
     * @param int $id id
     * @param boolean $modifAddOprate 修改时是否加操作人，true:加;false:不加[默认]
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 单条数据 - -维数组 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     * @author zouyan(305463219@qq.com)
     */
    public static function replaceById(Request $request, Controller $controller, $saveData, &$id, $modifAddOprate = false, $notLog = 0){
        $company_id = $controller->company_id;

        $user_id = $controller->user_id;
        if(isset($saveData['real_name']) && empty($saveData['real_name'])  ){
            throws('收货人不能为空！');
        }

        if(isset($saveData['tel']) && empty($saveData['tel'])  ){
            throws('收货电话不能为空！');
        }

//        if(isset($saveData['mobile']) && empty($saveData['mobile'])  ){
//            throws('手机不能为空！');
//        }

//        if($id <= 0 && isset($saveData['admin_username']) && empty($saveData['admin_username'])  ){
//            throws('用户名不能为空！');
//        }


        // 调用新加或修改接口
        $apiParams = [
            'saveData' => $saveData,
            'company_id' => $company_id,
            'id' => $id,
            'operate_staff_id' => $user_id,
            'modifAddOprate' => 0,
        ];
        $id = static::exeDBBusinessMethodCT($request, $controller, '',  'modifyById', $apiParams, $company_id, $notLog);// replaceById
        return $id;
/*
        $isModify = false;
        if($id > 0){
            $isModify = true;
            // 判断权限
//            $judgeData = [
//                'company_id' => $company_id,
//            ];
//            $relations = '';
//            static::judgePower($request, $controller, $id, $judgeData, '', $company_id, $relations, $notLog);
            if($modifAddOprate) static::addOprate($request, $controller, $saveData);

        }else {// 新加;要加入的特别字段
            $addNewData = [
               // 'company_id' => $company_id,
            ];
            $saveData = array_merge($saveData, $addNewData);
            // 加入操作人员信息
            static::addOprate($request, $controller, $saveData);
        }
        // 新加或修改
        $result = static::replaceByIdBase($request, $controller, '', $saveData, $id, $notLog);
//        if($isModify){
//            // 判断版本号是否要+1
//            $historySearch = [
//                //  'company_id' => $company_id,
//                'seller_id' => $id,
//            ];
//            static::compareHistoryOrUpdateVersion($request, $controller, '' , $id, SellerHistoryAPIBusiness::$model_name
//                , 'seller_history', $historySearch, ['seller_id'], 1, $company_id);
//        }
        return $result;
*/

    }

    // ***********导入***开始************************************************************
    /**
     * 批量导入
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param array $saveData 要保存或修改的数组
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function import(Request $request, Controller $controller, $saveData , $notLog = 0)
    {
        $company_id = $controller->company_id;
        // 参数
        $requestData = [
            'company_id' => $company_id,
            'staff_id' =>  $controller->user_id,
            'admin_type' =>  self::$admin_type,
            'save_data' => $saveData,
        ];
        $url = config('public.apiUrl') . config('apiUrl.apiPath.staffImport');
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 批量导入员工--通过文件路径
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param string $fileName 文件全路径
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function importByFile(Request $request, Controller $controller, $fileName = '', $notLog = 0){
        // $fileName = 'staffs.xlsx';
        $dataStartRow = 1;// 数据开始的行号[有抬头列，从抬头列开始],从1开始
        // 需要的列的值的下标关系：一、通过列序号[1开始]指定；二、通过专门的列名指定;三、所有列都返回[文件中的行列形式],$headRowNum=0 $headArr=[]
        $headRowNum = 1;//0:代表第一种方式，其它数字：第二种方式; 1开始 -必须要设置此值，$headArr 参数才起作用
        // 下标对应关系,如果设置了，则只获取设置的列的值
        // 方式一格式：['1' => 'name'，'2' => 'chinese',]
        // 方式二格式: ['姓名' => 'name'，'语文' => 'chinese',]
        $headArr = [
            '县区' => 'department',
            '归属营业厅或片区' => 'group',
            '姓名或渠道名称' => 'channel',
            //'姓名' => 'real_name',
            '工号' => 'work_num',
            '职务' => 'position',
            '手机号' => 'mobile',
            '性别' => 'sex',
        ];
//        $headArr = [
//            '1' => 'name',
//            '2' => 'chinese',
//            '3' => 'maths',
//            '4' => 'english',
//        ];
        try{
            $dataArr = ImportExport::import($fileName, $dataStartRow, $headRowNum, $headArr);
        } catch ( \Exception $e) {
            throws($e->getMessage());
        }
        return self::import($request, $controller, $dataArr, $notLog);
    }

    // ***********导入***结束************************************************************

    // ***********获得kv***开始************************************************************
    // 根据父id,获得子数据kv数组
    public static function getCityByPid(Request $request, Controller $controller, $parent_id = 0, $notLog = 0){
        $company_id = $controller->company_id;
        $kvParams = ['key' => 'id', 'val' => 'city_name'];
        $queryParams = [
            'where' => [
                // ['id', '&' , '16=16'],
                ['parent_id', '=', $parent_id],
                //['mobile', $keyword],
                //['admin_type',self::$admin_type],
            ],
//            'whereIn' => [
//                'id' => $cityPids,
//            ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//            ],
            'orderBy' => ['sort_num'=>'desc', 'id'=>'asc'],
        ];
        return static::getKVCT( $request,  $controller, '', $kvParams, [], $queryParams, $company_id, $notLog);
    }

    // 根据父id,获得子数据kv数组
    public static function getListKV(Request $request, Controller $controller, $notLog = 0){
        $company_id = $controller->company_id;
        $kvParams = ['key' => 'id', 'val' => 'type_name'];
        $queryParams = [
            'where' => [
                // ['id', '&' , '16=16'],
                // ['parent_id', '=', $parent_id],
                //['mobile', $keyword],
                //['admin_type',self::$admin_type],
            ],
//            'whereIn' => [
//                'id' => $cityPids,
//            ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//            ],
            'orderBy' => ['sort_num'=>'desc', 'id'=>'desc'],
        ];
        return static::getKVCT( $request,  $controller, '', $kvParams, [], $queryParams, $company_id, $notLog);
    }
    // ***********获得kv***结束************************************************************

    // ***********通过组织条件获得kv***开始************************************************************
    /**
     * 获得列表数据--所有数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $pid 当前父id
     * @param int $oprateBit 操作类型位 1:获得所有的; 2 分页获取[同时有1和2，2优先]；4 返回分页html翻页代码
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据[一维的键=>值数组]
     * @author zouyan(305463219@qq.com)
     */
    public static function getChildListKeyVal(Request $request, Controller $controller, $pid, $oprateBit = 2 + 4, $notLog = 0){
        $parentData = self::getChildList($request, $controller, $pid, $oprateBit, $notLog);
        $department_list = $parentData['result']['data_list'] ?? [];
        return Tool::formatArrKeyVal($department_list, 'id', 'city_name');
    }
    /**
     * 获得列表数据--所有数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $pid 当前父id
     * @param int $oprateBit 操作类型位 1:获得所有的; 2 分页获取[同时有1和2，2优先]；4 返回分页html翻页代码
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function getChildList(Request $request, Controller $controller, $pid, $oprateBit = 2 + 4, $notLog = 0){
        $company_id = $controller->company_id;

        // 获得数据
        $queryParams = [
            'where' => [
//                ['company_id', $company_id],
                ['parent_id', $pid],
            ],
            'select' => [
                'id','city_name','sort_num'
                //,'operate_staff_id','operate_staff_history_id'
            ],
            'orderBy' => ['sort_num'=>'desc','id'=>'asc'],
        ];// 查询条件参数
        // $relations = ['CompanyInfo'];// 关系
        $relations = '';//['CompanyInfo'];// 关系
        $result = static::getBaseListData($request, $controller, '', $queryParams, $relations , $oprateBit, $notLog);
        // 格式化数据
//        $data_list = $result['data_list'] ?? [];
//        foreach($data_list as $k => $v){
//            // 公司名称
//            $data_list[$k]['company_name'] = $v['company_info']['company_name'] ?? '';
//            if(isset($data_list[$k]['company_info'])) unset($data_list[$k]['company_info']);
//        }
//        $result['data_list'] = $data_list;
        return ajaxDataArr(1, $result, '');
    }
    // ***********通过组织条件获得kv***结束************************************************************


    /**
     * 根据兑换单号id及收货信息，完成在线提货功能
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param array $saveData 要保存或修改的数组
     * @param int $id id
     * @param int $code_id 兑换码id
     * @param boolean $modifAddOprate 修改时是否加操作人，true:加;false:不加[默认]
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 单条数据 - -维数组 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     * @author zouyan(305463219@qq.com)
     */
    public static function addAddr(Request $request, Controller $controller, $saveData, &$id, $code_id, $modifAddOprate = false, $notLog = 0){
        $company_id = $controller->company_id;

        $user_id = $controller->user_id;
        if(isset($saveData['real_name']) && empty($saveData['real_name'])  ){
            throws('收货人不能为空！');
        }

        if(isset($saveData['tel']) && empty($saveData['tel'])  ){
            throws('收货电话不能为空！');
        }

//        if(isset($saveData['mobile']) && empty($saveData['mobile'])  ){
//            throws('手机不能为空！');
//        }

//        if($id <= 0 && isset($saveData['admin_username']) && empty($saveData['admin_username'])  ){
//            throws('用户名不能为空！');
//        }


        // 调用新加或修改接口
        $apiParams = [
            'saveData' => $saveData,
            'company_id' => $company_id,
            'id' => $id,
            'code_id' => $code_id,
            'operate_staff_id' => $user_id,
            'modifAddOprate' => 0,
        ];
        $id = static::exeDBBusinessMethodCT($request, $controller, '',  'replaceById', $apiParams, $company_id, $notLog);
        return $id;

    }
}