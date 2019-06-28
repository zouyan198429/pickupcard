<?php

namespace App\Http\Controllers\Layui\App;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class ContentController extends WorksController
{

    /**
     * 文章列表
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function list(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.app.content.list', $reDataArr);
    }

    /**
     * 分类管理
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function tags(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.app.content.tags', $reDataArr);
    }

    /**
     * 评论管理
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function comment(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.app.content.comment', $reDataArr);
    }

    /**
     * 评论管理 iframe 框
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function contform(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.app.content.contform', $reDataArr);
    }

    /**
     * 文章管理 iframe 框
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function listform(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.app.content.listform', $reDataArr);
    }

    /**
     * 分类管理 iframe 框
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function tagsform(Request $request)
    {
//        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('layui.app.content.tagsform', $reDataArr);
    }
}
