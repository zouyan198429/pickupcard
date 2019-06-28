<?php
// 人员
namespace App\Business\Controller\API\RunBuy;

use App\Business\API\RunBuy\CityAPIBusiness;
use App\Business\API\RunBuy\CityHistoryAPIBusiness;
use App\Business\API\RunBuy\StaffHistoryAPIBusiness;
use App\Services\Excel\ImportExport;
use App\Services\Request\API\HttpRequest;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Services\Request\CommonRequest;
use App\Http\Controllers\BaseController as Controller;
class CTAPIStaffBusiness extends BasicPublicCTAPIBusiness
{
    public static $model_name = 'API\RunBuy\StaffAPI';

    // 拥有者类型1平台2城市分站4城市代理8商家16店铺32快跑人员64用户
    public static $adminType = [
        '1' => '平台',
       // '2' => '城市分站',
        '4' => '城市代理',
        '8' => '商家',
        '16' => '店铺',
        '32' => '快跑人员',
        '64' => '用户',
    ];

    // 状态 0正常 1冻结
    public static $accountStatus = [
        '0' => '正常',
        '1' => '冻结',
    ];

    // 审核状态1待审核2审核通过3审核未通过--32快跑人员用
    public static $openStatus = [
        '1' => '待审核',
        '2' => '已通过',
        '3' => '未通过',
    ];

    /**
     * 登录
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $admin_type 拥有者类型1平台2城市分站4城市代理8商家16店铺32快跑人员64用户 可以写操作
     *
     * @return  array 用户数组
     * @author zouyan(305463219@qq.com)
     */
    public static function login(Request $request, Controller $controller, $admin_type = 0){
        $admin_username = CommonRequest::get($request, 'admin_username');
        $admin_password = CommonRequest::get($request, 'admin_password');
        $preKey = CommonRequest::get($request, 'preKey');// 0 小程序 1后台
        if(!is_numeric($preKey)){
            $preKey = 1;
        }
//        $preKey = Common::get($request, 'preKey');// 0 小程序 1后台
//        if(!is_numeric($preKey)){
//            $preKey = 1;
//        }
        // 数据验证 TODO
        // $company_id = config('public.company_id');
        $queryParams = [
            'where' => [
                // ['company_id',$company_id],
                ['admin_username',$admin_username],
                ['admin_password',md5($admin_password)],
            ],
//            'whereIn' => [
//                'admin_type' => array_keys(self::$adminType),
//            ],
            // 'select' => ['id','company_id','admin_username','real_name','admin_type'],
            // 'limit' => 1
        ];
        $pageParams = [
            'page' =>1,
            'pagesize' => 1,
            'total' => 1,
        ];
        if($admin_type >= 64){
            // array_push($queryParams['where'], ['admin_type', '&' , '64=64']);
        }else{
            array_push($queryParams['where'], ['admin_type', $admin_type]);
        }

        $relations = ['city', 'cityPartner', 'seller', 'shop'];
        $userInfo = static::getInfoQuery($request, $controller, '', 0, 1, $queryParams, $relations, 1);
        if(empty($userInfo) || count($userInfo) <= 0 || empty($userInfo)){
            throws('用户名或密码有误！');
        }
        if($admin_username != $userInfo['admin_username']) throws('用户名或密码有误！');
        if($userInfo['account_status'] == 1 ) throws('用户已冻结！');
        $staffCity = $userInfo['city'] ?? [];// 城市分站
        $staffCityPartner = $userInfo['city_partner'] ?? [];// 城市代理
        $staffSeller = $userInfo['seller'] ?? [];// 商家
        $staffShop = $userInfo['shop'] ?? [];// 店铺
        // 拥有者类型1平台2城市分站4城市代理8商家16店铺32快跑人员64用户
        switch ($userInfo['admin_type'])
        {
            case 1:// 平台
                break;
            case 2:// 城市分站
                break;
            case 4:// 城市代理
                if(empty($staffCityPartner))  throws('城市代理信息不存在！');
                if($staffCityPartner['status'] != 1)  throws('不是审核通过状态！');
                break;
            case 8:// 商家
                if(empty($staffSeller))  throws('商家信息不存在！');
                if($staffSeller['status'] != 1)  throws('不是审核通过状态！');
                break;
            case 16:// 店铺
                if(empty($staffShop))  throws('店铺信息不存在！');
                if($staffShop['status'] != 1)  throws('不是审核通过状态！');
                break;
            case 32:// 快跑人员
                break;
            case 64:// 用户
                break;
            default:
        }

//        $admin_type = $userInfo['admin_type'] ?? '';
//        if($admin_type != 2)  throws('您不是超级管理员，没有权限访问！');

        $saveData = [
            'lastlogintime' => date('Y-m-d H:i:s',time()),
        ];
        static::saveByIdApi($request, $controller,'', $userInfo['id'], $saveData, $userInfo['id'], 1);

        $userInfo['modifyTime'] = time();
        // 保存session
        // 存储数据到session...
//        if (!session_id()) session_start(); // 初始化session
//        $_SESSION['userInfo'] = $userInfo; //保存某个session信息

        // 保存session
        // 存储数据到session...
        if (!session_id()) session_start(); // 初始化session
        // $_SESSION['userInfo'] = $userInfo; //保存某个session信息
        $redisKey = $controller->setUserInfo($userInfo, $preKey);
        $userInfo['redisKey'] = $redisKey;

        return ajaxDataArr(1, $userInfo, '');
    }

    /**
     * 退出登录
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @author zouyan(305463219@qq.com)
     */
    public static function loginOut(Request $request, Controller $controller){
//        if(isset($_SESSION['userInfo'])){
//            unset($_SESSION['userInfo']); //保存某个session信息
//        }
        return $controller->delUserInfo();
    }

    /**
     * 修改密码
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @author zouyan(305463219@qq.com)
     */
    public static function modifyPassWord(Request $request, Controller $controller){

        // $id = Common::getInt($request, 'id');
        // Common::judgeEmptyParams($request, 'id', $id);
        $id = $controller->user_id;
        $company_id = $controller->company_id;
        $old_password = CommonRequest::get($request, 'old_password');// 旧密码，如果为空，则不校验
        $admin_password = CommonRequest::get($request, 'admin_password');
        $sure_password = CommonRequest::get($request, 'sure_password');

        if (empty($admin_password) || $admin_password != $sure_password){
            return ajaxDataArr(0, null, '密码和确定密码不一致！');
        }

        $saveData = [
            'admin_password' => $admin_password,
        ];

        // 修改
        // 判断权限
//        $judgeData = [
//           // 'company_id' => $company_id,
//            'id' => $company_id,
//        ];
//        $relations = '';
//        static::judgePower($request, $controller, $id, $judgeData, '', $company_id, $relations);
        // 如果有旧密码，则验证旧密码是否正确
        if(!empty($old_password)){
            $queryParams = [
                'where' => [
                    ['id',$id],
                    ['admin_password',md5($old_password)],
                ],
                // 'limit' => 1
            ];
            $relations = '';
            $infoData = static::getInfoQuery($request, $controller, '', $company_id, 1, $queryParams, $relations);
            if(empty($infoData)){
                return ajaxDataArr(0, null, '原始密码不正确！');
            }
        }
        $resultDatas = static::saveByIdApi($request, $controller,'', $id, $saveData, $company_id);
        return ajaxDataArr(1, $resultDatas, '');
    }

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
//            ],
//            'select' => [
//                'id','company_id','position_name','sort_num'
//                //,'operate_staff_id','operate_staff_id_history'
//                ,'created_at'
            ],
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
            $admin_type = CommonRequest::getInt($request, 'admin_type');
            if($admin_type > 0 )  array_push($queryParams['where'], ['admin_type', '=', $admin_type]);

            $city_site_id = CommonRequest::getInt($request, 'city_site_id');
            if($city_site_id > 0 )  array_push($queryParams['where'], ['city_site_id', '=', $city_site_id]);

            $city_partner_id = CommonRequest::getInt($request, 'city_partner_id');
            if($city_partner_id > 0 )  array_push($queryParams['where'], ['city_partner_id', '=', $city_partner_id]);

            $seller_id = CommonRequest::getInt($request, 'seller_id');
            if($seller_id > 0 )  array_push($queryParams['where'], ['seller_id', '=', $seller_id]);

            $shop_id = CommonRequest::getInt($request, 'shop_id');
            if($shop_id > 0 )  array_push($queryParams['where'], ['shop_id', '=', $shop_id]);

            $account_status = CommonRequest::get($request, 'account_status');
            if(is_numeric($account_status) && $account_status >= 0 )  array_push($queryParams['where'], ['account_status', '=', $account_status]);

            $open_status = CommonRequest::get($request, 'open_status');
            if(is_numeric($open_status) && $open_status >= 0 )  array_push($queryParams['where'], ['open_status', '=', $open_status]);


            $province_id = CommonRequest::getInt($request, 'province_id');
            if($province_id > 0 )  array_push($queryParams['where'], ['province_id', '=', $province_id]);

            $city_id = CommonRequest::getInt($request, 'city_id');
            if($city_id > 0 )  array_push($queryParams['where'], ['city_id', '=', $city_id]);

            $area_id = CommonRequest::getInt($request, 'area_id');
            if($area_id > 0 )  array_push($queryParams['where'], ['area_id', '=', $area_id]);

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
            // if(isset($data_list[$k]['province']) && !is_string($data_list[$k]['province'])) unset($data_list[$k]['province']);
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
            if(isset($data_list[$k]['city'])){
                if(!is_string($data_list[$k]['city'])) unset($data_list[$k]['city']);
            }
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
            // 城市分站名称
            $data_list[$k]['site_name'] = $v['cityinfo']['city_name'] ?? '';
            // $data_list[$k]['site_id'] = $v['cityinfo']['id'] ?? 0;
            if(isset($data_list[$k]['cityinfo'])) unset($data_list[$k]['cityinfo']);
            // 城市城市合伙人
            $data_list[$k]['partner_name'] = $v['city_partner']['partner_name'] ?? '';
            // $data_list[$k]['partner_id'] = $v['city_partner']['id'] ?? 0;
            if(isset($data_list[$k]['city_partner'])) unset($data_list[$k]['city_partner']);
            // 商家
            $data_list[$k]['seller_name'] = $v['seller']['seller_name'] ?? '';
            // $data_list[$k]['seller_id'] = $v['seller']['id'] ?? 0;
            if(isset($data_list[$k]['seller'])) unset($data_list[$k]['seller']);
            // 铺店
            $data_list[$k]['shop_name'] = $v['shop']['shop_name'] ?? '';
            // $data_list[$k]['shop_id'] = $v['shop']['id'] ?? 0;
            if(isset($data_list[$k]['shop'])) unset($data_list[$k]['shop']);
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
     * @return  array 单条数据 - -维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function getInfoData(Request $request, Controller $controller, $id, $selectParams = [], $relations = '', $notLog = 0){
        $company_id = $controller->company_id;
        // $relations = '';
        // $info = APIRunBuyRequest::getinfoApi(self::$model_name, '', $relations, $company_id , $id);
        $info = static::getInfoDataBase($request, $controller,'', $id, $selectParams, $relations, $notLog);
        // 判断权限
//        $judgeData = [
//            'company_id' => $company_id,
//        ];
//        static::judgePowerByObj($request, $controller, $info, $judgeData );
        // 城市代理
        $partner_name = $info['city_partner_history']['partner_name'] ?? '';
        if(empty($partner_name)) $partner_name = $info['city_partner']['partner_name'] ?? '';
        $info['partner_name'] = $partner_name;
        $now_partner_state = 0;// 最新的城市代理 0没有变化 ;1 已经删除  2 试卷不同
        if(isset($info['city_partner_history']) && isset($info['city_partner'])){
            $history_version_num = $info['city_partner_history']['version_num'] ?? '';
            $version_num = $info['city_partner']['version_num'] ?? '';
            if(empty($info['city_partner'])){
                $now_partner_state = 1;
            }elseif($version_num != '' && $history_version_num != $version_num){
                $now_partner_state = 2;
            }
        }
        if(isset($info['city_partner_history'])) unset($info['city_partner_history']);
        if(isset($info['city_partner'])) unset($info['city_partner']);
        $info['now_partner_state'] = $now_partner_state;
        // 商家
        $seller_name = $info['seller_history']['seller_name'] ?? '';
        if(empty($seller_name)) $seller_name = $info['seller']['seller_name'] ?? '';
        $info['seller_name'] = $seller_name;
        $now_seller_state = 0;// 最新的商家 0没有变化 ;1 已经删除  2 试卷不同
        if(isset($info['seller_history']) && isset($info['seller'])){
            $history_version_num = $info['seller_history']['version_num'] ?? '';
            $version_num = $info['seller']['version_num'] ?? '';
            if(empty($info['seller'])){
                $now_seller_state = 1;
            }elseif($version_num != '' && $history_version_num != $version_num){
                $now_seller_state = 2;
            }
        }
        if(isset($info['seller_history'])) unset($info['seller_history']);
        if(isset($info['seller'])) unset($info['seller']);
        $info['now_seller_state'] = $now_seller_state;
        // 店铺
        $shop_name = $info['shop_history']['shop_name'] ?? '';
        if(empty($shop_name)) $shop_name = $info['shop']['shop_name'] ?? '';
        $info['shop_name'] = $shop_name;
        $now_shop_state = 0;// 最新的商家 0没有变化 ;1 已经删除  2 试卷不同
        if(isset($info['shop_history']) && isset($info['shop'])){
            $history_version_num = $info['shop_history']['version_num'] ?? '';
            $version_num = $info['shop']['version_num'] ?? '';
            if(empty($info['shop'])){
                $now_shop_state = 1;
            }elseif($version_num != '' && $history_version_num != $version_num){
                $now_shop_state = 2;
            }
        }
        if(isset($info['shop_history'])) unset($info['shop_history']);
        if(isset($info['shop'])) unset($info['shop']);
        $info['now_shop_state'] = $now_shop_state;
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
//                ['company_id', $company_id],
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
        $id = CommonRequest::getInt($request, 'id');
        $company_id = $controller->company_id;
        // 超级管理员不能删除
        $info = static::getInfoDataBase($request, $controller,'', $id, [], '', $notLog);
        if( empty($info)) throws('记录不存在!');
        if($info['issuper'] == 1) throws('超级帐户不可删除!');

        return static::delAjaxBase($request, $controller, '', $notLog);

    }


    /**
     * 根据id新加或修改单条数据-id 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param array $saveData 要保存或修改的数组
     *    operate_type 可有 操作类型 1 提交申请修改信息 ;2 审核通过 3 审核不通过 4 冻结 5 解冻 6 上班 7 下班
     * @param int $id id
     * @param boolean $modifAddOprate 修改时是否加操作人，true:加;false:不加[默认]
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 单条数据 - -维数组 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     * @author zouyan(305463219@qq.com)
     */
    public static function replaceById(Request $request, Controller $controller, $saveData, &$id, $modifAddOprate = false, $notLog = 0){
        $company_id = $controller->company_id;
        $user_id = $controller->user_id;
        if(!is_numeric($user_id) || $user_id <= 0) $user_id = 0;

        $real_name = $saveData['real_name'] ?? '';
        $mobile = $saveData['mobile'] ?? '';
        $admin_username = $saveData['admin_username'] ?? '';

        if(isset($saveData['real_name']) && empty($saveData['real_name'])  ){
            throws('真实姓名不能为空！');
        }

        if(isset($saveData['mobile']) && empty($saveData['mobile'])  ){
            throws('手机不能为空！');
        }

        if(isset($saveData['admin_username']) && empty($saveData['admin_username'])  ){
            throws('用户名不能为空！');
        }

        // 调用新加或修改接口
        $apiParams = [
            'saveData' => $saveData,
            'company_id' => $company_id,
            'id' => $id,
            'operate_staff_id' => $user_id,
            'modifAddOprate' => 0,
        ];
        $methodName = 'replaceById';
        if(isset($saveData['mini_openid']))  $methodName = 'replaceByIdWX';
        $saveData = static::exeDBBusinessMethodCT($request, $controller, '',  $methodName, $apiParams, $company_id, $notLog);
        /*
        // 查询手机号是否已经有企业使用--账号表里查
        if( isset($saveData['mobile']) && self::judgeFieldExist($request, $controller, $id ,"mobile", $saveData['mobile'], $notLog)){
            throws('手机号已存在！');
        }
        // 用户名
        if( isset($saveData['admin_username']) && self::judgeFieldExist($request, $controller, $id ,"admin_username", $saveData['admin_username'], $notLog)){
            throws('用户名已存在！');
        }

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
//            $addNewData = [
//                'company_id' => $company_id,
//            ];
//            $saveData = array_merge($saveData, $addNewData);
            // 加入操作人员信息
            static::addOprate($request, $controller, $saveData);
        }

        // 省id历史
        if( isset($saveData['province_id']) && $saveData['province_id'] > 0 ){
            $province_id = $saveData['province_id'];
            $province_id_history = CTAPICityBusiness::getHistoryId($request, $controller, '', $province_id, CityHistoryAPIBusiness::$model_name,
                CityHistoryAPIBusiness::$table_name, ['city_table_id' => $province_id], [], $company_id , $notLog);
            $saveData['province_id_history'] = $province_id_history;
        }
        // 市id历史
        if( isset($saveData['city_id']) && $saveData['city_id'] > 0 ){
            $city_id = $saveData['city_id'];
            $city_id_history = CTAPICityBusiness::getHistoryId($request, $controller, '', $city_id, CityHistoryAPIBusiness::$model_name,
                CityHistoryAPIBusiness::$table_name, ['city_table_id' => $city_id], [], $company_id , $notLog);
            $saveData['city_id_history'] = $city_id_history;
        }
        // 区县id历史
        if( isset($saveData['area_id']) && $saveData['area_id'] > 0 ){
            $area_id = $saveData['area_id'];
            $area_id_history = CTAPICityBusiness::getHistoryId($request, $controller, '', $area_id, CityHistoryAPIBusiness::$model_name,
                CityHistoryAPIBusiness::$table_name, ['city_table_id' => $area_id], [], $company_id , $notLog);
            $saveData['area_id_history'] = $area_id_history;
        }

        // 新加或修改
        $result = static::replaceByIdBase($request, $controller, '', $saveData, $id, $notLog);

        if($isModify){
            // 判断版本号是否要+1
            $historySearch = [
               //  'company_id' => $company_id,
                'staff_id' => $id,
            ];
            static::compareHistoryOrUpdateVersion($request, $controller, '' , $id, StaffHistoryAPIBusiness::$model_name
                , 'staff_history', $historySearch, ['staff_id'], 1, $company_id);
        }
        */


        // 更新登陆缓存
        $redisKey = ( is_null($controller->redisKey) || empty($controller->redisKey) ) ? '' : $controller->redisKey;
        if($id > 0 && $controller->user_id == $id){
            $userInfo = $controller->user_info;
            $userInfo = array_merge($userInfo, $saveData);
            if (!empty($redisKey)) $controller->delUserInfo();// 是小程序，则先删除登陆缓存
            // 保存session
            // 存储数据到session...
            if (!session_id()) session_start(); // 初始化session
            // $_SESSION['userInfo'] = $userInfo; //保存某个session信息
            $redisKey = $controller->setUserInfo($userInfo, -1);
        }else{
            $userInfo = $saveData;
        }
        return ['redisKey' => $redisKey, 'result' => $userInfo];
    }

    /**
     * 判断后机号是否已经存在 true:已存在;false：不存在
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $id id
     * @param string $fieldName 需要判断的字段名 mobile  admin_username  work_num
     * @param string $fieldVal 当前要判断的值
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  boolean 单条数据 - -维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function judgeFieldExist(Request $request, Controller $controller, $id ,$fieldName, $fieldVal, $notLog = 0){
        $company_id = $controller->company_id;
        $queryParams = [
            'where' => [
               //  ['company_id', $company_id],
                [$fieldName,$fieldVal],
               // ['admin_type',self::$admin_type],
            ],
            // 'limit' => 1
        ];
        if( is_numeric($id) && $id >0){
            array_push($queryParams['where'],['id', '<>' ,$id]);
        }

        $infoData =  static::getInfoQuery($request, $controller, '', $company_id, 1, $queryParams, '');
        if(empty($infoData) || count($infoData)<=0){
            return false;
        }
        return true;
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
    public static function getStaffByPid(Request $request, Controller $controller, $parent_id = 0, $notLog = 0){
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
     * 根据id可有 操作类型 1 提交申请修改信息 ;2 审核通过 3 审核不通过 4 冻结 5 解冻 6 上班 7 下班
     *  id ；operate_type ； reason
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param boolean $modifAddOprate 修改时是否加操作人，true:加;false:不加[默认]
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  mixed 返回true
     * @author zouyan(305463219@qq.com)
     */
    public static function staffOperateById(Request $request, Controller $controller, $modifAddOprate = false, $notLog = 0){
        $id = CommonRequest::getInt($request, 'id');
        $operate_type = CommonRequest::getInt($request, 'operate_type');
        // operate_type 可有 操作类型 1 提交申请修改信息 ;2 审核通过 3 审核不通过 4 冻结 5 解冻 6 上班 7 下班
        $reason = CommonRequest::get($request, 'reason');// 原因

        $staffInfo = static::getInfoData($request, $controller, $id, ['admin_type', 'open_status', 'account_status'
            , 'on_line', 'city_site_id', 'real_name', 'mobile'], '', $notLog);
        if(empty($staffInfo)) throws('记录不存在');

        $admin_type = $staffInfo['admin_type'] ?? 0;
        if($admin_type != 32) throws('非快跑人员');

        $open_status = $staffInfo['open_status'] ?? 0;// 审核状态1待审核2审核通过3审核未通过--32快跑人员用
        $account_status = $staffInfo['account_status'] ?? 0;// 状态 0正常 1冻结
        $on_line = $staffInfo['on_line'] ?? 0;

        switch ($operate_type)
        {
            case 1://  1 提交申请修改信息 ;
                break;
            case 2:// 2 审核通过
                if($open_status != 1) throws('非待审核状态!');
                if(empty($staffInfo['city_site_id']) || empty($staffInfo['real_name']) || empty($staffInfo['mobile'])) throws('所属城市或真实姓名或手机为空，不能审核通过!');
                $saveData = [
                    'open_status' => 2,
                    'open_fail_reason' => '',
                ];
                break;
            case 3://  3 审核不通过
                if($open_status != 1) throws('非待审核状态!');
                $saveData = [
                    'open_status' => 3,
                    'open_fail_reason' => $reason,
                ];
                break;
            case 4:// 4 冻结
                if($account_status != 0) throws('非解冻状态!');
                $saveData = [
                    'account_status' => 1,
                    'frozen_fail_reason' => $reason,
                ];
                break;
            case 5:// 5 解冻
                if($account_status != 1) throws('非冻结状态!');
                $saveData = [
                    'account_status' => 0,
                    'frozen_fail_reason' => '',
                ];
                break;
            case 6://  6 上班
                if($open_status != 2) throws('非审核通过状态!');
                if($account_status == 1) throws('冻结状态!');
                // if($on_line != 1) throws('非下班状态!');
                $saveData = [
                    'on_line' => 2,
                ];
                break;
            case 7:// 7 下班
                if($open_status != 2) throws('非审核通过状态!');
                if($account_status == 1) throws('冻结状态!');
                // if($on_line != 2) throws('非上班状态!');
                $saveData = [
                    'on_line' => 1,
                ];
                break;
            default:
        }
        // operate_type 可有 操作类型 1 提交申请修改信息 ;2 审核通过 3 审核不通过 4 冻结 5 解冻 6 上班 7 下班
        $saveData['operate_type'] = $operate_type;

        $resultDatas = static::replaceById($request, $controller, $saveData, $id, $modifAddOprate, $notLog);
        return $resultDatas;
    }
}