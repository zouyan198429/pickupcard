<?php

namespace App\Http\Controllers\Manage;

use App\Business\Controller\API\RunBuy\CTAPICommonAddrBusiness;
use App\Http\Controllers\WorksController;
use App\Services\Request\CommonRequest;
use App\Services\Tool;
use Illuminate\Http\Request;

class CommonAddrController extends BasicController
{
    public static $VIEW_NAME = 'commonAddr';// 视图栏目文件夹目录名称
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
//        $reDataArr['province_kv'] = CTAPICommonAddrBusiness::getCityByPid($request, $this,  0);
//        $reDataArr['province_kv'] = CTAPICommonAddrBusiness::getChildListKeyVal($request, $this, 0, 1 + 0, 0);
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
          //   'department_id' => 0,
        ];
        $operate = "添加";

        if ($id > 0) { // 获得详情数据
            $operate = "修改";
            $info = CTAPICommonAddrBusiness::getInfoData($request, $this, $id, [], '');
            $this->isOwnSellerId($info);// 有企业id的记录，判断是不是当前企业
        }
        // $reDataArr = array_merge($reDataArr, $resultDatas);
        $reDataArr['info'] = $info;
        $reDataArr['operate'] = $operate;
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
        $ower_id = CommonRequest::getInt($request, 'ower_id');
        $real_name = CommonRequest::get($request, 'real_name');
        $sex = CommonRequest::getInt($request, 'sex');
        $mobile = CommonRequest::get($request, 'mobile');
        $addr_name = CommonRequest::get($request, 'addr_name');
        $addr = CommonRequest::get($request, 'addr');
        if($addr_name == $addr){
            $addr_name = "";
        }
        $is_default = CommonRequest::getInt($request, 'is_default');
        $latitude = CommonRequest::get($request, 'latitude');
        $longitude = CommonRequest::get($request, 'longitude');

        $saveData = [
            'ower_type' => 64,
            'ower_id' => $ower_id,
            'real_name' => $real_name,
            'sex' => $sex,
            'mobile' => $mobile,
            'addr_name' => $addr_name,
            'addr' => $addr,
            'is_default' => $is_default,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];

        if($id <= 0) {// 新加;要加入的特别字段
            $addNewData = [
                // 'account_password' => $account_password,
            ];
            $this->appSellerId($addNewData); // 有企业id的记录，添加时，加入所属企业id
            $saveData = array_merge($saveData, $addNewData);
        }else{
            $info = CTAPICommonAddrBusiness::getInfoData($request, $this, $id, [], '');
            $this->isOwnSellerId($info);// 有企业id的记录，判断是不是当前企业
        }
        $resultDatas = CTAPICommonAddrBusiness::replaceById($request, $this, $saveData, $id, true);
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
        $mergeParams = [];
        // 企业后台 有企业id的记录，查询或其它操作时，返回要加入request中的企业id参数，参与查询
//        $this->appendSellerIdParams($mergeParams);
        CTAPICommonAddrBusiness::mergeRequest($request, $this, $mergeParams);
        return  CTAPICommonAddrBusiness::getList($request, $this, 2 + 4, [], ['staff']);
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
////        $this->appendSellerIdParams($mergeParams);
//        CTAPICommonAddrBusiness::mergeRequest($request, $this, $mergeParams);
//        $result = CTAPICommonAddrBusiness::getList($request, $this, 1 + 0);
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
//    public function export(Request $request){
//        $this->InitParams($request);
//        $mergeParams = [];
//        // 企业后台 有企业id的记录，查询或其它操作时，返回要加入request中的企业id参数，参与查询
////        $this->appendSellerIdParams($mergeParams);
//        CTAPICommonAddrBusiness::mergeRequest($request, $this, $mergeParams);
//        CTAPICommonAddrBusiness::getList($request, $this, 1 + 0);
//    }


    /**
     * 导入模版
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function import_template(Request $request){
//        $this->InitParams($request);
//        CTAPICommonAddrBusiness::importTemplate($request, $this);
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
        CTAPICommonAddrBusiness::mergeRequest($request, $this, $mergeParams);
        $dataList = CTAPICommonAddrBusiness::getList($request, $this, 1 + 0, [], [])['result']['data_list'] ?? [];
        $this->ListIsOwnSellerId($dataList);// 判断数据权限

        return CTAPICommonAddrBusiness::delAjax($request, $this);
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
//        $childKV = CTAPICommonAddrBusiness::getCityByPid($request, $this, $parent_id);
//        // $childKV = CTAPICommonAddrBusiness::getChildListKeyVal($request, $this, $parent_id, 1 + 0);
//
//        return  ajaxDataArr(1, $childKV, '');;
//    }


    // 导入员工信息
//    public function ajax_import(Request $request){
//        $this->InitParams($request);
//        $fileName = 'staffs.xlsx';
//        $resultDatas = CTAPICommonAddrBusiness::importByFile($request, $this, $fileName);
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
//        $resultDatas = CTAPICommonAddrBusiness::importByFile($request, $this, $fileName);
//        return ajaxDataArr(1, $resultDatas, '');
//    }
}
