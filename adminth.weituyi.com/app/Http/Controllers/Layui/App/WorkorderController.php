<?php

namespace App\Http\Controllers\Layui\App;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class WorkorderController extends WorksController
{

    /**
     * 工单系统
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function list(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.app.workorder.list', $reDataArr);
    }

    /**
     * 工单管理 iframe 框
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function listform(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.app.workorder.listform', $reDataArr);
    }

}
