<?php

namespace App\Http\Controllers\Layui\App;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class ForumController extends WorksController
{

    /**
     * 帖子列表
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function list(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.app.forum.list', $reDataArr);
    }

    /**
     * 回帖列表
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function replys(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.app.forum.replys', $reDataArr);
    }

    /**
     * 帖子管理 iframe 框
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function listform(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.app.forum.listform', $reDataArr);
    }

    /**
     * 回帖管理 iframe 框
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function replysform(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.app.forum.replysform', $reDataArr);
    }
}
