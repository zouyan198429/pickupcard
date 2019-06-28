<?php

namespace App\Http\Controllers\Layui\Component;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class UploadController extends WorksController
{

    /**
     * 上传
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.component.upload.index', $reDataArr);
    }

    /**
     * 功能演示一
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function demo1(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.component.upload.demo1', $reDataArr);
    }

    /**
     * 功能演示二
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function demo2(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.component.upload.demo2', $reDataArr);
    }
}
