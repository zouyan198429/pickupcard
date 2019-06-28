<?php

namespace App\Services\Request;

use Illuminate\Http\Request;

/**
 * 通用工具服务类--HTTP请求数据库操作
 */
class CommonRequest
{
    //写一些通用方法
    /*
    public static function test(){
        echo 'aaa';die;
    }
    */

    // 先从get获取，没有再从post获取
    public static function get(Request $request, $key)
    {
        $value  = $request->get($key) ?: $request->post($key);
        // $value = StringHelper::deepFilterDatas($value, ['trim', 'strip_tags']);
        if(is_null($value)){ $value = '';}
        return $value;
    }

    public static function getInt(Request $request, $key)
    {
        return (int) self::get($request, $key);
    }

    public static function getInts(Request $request, $key)
    {
        $value = self::get($request,$key);

        return is_array($value) ? array_filter(array_map('intval', $value)) : intval($value);
    }

    public static function getBool(Request $request, $key)
    {
        return (bool) self::get($request, $key);
    }

    public static function getPosts(Request $request)
    {
        $params = $request->post() ?? [];

        // 兼容 RAW-JSON
//        if (Yii::$app->request->headers->get('content-type') == 'application/json') {
//            $params = array_merge(
//                $params,
//                json_decode(Yii::$app->request->getRawBody(), true) ?? []
//            );
//        }

        // $params = StringHelper::deepFilterDatas($params, ['trim', 'strip_tags']);

        return $params;
    }


//    public static function outputJson(Request $request, $resp)
//    {
//        header('Content-type: text/json');
//        header('Content-type: application/json; charset=UTF-8');

//        header('Access-Control-Allow-Origin: *');
//        header('Access-Control-Allow-Credentials: true');
//        header('Access-Control-Max-Age: 864000');
//
//        // 允许所有自定义请求头
//        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
//            header('Access-Control-Allow-Headers: ' . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
//        }
//        return response()->json($resp);
    // 创建一个 JSONP 响应
//        return response()
//            ->json($resp)
//            ->withCallback($request->input('callback'));

//    }


    // 获得翻页的三个关键参数
    public static function getPageParams(Request $request){

        // 当前页page,如果不正确默认第一页
        $page = self::getInt($request, 'page');
        if ( (! is_numeric($page)) || $page<=0 ){ $page = 1; }

        // 每页显示的数量,取值1 -- 100 条之间,默认20条
        $pagesize = self::getInt($request, 'pagesize');
        //if ( (! is_numeric($pagesize)) || $pagesize <= 0 || $pagesize > 100 ){ $pagesize = 15; }
        if ( (! is_numeric($pagesize)) || $pagesize <= 0 || $pagesize > 10000 ){ $pagesize = 15; }

        // 总记录数,优化方案：传0传重新获取总数，如果传了，则不会再获取，而是用传的，减软数据库压力
        $total = self::getInt($request, 'total');
        if ( (! is_numeric($total)) || $total<0 ){ $total = 0; }
        return [
            'page' => $page,
            'pagesize' => $pagesize,
            'total' => $total,
        ];
    }


    /**
     * 查询参数处理
     *
     * @return  array :错误信息 array:查询参数及查询关系参数
     * @author zouyan(305463219@qq.com)
     */
    public static function getQueryRelations(Request $request){
        // 查询条件参数
        $queryParams = self::get($request, 'queryParams');
        if(empty($queryParams)) $queryParams = [];
        // json 转成数组
        jsonStrToArr($queryParams , 1, '参数[queryParams]格式有误!');

        // 查询关系参数
        $relations = self::get($request, 'relations');
        if(empty($relations)) $relations = [];
        // json 转成数组
        jsonStrToArr($relations , 1, '参数[relations]格式有误!');

        /*
        $queryParams = [
            'where' => [
                ['id', '>', '0'],
                //  ['phonto_name', 'like', '%知的标题1%']
            ],
            'orderBy' => ['id'=>'desc','company_id'=>'asc'],
            // 'limit' => $pagesize,
            // 'take' => $pagesize,
            // 'offset' => $offset,
            // 'skip' => $offset,
        ];
        $relations = ['siteResources','CompanyInfo.proUnits.proRecords','CompanyInfo.companyType'];
        */
        return [
            'queryParams' => $queryParams,
            'relations' => $relations,
        ];
    }

}
