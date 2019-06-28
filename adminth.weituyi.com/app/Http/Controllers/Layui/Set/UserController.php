<?php

namespace App\Http\Controllers\Layui\Set;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class UserController extends WorksController
{

    /**
     * 基本资料
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function info(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.set.user.info', $reDataArr);
    }

    /**
     * 修改密码
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function password(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.set.user.password', $reDataArr);
    }
}
