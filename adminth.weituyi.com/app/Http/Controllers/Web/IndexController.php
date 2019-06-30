<?php

namespace App\Http\Controllers\Web;

use App\Business\Controller\API\RunBuy\CTAPIActivityCodeBusiness;
use App\Services\Request\CommonRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IndexController extends BaseWebController
{

    /**
     * 登陆
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function login(Request $request, $code_id = 0, $code = '')
    {
        $reDataArr = $this->reDataArr;
        // $code_id = CommonRequest::getInt($request, 'code_id');

        $reDataArr['code_id'] =  $code_id;
        $reDataArr['code'] =  $code;

        // Log::info('日志测试---search页',[]);
        return view('web.search', $reDataArr);
    }



    /**
     * ajax保存数据
     *
     * @param Request $request
     * @return array
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_login(Request $request)
    {
        // $this->InitParams($request);
        // $company_id = $this->company_id;

        return CTAPIActivityCodeBusiness::login($request, $this);

    }

    /**
     * 注销
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function logout(Request $request)
    {
        // $this->InitParams($request);
        CTAPIActivityCodeBusiness::loginOut($request, $this);
        $reDataArr = $this->reDataArr;
        return redirect('web/search');
    }

}
