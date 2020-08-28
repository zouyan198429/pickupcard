<?php

namespace App\Http\Controllers\Admin;

use App\Business\Controller\API\RunBuy\CTAPICityBusiness;
use App\Business\Controller\API\RunBuy\CTAPIStaffBusiness;
use App\Http\Controllers\WorksController;
use App\Services\Request\CommonRequest;
use App\Services\Tool;
use Illuminate\Http\Request;

class UserController extends StaffController
{
    public static $ADMIN_TYPE = 8;// 类型1平台2企业4管理员8个人
    public static $VIEW_NAME = 'user';// 视图文件夹名称

}
