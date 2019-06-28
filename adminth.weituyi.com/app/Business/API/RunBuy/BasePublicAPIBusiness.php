<?php
//
namespace App\Business\API\RunBuy;

use App\Business\API\BaseAPIBusiness;

class BasePublicAPIBusiness extends BaseAPIBusiness
{
    public static $model_name = '';// api接口的 模型名
    public static $APIRequestName = 'Sites\APIRunBuyRequest';// 具体的api request请求类名称
    public static $table_name = '';// 表名称

}