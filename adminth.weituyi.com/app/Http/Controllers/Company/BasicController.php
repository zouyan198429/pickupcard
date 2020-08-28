<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\WorksController;
use App\Services\Tool;
use Illuminate\Http\Request;

class BasicController extends WorksController
{
    public static $VIEW_PATH = 'company';// 视图文件夹目录名称
    public static $VIEW_NAME = '';// 视图栏目文件夹目录名称
    public static $LOGIN_ADMIN_TYPE = 2;// 当前登录的用户类型1平台2企业4管理员8个人

}
