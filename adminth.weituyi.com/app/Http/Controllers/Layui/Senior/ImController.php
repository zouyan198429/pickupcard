<?php

namespace App\Http\Controllers\Layui\Senior;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class ImController extends WorksController
{

    /**
     * LayIM 社交聊天
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.senior.im.index', $reDataArr);
    }
}
