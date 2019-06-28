<?php
namespace App\Services\Request\API\Sites;

use App\Services\Request\API\APIBasicRequest;
use App\Services\Request\API\HttpRequest;

/**
 *具体业务通用接口类--请求api--公有接口
 */
class APIBaiDuAKRequest //extends APIBasicRequest  第三方其它的，不用继承此类
{

    /**
     * 获得请求地址-- 子类必须重写此方法
     *
     * @return string 接口地址
     * @author zouyan(305463219@qq.com)
     */
    public static function getUrl(){
        return config('public.apiUrl');
    }

    /**
     * 获得百度数据
     *
     * @param array $requestData 请求参数数组

     * @param string $method 请求方法前缀
     * @param string $relations 关系数组/json字符
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function ajaxGetBaiDuData($requestData,$method)
    {
        $requestData['ak'] = config('public.BaiDuAK');
        $requestData['output'] = 'json';
        $url = static::getUrl() . config('public.apiPathBaiDu.' . $method);
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        //return HttpRequest::HttpRequestApi($url, [], $requestData, 'GET');

        $result = HttpRequest::sendHttpRequest($url, [], $requestData, 'GET');

        $resultData = json_decode($result, true);
        $error = $resultData['error'] ?? 0;
        $status = $resultData['status'] ?? '返回数据错误!';
        $data = $resultData['results'] ?? [];
        if ($error != 0){
            // throws('百度接口错误:' . $status);
        }
        if(!is_array($data)){
            $data = [];
        }
        return $data;
    }
}