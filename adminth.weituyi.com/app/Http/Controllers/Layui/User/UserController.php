<?php

namespace App\Http\Controllers\Layui\User;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class UserController extends WorksController
{

    /**
     * 网站用户
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function list(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.user.user.list', $reDataArr);
    }


    /**
     * 网站用户 iframe 框
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function userform(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.user.user.userform', $reDataArr);
    }
}
