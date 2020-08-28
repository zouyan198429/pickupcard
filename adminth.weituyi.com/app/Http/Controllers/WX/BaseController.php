<?php

namespace App\Http\Controllers\WX;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseController extends WorksController
{
    public $save_session = false;// true后台来的，false小程序来的
    // 每一种登录项的唯一标识【大后台：adimn; 企业：company;用户：user】,每一种后台控制器父类，修改成自己的唯一值
    //        用途，如加入到登录状态session中，就可以一个浏览器同时登录多个后台。--让每一个后台session的键都唯一，不串（重）
    public $siteLoginUniqueKey = 'wx';
    public $source = 3;// 来源-1网站页面，2ajax；3小程序
    public $company_id = 1;//
}
