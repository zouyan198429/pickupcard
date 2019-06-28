<?php

namespace App\Http\Controllers\Layui\App;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class MallController extends WorksController
{

    /**
     * 分类管理
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function category(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.app.mall.category', $reDataArr);
    }

    /**
     * 商品列表
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function list(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.app.mall.list', $reDataArr);
    }

    /**
     * 规格管理
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function specs(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.app.mall.specs', $reDataArr);
    }
}
