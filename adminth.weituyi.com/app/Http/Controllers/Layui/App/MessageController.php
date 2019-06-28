<?php

namespace App\Http\Controllers\Layui\App;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class MessageController extends WorksController
{

    /**
     * 消息中心
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.app.message.index', $reDataArr);
    }

    /**
     * 消息详情标题
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function detail(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.app.message.detail', $reDataArr);
    }
}
