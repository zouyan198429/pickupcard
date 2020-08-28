<?php

namespace App\Http\Controllers\Seller;

use App\Business\Controller\API\RunBuy\CTAPIActivityBusiness;
use App\Business\Controller\API\RunBuy\CTAPIProductBusiness;
use App\Http\Controllers\WorksController;
use App\Services\Request\CommonRequest;
use App\Services\Tool;
use Illuminate\Http\Request;

class ActivityController extends BasicController
{
    public static $VIEW_NAME = 'activity';// 视图栏目文件夹目录名称

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
        // 获得商品信息
        $seller_id = $this->getSellerIdByAdminType();
        $reDataArr['product_kv'] = CTAPIProductBusiness::getListKV($request, $this, 0, $seller_id);
        $reDataArr['defaultProduct'] = -1;// 默认
        // 状态
        $reDataArr['status'] =  CTAPIActivityBusiness::$statusArr;
        $reDataArr['defaultStatus'] = -1;// 默认状态

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
//        $reDataArr['province_kv'] = CTAPIActivityBusiness::getCityByPid($request, $this,  0);
//        $reDataArr['province_kv'] = CTAPIActivityBusiness::getChildListKeyVal($request, $this, 0, 1 + 0, 0);
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
            $info = CTAPIActivityBusiness::getInfoData($request, $this, $id, [], ['siteResources']);
            $this->isOwnSellerId($info);// 有企业id的记录，判断是不是当前企业
        }
        // $reDataArr = array_merge($reDataArr, $resultDatas);

        // 获得商品信息
        $seller_id = $this->getSellerIdByAdminType();
        $reDataArr['product_kv'] = CTAPIProductBusiness::getListKV($request, $this, 0, $seller_id);
        $reDataArr['defaultProduct'] = $info['product_id'] ?? -1;// 默认

        // 兑换码生成是是否启用1待启用2直接启用
        $reDataArr['defaultOpenStatus'] = CTAPIActivityBusiness::$defaultOpenStatusArr;
        $reDataArr['defaultDefaultOpenStatus'] = $info['default_open_status'] ?? -1;// 默认


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
        $product_id = CommonRequest::getInt($request, 'product_id');
        $activity_name = CommonRequest::get($request, 'activity_name');
        $activity_tips = CommonRequest::get($request, 'activity_tips');
        $begin_time = CommonRequest::get($request, 'begin_time');
        $end_time = CommonRequest::get($request, 'end_time');
        $begin_num = CommonRequest::getInt($request, 'begin_num');
        $total_num = CommonRequest::getInt($request, 'total_num');
        $code_len = CommonRequest::getInt($request, 'code_len');
        $default_open_status = CommonRequest::getInt($request, 'default_open_status');
//        $sort_num = CommonRequest::getInt($request, 'sort_num');

        // 图片资源
        $resource_id = CommonRequest::get($request, 'resource_id');
        if(is_string($resource_id) || is_numeric($resource_id)){
            $resource_id = explode(',' ,$resource_id);
        }

        $resource_ids = implode(',', $resource_id);
        if(!empty($resource_ids)) $resource_ids = ',' . $resource_ids . ',';



        // 判断时间
        // 判断开始结束日期[ 可为空,有值的话-；4 开始日期 不能大于 >  当前日；32 结束日期 不能大于 >  当前日;256 开始日期 不能大于 >  结束日期]
        Tool::judgeBeginEndDate($begin_time, $end_time, 1 + 2 + 256);
        if( $product_id <= 0 )  return ajaxDataArr(0, null, '请选择所属商品！');
        if( $begin_num <= 0 )  return ajaxDataArr(0, null, '起始编号参数必须>0！');
        if( $total_num <= 0 )  return ajaxDataArr(0, null, '编号数量参数必须>0！');

        $saveData = [
            'product_id' => $product_id,
            'activity_name' => $activity_name,
            'activity_tips' => $activity_tips,
            'begin_time' => $begin_time,
            'end_time' => $end_time,
            'begin_num' => $begin_num,
            'total_num' => $total_num,
            'code_len' => $code_len,
            'default_open_status' => $default_open_status,
//            'sort_num' => $sort_num,
            'resource_ids' => $resource_ids,// 图片资源id串(逗号分隔-未尾逗号结束)
            'resourceIds' => $resource_id,// 此下标为图片资源关系
        ];

        if($id <= 0) {// 新加;要加入的特别字段
            $addNewData = [
                // 'account_password' => $account_password,
            ];
            $this->appSellerId($addNewData); // 有企业id的记录，添加时，加入所属企业id
            $saveData = array_merge($saveData, $addNewData);
        }else{
            $info = CTAPIActivityBusiness::getInfoData($request, $this, $id, [], '');
            $this->isOwnSellerId($info);// 有企业id的记录，判断是不是当前企业
        }
        $resultDatas = CTAPIActivityBusiness::replaceById($request, $this, $saveData, $id, true);
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
        $this->appendSellerIdParams($mergeParams);
        CTAPIActivityBusiness::mergeRequest($request, $this, $mergeParams);
        return  CTAPIActivityBusiness::getList($request, $this, 2 + 4, [], ['productInfo', 'productHistoryInfo', 'oprateStaff', 'oprateStaffHistory', 'siteResources']);
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
//        CTAPIActivityBusiness::mergeRequest($request, $this, $mergeParams);
//        $result = CTAPIActivityBusiness::getList($request, $this, 1 + 0);
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
//        $this->appendSellerIdParams($mergeParams);
//        CTAPIActivityBusiness::mergeRequest($request, $this, $mergeParams);
//        CTAPIActivityBusiness::getList($request, $this, 1 + 0);
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
//        CTAPIActivityBusiness::importTemplate($request, $this);
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
        CTAPIActivityBusiness::mergeRequest($request, $this, $mergeParams);
        $dataList = CTAPIActivityBusiness::getList($request, $this, 1 + 0, [], [])['result']['data_list'] ?? [];
        $this->ListIsOwnSellerId($dataList);// 判断数据权限

        return CTAPIActivityBusiness::delAjax($request, $this);
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
//        $childKV = CTAPIActivityBusiness::getCityByPid($request, $this, $parent_id);
//        // $childKV = CTAPIActivityBusiness::getChildListKeyVal($request, $this, $parent_id, 1 + 0);
//
//        return  ajaxDataArr(1, $childKV, '');;
//    }


    // 导入员工信息
//    public function ajax_import(Request $request){
//        $this->InitParams($request);
//        $fileName = 'staffs.xlsx';
//        $resultDatas = CTAPIActivityBusiness::importByFile($request, $this, $fileName);
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
//        $resultDatas = CTAPIActivityBusiness::importByFile($request, $this, $fileName);
//        return ajaxDataArr(1, $resultDatas, '');
//    }
}
