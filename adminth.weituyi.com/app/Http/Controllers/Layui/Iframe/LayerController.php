<?php

namespace App\Http\Controllers\Layui\Iframe;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class LayerController extends WorksController
{

    /**
     * layer iframe 示例
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function iframe(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.iframe.layer.iframe', $reDataArr);
    }

}
