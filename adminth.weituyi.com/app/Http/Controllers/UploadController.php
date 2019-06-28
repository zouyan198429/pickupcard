<?php

namespace App\Http\Controllers;

use App\Services\DB\CommonDB;
use App\Services\Request\API\Sites\APIRunBuyRequest;
use App\Services\Request\CommonRequest;
use App\Services\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UploadController extends WorksController
{
    protected $model_name = 'Resource';
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
        ini_set('memory_limit','1024M');    // 临时设置最大内存占用为 3072M 3G
        ini_set("max_execution_time", "300");
        set_time_limit(300);   // 设置脚本最大执行时间 为0 永不过期

        $pro_unit_id = CommonRequest::getInt($request, 'pro_unit_id');
        $name = CommonRequest::get($request, 'name');
        $requestLog = [
            'files'       => $request->file(),
            'posts'  => $request->post(),
            'input'      => $request->input(),
        ];
        Log::info('上传文件日志',$requestLog);
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $photo = $request->file('photo');
            $extension = $photo->extension();
            $hashname = $photo->hashName();
            $temFile = [
                'extension' => $extension,
                'hashname' => $hashname,
                'name' => $name,
            ];
            Log::info('上传文件日志-文件信息',$temFile);

            // 生成保存路径
            $company_id = $this->company_id;
            $savPath = $this->source_path . $company_id . '/';
            if(is_numeric($pro_unit_id)){
                $savPath .=   'pro' . $pro_unit_id . '/';
            }
            $savPath .=   date('Y/m/d/',time());
            $saveName = Tool::createUniqueNumber(30) .'.' . $extension;
            //$store_result = $photo->store('photo');
            try{
                $store_result = $photo->storeAs($savPath, $saveName);
                // 保存资源
                $saveData = [
                    'resource_name' => $name,
                    // 'resource_type' => 0,
                    // 'resource_note' => '',
                    'resource_url' => $savPath . $saveName,
                ];
                $reslut = APIRunBuyRequest::createApi($this->model_name, $saveData, $company_id);

                $id = $reslut['id'] ?? '';
                if(empty($id)){
                    Log::info('上传文件日志-保存资源失败',$id);
                    throws('保存资源失败!', $this->source);
                }

            } catch ( \Exception $e) {
                $errArr = [
                    'result' => 'failed',// 文件上传失败
                    'message' => $e->getMessage(),//'文件内容包含违规内容',//用于在界面上提示用户的消息
                ];
                Log::info('上传文件日志-失败',$errArr);
                return $errArr;
            }
            $sucArr = [
                'result' => 'ok',// 文件上传成功
                'id' => $id, // 文件在服务器上的唯一标识
                'url'=> url($savPath . $saveName),//'http://example.com/file-10001.jpg',// 文件的下载地址
                'store_result' => $store_result,
                'data_list' => [
                    [
                        'id' => $id,
                        'resource_name' => $name,
                        'resource_url' => url($savPath . $saveName),
                        'created_at' =>  date('Y-m-d H:i:s',time()),
                    ]
                ],
            ];
            Log::info('上传文件日志-成功',$sucArr);
            return $sucArr;

        }else{
            $errArr = [
                'result' => 'failed',// 文件上传失败
                'message' => '请选择要上传的文件！',//'文件内容包含违规内容',//用于在界面上提示用户的消息
            ];
            return $errArr;
        }
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
        $id = CommonRequest::getInt($request, 'id');
        Tool::judgeInitParams('id', $id);
        $company_id = $this->company_id;
        $resultDatas = APIRunBuyRequest::ResourceDelById($id, $company_id);

        return ajaxDataArr(1, $resultDatas, '');

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
