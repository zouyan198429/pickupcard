<?php

namespace App\Http\Controllers\Layui;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class HomeController extends WorksController
{


    /**
     * 控制台
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function console(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.home.console', $reDataArr);
    }

    /**
     * 主页一
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function homepage1(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.home.homepage1', $reDataArr);
    }

    /**
     * 主页二
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function homepage2(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.home.homepage2', $reDataArr);
    }
}
