<?php

namespace App\Http\Controllers\Layui\Senior;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class EchartsController extends WorksController
{

    /**
     * 折线图
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function line(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.senior.echarts.line', $reDataArr);
    }

    /**
     * 柱状图
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function bar(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.senior.echarts.bar', $reDataArr);
    }

    /**
     * 地图
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function map(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.senior.echarts.map', $reDataArr);
    }
}
