<?php

namespace App\Http\Controllers\Layui;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class SystemController extends WorksController
{
    /**
     * 版本信息
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function about(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.system.about', $reDataArr);
    }

    /**
     * 授权获得 layuiAdmin
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function get(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.system.get', $reDataArr);
    }

    /**
     * 更多面板的模板
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function more(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.system.more', $reDataArr);
    }

    /**
     * 主题设置模板
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function theme(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.system.theme', $reDataArr);
    }

}
