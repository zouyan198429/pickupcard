<?php

namespace App\Http\Controllers\Manage;

use App\Business\Controller\API\RunBuy\CTAPICityBusiness;
use App\Business\Controller\API\RunBuy\CTAPIStaffBusiness;
use App\Http\Controllers\WorksController;
use App\Services\Request\CommonRequest;
use App\Services\Tool;
use Illuminate\Http\Request;

class SellerController extends StaffController
{
    public static $ADMIN_TYPE = 2;// 类型1平台2企业4个人
    public static $VIEW_NAME = 'seller';// 视图文件夹名称

}
