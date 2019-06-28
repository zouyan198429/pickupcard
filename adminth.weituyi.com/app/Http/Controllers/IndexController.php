<?php

namespace App\Http\Controllers;

use App\Services\Request\API\Sites\APIRunBuyRequest;
use App\Services\Common;
use App\Services\Request\API\HttpRequest;
use App\Services\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Lvht\GeoHash;

class IndexController extends WorksController
{
    /**
     * 注册
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function test(Request $request)
    {
        $lng = 117.031689;
        $lat = 36.65396;
        // $hash = GeoHash::encode($lng,$lat);// wwe0x0euu12
        // vd($hash);
        // $nearHash  = GeoHash::expand('wwe0x0');// 附近8个
        // pr($nearHash);
        // $point = GeoHash::decode('wwe0x0');
        // pr($point);
        // $hash = geohash_encode($lat, $lng, 12);// wwe0x0euu12h
        // vd($hash);
        // $nearHash  = geohash_neighbors('wwe0x0');
        // pr($nearHash);
        // $point = geohash_decode('wwe0x0');
        // pr($point);
        return view('test');
    }

    public function test2(Request $request)
    {
        return view('test2');
    }

    /**
     * 首页
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function index(Request $request)
//    {
//        $this->InitParams($request);
//        $url = config('public.apiUrl') . config('apiUrl.common.index');
//        // 生成带参数的测试get请求
//        // $requestTesUrl = splicQuestAPI($url , $requestData);
//        $requestData['company_id'] = $this->company_id ;
//        $resData = HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
//        $resData['userInfo'] = $this->user_info;
//        return view('index',$resData);
//    }

    /**
     * 首页
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $urlArr = $request->server();
        $httpHost = $urlArr['HTTP_HOST'] ?? '';
        return redirect(Common::urlRedirect($httpHost, 1));
    }
    /**
     * 文件上传
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function upload(Request $request)
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
//
//        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
//            $photo = $request->file('photo');
//            $extension = $photo->extension();
//            //$store_result = $photo->store('photo');
//            $save_path = 'resource/photo/'. date('Y/m/d/',time());
//            $save_name = Tool::createUniqueNumber(20) .'.' . $extension;
//            $store_result = $photo->storeAs($save_path, $save_name);
//            $output = [
//                'extension' => $extension,
//                'store_result' => $store_result
//            ];
//            $sucArr = [
//                'result' => 'ok',// 文件上传成功
//                'id' => 10001, // 文件在服务器上的唯一标识
//                'url'=> url($save_path . $save_name),//'http://example.com/file-10001.jpg',// 文件的下载地址
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


    /**
     * 注册
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function reg(Request $request)
//    {
//        return view('reg');
//    }

    /**
     * 登陆
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function login(Request $request)
//    {
//        return view('login');
//    }

    /**
     * 注销
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function logout(Request $request)
//    {
//        $this->InitParams($request);
//        // session_start(); // 初始化session
//        //$userInfo = $_SESSION['userInfo'] ?? [];
//        /*
//        if(isset($_SESSION['userInfo'])){
//            unset($_SESSION['userInfo']); //保存某个session信息
//        }
//        */
//        $this->delUserInfo();
//        return redirect('/login');
//    }

    /**
     * err404
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function err404(Request $request)
    {
        return view('404');
    }

}
