<?php

namespace App\Http\Controllers\Layui\Set;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class SystemController extends WorksController
{

    /**
     * 网站设置
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function website(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.set.system.website', $reDataArr);
    }

    /**
     * 邮件服务
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function email(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.set.system.email', $reDataArr);
    }
}
