<?php

namespace App\Http\Controllers\Company;

use App\Business\Controller\API\RunBuy\CTAPIActivityCodeBusiness;
use App\Http\Controllers\WorksController;
use App\Services\Request\CommonRequest;
use App\Services\Tool;
use Illuminate\Http\Request;

class CodesController extends BasicController
{
    public static $VIEW_NAME = 'codes';// 视图栏目文件夹目录名称
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
        // 状态
        $reDataArr['status'] =  CTAPIActivityCodeBusiness::$statusArr;
        $reDataArr['defaultStatus'] = -1;// 默认状态

        // 启用状态
        $reDataArr['openStatus'] =  CTAPIActivityCodeBusiness::$openStatusArr;
        $reDataArr['defaultOpenStatus'] = -1;// 默认状态

        $reDataArr['activity_id'] =  CommonRequest::getInt($request, 'activity_id');
        return view('' . static::$VIEW_PATH . '.' . static::$VIEW_NAME. '.index', $reDataArr);
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
//        $reDataArr['province_kv'] = CTAPIActivityCodeBusiness::getCityByPid($request, $this,  0);
//        $reDataArr['province_kv'] = CTAPIActivityCodeBusiness::getChildListKeyVal($request, $this, 0, 1 + 0, 0);
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
//    public function add(Request $request,$id = 0)
//    {
//        $this->InitParams($request);
//        $reDataArr = $this->reDataArr;
//        $info = [
//            'id'=>$id,
//          //   'department_id' => 0,
//        ];
//        $operate = "添加";
//
//        if ($id > 0) { // 获得详情数据
//            $operate = "修改";
//            $info = CTAPIActivityCodeBusiness::getInfoData($request, $this, $id, [], '');
//            $this->isOwnSellerId($info);// 有企业id的记录，判断是不是当前企业
//        }else{
//            $info = CTAPIActivityCodeBusiness::getInfoData($request, $this, $id, [], '');
//            $this->isOwnSellerId($info);// 有企业id的记录，判断是不是当前企业
//        }
//        // $reDataArr = array_merge($reDataArr, $resultDatas);
//        $reDataArr['info'] = $info;
//        $reDataArr['operate'] = $operate;
//        return view('' . static::$VIEW_PATH . '.' . static::$VIEW_NAME. '.add', $reDataArr);
//    }


    /**
     * ajax保存数据
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
//    public function ajax_save(Request $request)
//    {
//        $this->InitParams($request);
//        $id = CommonRequest::getInt($request, 'id');
//        // CommonRequest::judgeEmptyParams($request, 'id', $id);
//        $type_name = CommonRequest::get($request, 'type_name');
//        $sort_num = CommonRequest::getInt($request, 'sort_num');
//
//        $saveData = [
//            'type_name' => $type_name,
//            'sort_num' => $sort_num,
//        ];
//
////        if($id <= 0) {// 新加;要加入的特别字段
////            $addNewData = [
////                // 'account_password' => $account_password,
////            ];
//                $this->appSellerId($addNewData); // 有企业id的记录，添加时，加入所属企业id
////            $saveData = array_merge($saveData, $addNewData);
////        }
//        $resultDatas = CTAPIActivityCodeBusiness::replaceById($request, $this, $saveData, $id, true);
//        return ajaxDataArr(1, $resultDatas, '');
//    }

    /**
     * ajax获得列表数据
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_alist(Request $request){
        $this->InitParams($request);
        $mergeParams = [];
        // 企业后台 有企业id的记录，查询或其它操作时，返回要加入request中的企业id参数，参与查询
        $this->appendSellerIdParams($mergeParams);
        CTAPIActivityCodeBusiness::mergeRequest($request, $this, $mergeParams);
        return  CTAPIActivityCodeBusiness::getList($request, $this, 2 + 4, [], ['oprateStaff', 'oprateStaffHistory']);
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
//        CTAPIActivityCodeBusiness::mergeRequest($request, $this, $mergeParams);
//        $result = CTAPIActivityCodeBusiness::getList($request, $this, 1 + 0);
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
        CTAPIActivityCodeBusiness::mergeRequest($request, $this, $mergeParams);
        CTAPIActivityCodeBusiness::getList($request, $this, 1 + 0, [], ['activityInfo', 'oprateStaff', 'oprateStaffHistory']);
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
//        CTAPIActivityCodeBusiness::importTemplate($request, $this);
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
        CTAPIActivityCodeBusiness::mergeRequest($request, $this, $mergeParams);
        $dataList = CTAPIActivityCodeBusiness::getList($request, $this, 1 + 0, [], [])['result']['data_list'] ?? [];
        $this->ListIsOwnSellerId($dataList);// 判断数据权限

        return CTAPIActivityCodeBusiness::delAjax($request, $this);
    }

    /**
     * 子帐号管理-开启所有[根据活动id]
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_open_all(Request $request)
    {
        $this->InitParams($request);
        $result = CTAPIActivityCodeBusiness::openALLAjax($request, $this,2);
        return ajaxDataArr(1, $result, '');
    }

    /**
     * 子帐号管理-开启
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_open(Request $request)
    {
        $this->InitParams($request);

        $id = CommonRequest::get($request, 'id');
        // 查询所有记录
        $mergeParams = ['ids' => $id];
        CTAPIActivityCodeBusiness::mergeRequest($request, $this, $mergeParams);
        $dataList = CTAPIActivityCodeBusiness::getList($request, $this, 1 + 0, [], [])['result']['data_list'] ?? [];
        $this->ListIsOwnSellerId($dataList);// 判断数据权限

        $result = CTAPIActivityCodeBusiness::openAjax($request, $this,2);
        return ajaxDataArr(1, $result, '');
    }

    /**
     * 子帐号管理-关闭所有[根据活动id]
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_close_all(Request $request)
    {
        $this->InitParams($request);
        $result = CTAPIActivityCodeBusiness::openALLAjax($request, $this,1);
        return ajaxDataArr(1, $result, '');
    }

    /**
     * 子帐号管理-关闭
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_close(Request $request)
    {
        $this->InitParams($request);

        $id = CommonRequest::get($request, 'id');
        // 查询所有记录
        $mergeParams = ['ids' => $id];
        CTAPIActivityCodeBusiness::mergeRequest($request, $this, $mergeParams);
        $dataList = CTAPIActivityCodeBusiness::getList($request, $this, 1 + 0, [], [])['result']['data_list'] ?? [];
        $this->ListIsOwnSellerId($dataList);// 判断数据权限

        $result = CTAPIActivityCodeBusiness::openAjax($request, $this,1);
        return ajaxDataArr(1, $result, '');
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
//        $childKV = CTAPIActivityCodeBusiness::getCityByPid($request, $this, $parent_id);
//        // $childKV = CTAPIActivityCodeBusiness::getChildListKeyVal($request, $this, $parent_id, 1 + 0);
//
//        return  ajaxDataArr(1, $childKV, '');;
//    }


    // 导入员工信息
//    public function ajax_import(Request $request){
//        $this->InitParams($request);
//        $fileName = 'staffs.xlsx';
//        $resultDatas = CTAPIActivityCodeBusiness::importByFile($request, $this, $fileName);
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
//        $resultDatas = CTAPIActivityCodeBusiness::importByFile($request, $this, $fileName);
//        return ajaxDataArr(1, $resultDatas, '');
//    }
}
