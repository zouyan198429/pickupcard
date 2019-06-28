<?php

namespace App\Business\Controller\API;

use App\Services\Response\Data\CommonAPIFromBusiness;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as Controller;

class BasicCTAPIBusiness extends APIOperate
{
    public static $model_name = '';// 中间层 App\Business\API 下面的表名称 API\RunBuy\CountSenderRegAPI

    /**
     * 修改 Request的值
     *
     * @param array $params 需要修改的键值数组 ['foo' => 'bar', ....]
     * @return null
     * @author zouyan(305463219@qq.com)
     */
    public static function mergeRequest(Request $request, Controller $controller, $params = [])
    {
        // 合并输入，如果有相同的key，用户输入的值会被替换掉，否则追加到 input
         $request->merge($params);

        // 替换所有输入
        // $request->replace($params);
    }

    /**
     * 删除 Request的值
     *
     * @param array $params 需要修改的键值数组 ['foo', 'bar', ....]
     * @return null
     * @author zouyan(305463219@qq.com)
     */
    public static function removeRequest(Request $request, Controller $controller, $params = [])
    {
        foreach($params as $key){
            unset($request[$key]);
        }
    }
}