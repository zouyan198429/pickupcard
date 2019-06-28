<?php

namespace App\Http\Controllers\Layui\Component;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class AuxiliarController extends WorksController
{

    /**
     * 辅助
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.component.auxiliar.index', $reDataArr);
    }
}
