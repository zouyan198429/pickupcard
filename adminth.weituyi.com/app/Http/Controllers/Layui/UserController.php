<?php

namespace App\Http\Controllers\Layui;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class UserController extends WorksController
{
    /**
     * 注册
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function reg(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.user.reg', $reDataArr);
    }

    /**
     * 登入
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function login(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.user.login', $reDataArr);
    }

    /**
     * 忘记密码
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function forget(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.user.forget', $reDataArr);
    }
}
