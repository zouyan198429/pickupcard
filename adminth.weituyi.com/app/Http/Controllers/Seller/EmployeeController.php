<?php

namespace App\Http\Controllers\Seller;

use App\Business\Controller\API\RunBuy\CTAPICityBusiness;
use App\Business\Controller\API\RunBuy\CTAPIStaffBusiness;
use App\Http\Controllers\WorksController;
use App\Services\Request\CommonRequest;
use App\Services\Tool;
use Illuminate\Http\Request;

class EmployeeController extends StaffController
{
    public static $ADMIN_TYPE = 4;// 类型1平台2企业4管理员8个人
    public static $VIEW_NAME = 'employee';// 视图文件夹名称

}
