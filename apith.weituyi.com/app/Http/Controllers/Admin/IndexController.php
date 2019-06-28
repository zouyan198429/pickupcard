<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CompController;
use App\Services\Request\Data\CommonAPIFormModel;
use App\Services\DB\CommonDB;
use App\Services\Request\CommonRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class IndexController extends CompController
{

    /**
     * 批量修改设置
     *
     * @param string $Model_name model名称
     * @param string $primaryKey 主键字段,默认为id
     * @param string $dataParams 主键及要修改的字段值 二维数组 数组/json字符
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function batchSaveByPrimaryKey(Request $request)
    {
        $this->InitParams($request);
        // 获得对象
        CommonAPIFormModel::requestGetObj($request,$modelObj);
        $dataParams = CommonRequest::get($request, 'dataParams');
        // json 转成数组
        jsonStrToArr($dataParams , 1, '参数[dataParams]格式有误!');

        $primaryKey = CommonRequest::get($request, 'primaryKey');
        if(empty($primaryKey)){
            $primaryKey = 'id';
        }
        $successRels = [
            'success' => [],
            'fail' => [],
        ];
        DB::beginTransaction();
        foreach($dataParams as $info){
            // 保存记录
            $id = $info[$primaryKey] ?? '';
            try {
                $temObj = $modelObj;
                $temObj->find($id);
                unset($info[$primaryKey]);
                if(empty($info)){
                    continue;
                }
                foreach($info as $field => $val){
                    $temObj->{$field} = $val;
                }
                $res = $temObj->save();
                array_push($successRels['success'],[$id => $res]);
            } catch ( \Exception $e) {
                DB::rollBack();
                array_push($successRels['fail'],[ 'ids'=> $id,'msg'=>$e->getMessage() ]);
                throws('修改[' . $id . ']失败；信息[' . $e->getMessage() . ']');
                // throws($e->getMessage());
            }
        }
        DB::commit();
        return  okArray($successRels);
    }
}
