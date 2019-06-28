<?php

namespace App\Http\Controllers\Layui\User;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class AdministratorsController extends WorksController
{

    /**
     * 后台管理员
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function list(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.user.administrators.list', $reDataArr);
    }

    /**
     * 角色管理
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function role(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.user.administrators.role', $reDataArr);
    }

    /**
     * 管理员 iframe 框
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function adminform(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.user.administrators.adminform', $reDataArr);
    }

    /**
     * 角色管理 iframe 框
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function roleform(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.user.administrators.roleform', $reDataArr);
    }
}
