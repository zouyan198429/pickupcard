<?php

namespace App\Http\Controllers\Admin;

use App\Business\Controller\API\RunBuy\CTAPICityBusiness;
use App\Http\Controllers\WorksController;
use App\Services\Request\CommonRequest;
use App\Services\Tool;
use Illuminate\Http\Request;

class CityController extends WorksController
{
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
        // $info = CTAPICityBusiness::getInfoData($request, $this, 1, [], '');
        // pr($info);
        // 是否城市分站
        $reDataArr['isCitySite'] =  CTAPICityBusiness::$isCitySiteArr;
        $reDataArr['defaultIsCitySite'] = -1;// 列表页默认状态
        // 类型
        $reDataArr['cityType'] =  CTAPICityBusiness::$cityTypeArr;
        $reDataArr['defaultCityType'] = -1;// 默认状态
        // 获得第一级省一维数组[$k=>$v]
         $reDataArr['province_kv'] = CTAPICityBusiness::getCityByPid($request, $this,  0);
        // $reDataArr['province_kv'] = CTAPICityBusiness::getChildListKeyVal($request, $this, 0, 1 + 0, 0);
        $reDataArr['defaultProvinceId'] = -1;// 默认
        return view('admin.city.index', $reDataArr);
    }

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
            $info = CTAPICityBusiness::getInfoData($request, $this, $id, [], '');
            $city_ids = $info['city_ids'] ?? '';
            $pIds = explode(',', $city_ids);
            if(count($pIds) >=3 ) $info['province_id'] = $pIds[0] ?? -1;
            if(count($pIds) >=4 ) $info['city_id'] = $pIds[1] ?? -1;
            if(count($pIds) >=5 ) $info['area_id'] = $pIds[2] ?? -1;

        }
        $reDataArr['operate'] = $operate;
        // 是否城市分站
        $reDataArr['isCitySite'] =  CTAPICityBusiness::$isCitySiteArr;
        $reDataArr['defaultIsCitySite'] = $info['is_city_site'] ?? -1;// 列表页默认状态
        // 类型
        $reDataArr['cityType'] =  CTAPICityBusiness::$cityTypeArr;
        $reDataArr['defaultCityType'] = $info['city_type'] ?? -1;// 默认状态
        // 获得第一级省一维数组[$k=>$v]
        $reDataArr['province_kv'] = CTAPICityBusiness::getCityByPid($request, $this,  0);
        // $reDataArr['province_kv'] = CTAPICityBusiness::getChildListKeyVal($request, $this, 0, 1 + 0, 0);
        $reDataArr['defaultProvinceId'] = $info['province_id'] ?? -1;// 默认

        $reDataArr['info'] = $info;
        return view('admin.city.add', $reDataArr);
    }

    /**
     * 选择-弹窗
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function select(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        // $info = CTAPICityBusiness::getInfoData($request, $this, 1, [], '');
        // pr($info);
        // 是否城市分站
        $reDataArr['isCitySite'] =  CTAPICityBusiness::$isCitySiteArr;
        $reDataArr['defaultIsCitySite'] = 1;// 列表页默认状态
        // 类型
        $reDataArr['cityType'] =  CTAPICityBusiness::$cityTypeArr;
        $reDataArr['defaultCityType'] = -1;// 默认状态
        // 获得第一级省一维数组[$k=>$v]
        $reDataArr['province_kv'] = CTAPICityBusiness::getCityByPid($request, $this,  0);
        // $reDataArr['province_kv'] = CTAPICityBusiness::getChildListKeyVal($request, $this, 0, 1 + 0, 0);
        $reDataArr['defaultProvinceId'] = -1;// 默认
        return view('admin.city.select', $reDataArr);
    }

    /**
     * 选中/更新
     *
     * @param Request $request
     * @param int $id
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_selected(Request $request)
    {
        $this->InitParams($request);
        $id = CommonRequest::getInt($request, 'id');
        $info = CTAPICityBusiness::getInfoHistoryId($request, $this, $id, []);
        return ajaxDataArr(1, $info, '');

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
        $province_id = CommonRequest::getInt($request, 'province_id');
        $city_id = CommonRequest::getInt($request, 'city_id');
        $city_name = CommonRequest::get($request, 'city_name');
        $code = CommonRequest::get($request, 'code');
        $is_city_site = CommonRequest::getInt($request, 'is_city_site');
        $city_type = CommonRequest::getInt($request, 'city_type');
        $latitude = CommonRequest::get($request, 'latitude');
        $longitude = CommonRequest::get($request, 'longitude');
        $sort_num = CommonRequest::getInt($request, 'sort_num');

        $saveData = [
            'city_name' => $city_name,
            'code' => $code,
            'is_city_site' => $is_city_site,
            'city_type' => $city_type,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'sort_num' => $sort_num,

            'province_id' => $province_id,
            'city_id' => $city_id,

        ];

//        if($id <= 0) {// 新加;要加入的特别字段
//            $addNewData = [
//                // 'account_password' => $account_password,
//            ];
//            $saveData = array_merge($saveData, $addNewData);
//        }
        $resultDatas = CTAPICityBusiness::replaceById($request, $this, $saveData, $id, true);
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
        return  CTAPICityBusiness::getList($request, $this, 2 + 4, [], ['feescale']);
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
//        $result = CTAPICityBusiness::getList($request, $this, 1 + 0);
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
//        CTAPICityBusiness::getList($request, $this, 1 + 0);
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
//        CTAPICityBusiness::importTemplate($request, $this);
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
        return CTAPICityBusiness::delAjax($request, $this);
    }

    /**
     * ajax根据部门id,小组id获得所属部门小组下的员工数组[kv一维数组]
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_get_child(Request $request){
        $this->InitParams($request);
        $parent_id = CommonRequest::getInt($request, 'parent_id');
        // 获得一级城市信息一维数组[$k=>$v]
        $childKV = CTAPICityBusiness::getCityByPid($request, $this, $parent_id);
        // $childKV = CTAPICityBusiness::getChildListKeyVal($request, $this, $parent_id, 1 + 0);

        return  ajaxDataArr(1, $childKV, '');;
    }


    // 导入员工信息
//    public function ajax_import(Request $request){
//        $this->InitParams($request);
//        $fileName = 'staffs.xlsx';
//        $resultDatas = CTAPICityBusiness::importByFile($request, $this, $fileName);
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
//        $resultDatas = CTAPICityBusiness::importByFile($request, $this, $fileName);
//        return ajaxDataArr(1, $resultDatas, '');
//    }
}
