<?php

namespace App\Http\Controllers\Layui\Template;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class TipsController extends WorksController
{
    /**
     * 404页面不存在
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function err404(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.template.tips.404', $reDataArr);
    }

    /**
     * 错误提示
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function error(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.template.tips.error', $reDataArr);
    }
}
