<?php

namespace App\Http\Controllers\Seller;

use App\Business\Controller\API\RunBuy\CTAPICityBusiness;
use App\Business\Controller\API\RunBuy\CTAPIDeliveryAddrBusiness;
use App\Business\Controller\API\RunBuy\CTAPIProductBusiness;
use App\Http\Controllers\WorksController;
use App\Services\Request\CommonRequest;
use App\Services\Tool;
use Illuminate\Http\Request;

class AddrsController extends BasicController
{
    public static $VIEW_NAME = 'addrs';// 视图栏目文件夹目录名称
    /**
     * 首页
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        // $info = CTAPIDeliveryAddrBusiness::getInfoData($request, $this, 1, [], '');
        // pr($info);
        // 获得第一级省一维数组[$k=>$v]
        // $reDataArr['province_kv'] = CTAPICityBusiness::getCityByPid($request, $this,  0);
        // $reDataArr['province_kv'] = CTAPIStaffBusiness::getChildListKeyVal($request, $this, 0, 1 + 0, 0);
        // $reDataArr['province_id'] = 0;

        // 省
        $reDataArr['province_kv'] = CTAPICityBusiness::getCityByPid($request, $this,  0);
        $reDataArr['defaultProvince'] = -1;
        // 状态
        $reDataArr['status'] =  CTAPIDeliveryAddrBusiness::$statusArr;
        $reDataArr['defaultStatus'] = -1;// 默认状态
        // 获得商品信息
        $seller_id = $this->getSellerIdByAdminType();
        $reDataArr['product_kv'] = CTAPIProductBusiness::getListKV($request, $this, 0, $seller_id);

        $reDataArr['defaultProduct'] = -1;// 默认

        return view('' . static::$VIEW_PATH . '.' . static::$VIEW_NAME. '.index', $reDataArr);
    }

    /**
     * 未发货列表
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function addrs_wait_send(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        // $info = CTAPIDeliveryAddrBusiness::getInfoData($request, $this, 1, [], '');
        // pr($info);
        // 获得第一级省一维数组[$k=>$v]
        // $reDataArr['province_kv'] = CTAPICityBusiness::getCityByPid($request, $this,  0);
        // $reDataArr['province_kv'] = CTAPIStaffBusiness::getChildListKeyVal($request, $this, 0, 1 + 0, 0);
        // $reDataArr['province_id'] = 0;

        // 省
        $reDataArr['province_kv'] = CTAPICityBusiness::getCityByPid($request, $this,  0);
        $reDataArr['defaultProvince'] = -1;
        // 状态
        $reDataArr['status'] =  CTAPIDeliveryAddrBusiness::$statusArr;
        $reDataArr['defaultStatus'] = 1;// 默认状态
        // 获得商品信息
        $seller_id = $this->getSellerIdByAdminType();
        $reDataArr['product_kv'] = CTAPIProductBusiness::getListKV($request, $this, 0, $seller_id);

        $reDataArr['defaultProduct'] = -1;// 默认

        return view('' . static::$VIEW_PATH . '.' . static::$VIEW_NAME. '.wait_send', $reDataArr);
    }

    /**
     * 已发货列表
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function addrs_sended(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        // $info = CTAPIDeliveryAddrBusiness::getInfoData($request, $this, 1, [], '');
        // pr($info);
        // 获得第一级省一维数组[$k=>$v]
        // $reDataArr['province_kv'] = CTAPICityBusiness::getCityByPid($request, $this,  0);
        // $reDataArr['province_kv'] = CTAPIStaffBusiness::getChildListKeyVal($request, $this, 0, 1 + 0, 0);
        // $reDataArr['province_id'] = 0;

        // 省
        $reDataArr['province_kv'] = CTAPICityBusiness::getCityByPid($request, $this,  0);
        $reDataArr['defaultProvince'] = -1;
        // 状态
        $reDataArr['status'] =  CTAPIDeliveryAddrBusiness::$statusArr;
        $reDataArr['defaultStatus'] = 2;// 默认状态
        // 获得商品信息
        $seller_id = $this->getSellerIdByAdminType();
        $reDataArr['product_kv'] = CTAPIProductBusiness::getListKV($request, $this, 0, $seller_id);

        $reDataArr['defaultProduct'] = -1;// 默认

        return view('' . static::$VIEW_PATH . '.' . static::$VIEW_NAME. '.sended', $reDataArr);
    }

    /**
     * 同事选择-弹窗
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function select(Request $request)
//    {
//        $this->InitParams($request);
//        $reDataArr = $this->reDataArr;
//        $reDataArr['province_kv'] = CTAPIDeliveryAddrBusiness::getStaffByPid($request, $this,  0);
//        $reDataArr['province_kv'] = CTAPIDeliveryAddrBusiness::getChildListKeyVal($request, $this, 0, 1 + 0, 0);
//        $reDataArr['province_id'] = 0;
//        return view('' . static::$VIEW_PATH . '.' . static::$VIEW_NAME. '.select', $reDataArr);
//    }

    /**
     * 添加
     *
     * @param Request $request
     * @param int $id
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function add(Request $request,$id = 0)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        $info = [
            'id'=>$id,
        ];
        $operate = "添加";

        if ($id > 0) { // 获得详情数据
            $operate = "修改";
            $info = CTAPIDeliveryAddrBusiness::getInfoData($request, $this, $id, [], '');
            $this->isOwnSellerId($info);// 有企业id的记录，判断是不是当前企业
        }
        // $reDataArr = array_merge($reDataArr, $resultDatas);
        $reDataArr['info'] = $info;
        $reDataArr['operate'] = $operate;
        $reDataArr['province_kv'] = CTAPICityBusiness::getCityByPid($request, $this,  0);
//        $reDataArr['province_kv'] = CTAPIDeliveryAddrBusiness::getChildListKeyVal($request, $this, 0, 1 + 0, 0);
//        $reDataArr['province_id'] = 0;

        // 状态
        $reDataArr['status'] =  CTAPIDeliveryAddrBusiness::$statusArr;
        $reDataArr['defaultStatus'] = $info['status'] ?? -1;// 默认状态

        return view('' . static::$VIEW_PATH . '.' . static::$VIEW_NAME. '.add', $reDataArr);
    }


    /**
     * ajax保存数据
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_save(Request $request)
    {
        $this->InitParams($request);
        $id = CommonRequest::getInt($request, 'id');
        // CommonRequest::judgeEmptyParams($request, 'id', $id);
//        $work_num = CommonRequest::get($request, 'work_num');
//        $department_id = CommonRequest::getInt($request, 'department_id');
//        $group_id = CommonRequest::getInt($request, 'group_id');
//        $position_id = CommonRequest::getInt($request, 'position_id');
        $real_name = CommonRequest::get($request, 'real_name');
//        $sex = CommonRequest::getInt($request, 'sex');
//        $account_status = CommonRequest::getInt($request, 'account_status');
//        $mobile = CommonRequest::get($request, 'mobile');
        $tel = CommonRequest::get($request, 'tel');
//        $qq_number = CommonRequest::get($request, 'qq_number');
        $province_id = CommonRequest::getInt($request, 'province_id');
        $city_id = CommonRequest::getInt($request, 'city_id');
        $area_id = CommonRequest::getInt($request, 'area_id');
        $addr = CommonRequest::get($request, 'addr');
        $status = CommonRequest::getInt($request, 'status');
//        $latitude = CommonRequest::get($request, 'latitude');
//        $longitude = CommonRequest::get($request, 'longitude');
//        $admin_username = CommonRequest::get($request, 'admin_username');
//        $admin_password = CommonRequest::get($request, 'admin_password');
//        $sure_password = CommonRequest::get($request, 'sure_password');

        $saveData = [
//            'admin_type' => 1,
//            'work_num' => $work_num,
//            'department_id' => $department_id,
//            'group_id' => $group_id,
//            'position_id' => $position_id,
            'real_name' => $real_name,
//            'sex' => $sex,
//            'gender' => $sex,
//            'account_status' => $account_status,
//            'mobile' => $mobile,
            'tel' => $tel,
//            'qq_number' => $qq_number,
            'province_id' => $province_id,
            'city_id' => $city_id,
            'area_id' => $area_id,
            'addr' => $addr,
            'status' => $status,
//            'latitude' => $latitude,
//            'longitude' => $longitude,
//            'admin_username' => $admin_username,
        ];
//        if($admin_password != '' || $sure_password != ''){
//            if ($admin_password != $sure_password){
//                return ajaxDataArr(0, null, '密码和确定密码不一致！');
//            }
//            $saveData['admin_password'] = $admin_password;
//        }

        if($id <= 0) {// 新加;要加入的特别字段
            $addNewData = [
                // 'account_password' => $account_password,
            ];
            $this->appSellerId($addNewData); // 有企业id的记录，添加时，加入所属企业id
            $saveData = array_merge($saveData, $addNewData);
        }else{
            $info = CTAPIDeliveryAddrBusiness::getInfoData($request, $this, $id, [], '');
            $this->isOwnSellerId($info);// 有企业id的记录，判断是不是当前企业
        }
        $resultDatas = CTAPIDeliveryAddrBusiness::replaceById($request, $this, $saveData, $id, true);
        return ajaxDataArr(1, $resultDatas, '');
    }

    /**
     * ajax获得列表数据
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_alist(Request $request){
        $this->InitParams($request);
        $request->merge(['admin_type' => 1]);
        $mergeParams = [];
        // 企业后台 有企业id的记录，查询或其它操作时，返回要加入request中的企业id参数，参与查询
        $this->appendSellerIdParams($mergeParams);
        CTAPIDeliveryAddrBusiness::mergeRequest($request, $this, $mergeParams);
        return  CTAPIDeliveryAddrBusiness::getList($request, $this, 2 + 4, [], ['province', 'provinceHistory'
            , 'city', 'cityHistory', 'area', 'areaHistory'
           // , 'oprateStaff', 'oprateStaffHistory'
            , 'productInfo', 'productHistoryInfo'
            , 'activityInfo', 'codeInfo']);// , 'cityPartner', 'seller' , 'shop'
    }

    /**
     * ajax获得列表数据
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function ajax_get_ids(Request $request){
//        $this->InitParams($request);
//        $mergeParams = [];
//        // 企业后台 有企业id的记录，查询或其它操作时，返回要加入request中的企业id参数，参与查询
//        $this->appendSellerIdParams($mergeParams);
//        CTAPIDeliveryAddrBusiness::mergeRequest($request, $this, $mergeParams);
//        $result = CTAPIDeliveryAddrBusiness::getList($request, $this, 1 + 0);
//        $data_list = $result['result']['data_list'] ?? [];
//        $ids = implode(',', array_column($data_list, 'id'));
//        return ajaxDataArr(1, $ids, '');
//    }


    /**
     * 导出
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function export(Request $request){
        $this->InitParams($request);
        $mergeParams = [];
        // 企业后台 有企业id的记录，查询或其它操作时，返回要加入request中的企业id参数，参与查询
        $this->appendSellerIdParams($mergeParams);
        CTAPIDeliveryAddrBusiness::mergeRequest($request, $this, $mergeParams);
        CTAPIDeliveryAddrBusiness::getList($request, $this, 1 + 0, [], ['province', 'provinceHistory'
            , 'city', 'cityHistory', 'area', 'areaHistory'
            // , 'oprateStaff', 'oprateStaffHistory'
            , 'productInfo', 'productHistoryInfo'
            , 'activityInfo', 'codeInfo']);
    }


    /**
     * 导入模版
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function import_template(Request $request){
//        $this->InitParams($request);
//        CTAPIDeliveryAddrBusiness::importTemplate($request, $this);
//    }


    /**
     * 子帐号管理-删除
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_del(Request $request)
    {
        $this->InitParams($request);

        $id = CommonRequest::get($request, 'id');
        // 查询所有记录
        $mergeParams = ['ids' => $id];
        CTAPIDeliveryAddrBusiness::mergeRequest($request, $this, $mergeParams);
        $dataList = CTAPIDeliveryAddrBusiness::getList($request, $this, 1 + 0, [], [])['result']['data_list'] ?? [];
        $this->ListIsOwnSellerId($dataList);// 判断数据权限

        return CTAPIDeliveryAddrBusiness::delAjax($request, $this);
    }

    /**
     * 子帐号管理-发货
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_send(Request $request)
    {
        $this->InitParams($request);
        $id = CommonRequest::get($request, 'id');
        // 数组转为逗号分隔的字符串
        if(is_array($id)) $id = implode(',', $id);
        if(strlen($id) <= 0){
            throws('操作记录标识不能为空！');
        }
        return CTAPIDeliveryAddrBusiness::sendAjax($request, $this, $id);
    }

    /**
     * ajax根据部门id,小组id获得所属部门小组下的员工数组[kv一维数组]
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function ajax_get_child(Request $request){
//        $this->InitParams($request);
//        $parent_id = CommonRequest::getInt($request, 'parent_id');
//        // 获得一级城市信息一维数组[$k=>$v]
//        $childKV = CTAPIDeliveryAddrBusiness::getStaffByPid($request, $this, $parent_id);
//        // $childKV = CTAPIDeliveryAddrBusiness::getChildListKeyVal($request, $this, $parent_id, 1 + 0);
//
//        return  ajaxDataArr(1, $childKV, '');;
//    }


    // 导入员工信息
//    public function ajax_import(Request $request){
//        $this->InitParams($request);
//        $fileName = 'staffs.xlsx';
//        $resultDatas = CTAPIDeliveryAddrBusiness::importByFile($request, $this, $fileName);
//        return ajaxDataArr(1, $resultDatas, '');
//    }

    /**
     * 单文件上传-导入excel
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function import(Request $request)
//    {
//        $this->InitParams($request);
//        // 上传并保存文件
//        $result = Resource::fileSingleUpload($request, $this, 1);
//        if($result['apistatus'] == 0) return $result;
//        // 文件上传成功
//        $fileName = Tool::getPath('public') . '/' . $result['result']['filePath'];
//        $resultDatas = CTAPIDeliveryAddrBusiness::importByFile($request, $this, $fileName);
//        return ajaxDataArr(1, $resultDatas, '');
//    }
}
