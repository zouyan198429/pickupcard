<?php

namespace App\Http\Controllers\Layui;

use App\Business\Resource;
use App\Http\Controllers\WorksController;
use App\Services\DB\CommonDB;
use App\Services\Request\API\Sites\APIRunBuyRequest;
use App\Services\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AdminController;

class UploadController extends WorksController
{
    public $model_name = 'Resource';
    // 大后台 admin/年/月/日/文件
    // 企业 company/[生产单元/]年/月/日/文件
    protected $source_path = '/resource/company/';

    /**
     * 文件上传
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        return Resource::filePlupload($request, $this, 0);
    }

    /**
     * 根据资源id，删除资源[删除文件和数据记录]
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_del(Request $request)
    {
        $this->InitParams($request);
        return Resource::delAjax($request, $this);
    }

    /**
     * 文件上传
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function index(Request $request)
//    {
//        $errArr = [
//            'result' => 'failed',// 文件上传失败
//            'message' => '文件内容包含违规内容',//用于在界面上提示用户的消息
//        ];
//        // return $errArr;
//
//        $requestLog = [
//            'file'       =>$request->file('file'),
//            'files'       => $request->file(),
//            'posts'  => $request->post(),
//            'input'      => $request->input(),
//        ];
//        Log::info('上传文件日志',$requestLog);
//
//        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
//            $photo = $request->file('photo');
//            $extension = $photo->extension();
//            //$store_result = $photo->store('photo');
//            $store_result = $photo->storeAs('photo', 'testaaaa.jpg');
//            $output = [
//                'extension' => $extension,
//                'store_result' => $store_result
//            ];
//            $sucArr = [
//                'result' => 'ok',// 文件上传成功
//                'id' => 10001, // 文件在服务器上的唯一标识
//                'url'=> 'http://example.com/file-10001.jpg',// 文件的下载地址
//                'output'  => $output,
//            ];
//            return $sucArr;
//            Log::info('上传文件日志',$output);
//            print_r($output);exit();
//        }
//        $errArr = [
//            'result' => 'failed',// 文件上传失败
//            'message' => '文件内容包含违规内容',//用于在界面上提示用户的消息
//        ];
//        return $errArr;
//        $sucArr = [
//            'result' => 'ok',// 文件上传成功
//            'id' => 10001, // 文件在服务器上的唯一标识
//            'url'=> 'http://example.com/file-10001.jpg',// 文件的下载地址
//        ];
//        return $sucArr;
//    }

}
