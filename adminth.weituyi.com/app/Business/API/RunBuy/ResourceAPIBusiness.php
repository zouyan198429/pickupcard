<?php
// 资源
namespace App\Business\API\RunBuy;


use App\Services\Tool;

class ResourceAPIBusiness extends BasePublicAPIBusiness
{
    public static $model_name = 'RunBuy\Resource';
    public static $table_name = 'resource';// 表名称
    /**
     * 根据资源id，删除资源及数据表记录
     *
     * @param object $modelObj 当前模型对象
     * @param int $companyId 企业id
     * @param string $queryParams 条件数组/json字符
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function ResourceDelById($id, $companyId = null, $notLog = 0){
        $model_name = static::$model_name;// 'Resource';
        // 获得数据记录
        $relations = ['resourceHistory'];
//        if(is_numeric($companyId) && $companyId > 0){
//            // 判断权限
//            $judgeData = [
//                // 'company_id' => $companyId,
//            ];
//            $info = static::judgePower($id, $judgeData, $model_name, $companyId, $relations, $notLog);
//        }else{
            $info = static::getInfoDataBase($companyId, $id, $model_name,'', $relations, $notLog);
//        }

        if(empty($info)){
            // throws('资源记录[' . $id . ']不存在!', $this->source);
            throws('资源记录[' . $id . ']不存在!');
        }
        // 删除文件---没有使用过，则删除
        $resourceHistory = $info['resource_history'] ?? [];
        if(empty($resourceHistory)) Tool::resourceDelFile([$info]);
        //删除记录
        $queryParams =[// 查询条件参数
            'where' => [
                ['id', $id],
            ]
        ];
        return static::delByQuery($companyId, $model_name, $queryParams, $notLog);
        // return static::delAjaxBase($companyId, $id, $model_name, $notLog);
    }

}