<?php

namespace App\Http\Controllers\Layui\Component;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class FormController extends WorksController
{

    /**
     * 表单元素
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function element(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.component.form.element', $reDataArr);
    }

    /**
     * 表单组合
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function group(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.component.form.group', $reDataArr);
    }


}
