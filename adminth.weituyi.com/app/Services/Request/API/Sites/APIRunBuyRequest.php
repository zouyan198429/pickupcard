<?php

namespace App\Services\Request\API\Sites;

use App\Services\Request\API\APIBasicRequest;

/**
 *具体业务通用接口类--请求api--公有接口
 */
class APIRunBuyRequest extends APIBasicRequest // 如果是自己的数据库系统，可以继承此公用方法
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

}