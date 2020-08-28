<?php

namespace App\Http\Controllers\Site;

use App\Business\Controller\API\RunBuy\CTAPICityBusiness;
use App\Services\Request\CommonRequest;
use App\Services\Tool;
use Illuminate\Http\Request;

class CityController extends BaseWebController
{
    public static $VIEW_NAME = '';// 视图栏目文件夹目录名称
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
        $childKV = CTAPICityBusiness::getCityByPid($request, $this, $parent_id, 1);
        // $childKV = CTAPICityBusiness::getChildListKeyVal($request, $this, $parent_id, 1 + 0);

        return  ajaxDataArr(1, $childKV, '');;
    }
}
