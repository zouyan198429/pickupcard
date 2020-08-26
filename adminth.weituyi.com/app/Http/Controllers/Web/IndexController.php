<?php

namespace App\Http\Controllers\Web;

use App\Business\Controller\API\RunBuy\CTAPIActivityBusiness;
use App\Business\Controller\API\RunBuy\CTAPIActivityCodeBusiness;
use App\Services\Request\CommonRequest;
use App\Services\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IndexController extends BaseWebController
{


    /**
     * 首页
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $reDataArr = $this->reDataArr;
        // 获得当前不效的提货活动
//        $reDataArr['activity_kv'] = CTAPIActivityBusiness::getListKV($request, $this, 1);
//        $reDataArr['defaultActivity'] = -1;// 默认

        $activity_kv = [];
        $extParams = [
            'useQueryParams' => false,
            'sqlParams' => [// 其它sql条件[覆盖式],下面是常用的，其它的也可以
                'where' => [['status', 2]],
                'select' => ['id', 'product_id', 'product_id_history', 'activity_name'],
//              'orderBy' => '如果有值，则替换orderBy'
//                'whereIn' => ['status'=> [1,2]],// ['status'=> [1,2,4]],
//               'whereNotIn' => '如果有值，则替换whereNotIn'
//               'whereBetween' => '如果有值，则替换whereBetween'
//               'whereNotBetween' => '如果有值，则替换whereNotBetween'
           ],
        ];
        $activiryArr = CTAPIActivityBusiness::getList($request, $this, 1, [], ['productInfo', 'productHistoryInfo'], $extParams, 1);
        $activiryList = $activiryArr['result']['data_list'] ?? [];
        foreach($activiryList as $k => $v){
            $activity_kv[$v['id']] = $v['activity_name'] . '[' . $v['product_name'] . ']';
        }
        $reDataArr['activity_kv'] = $activity_kv;
        $reDataArr['defaultActivity'] = -1;// 默认

        // 版权
        $reDataArr['copyright'] = config('public.copyright');

        // Log::info('日志测试---search页',[]);
         return view('web.index', $reDataArr);
    }

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
        // 获得兑换码信息
        $codeInfo = CTAPIActivityCodeBusiness::getInfoData($request, $this, $code_id, ['product_id', 'activity_id'],  ['activityInfo.siteResources'], 1);

        // 资源url
        $resource_list = [];
        if(isset($codeInfo['activity_info'])){
            $activity_info = $codeInfo['activity_info'] ?? [];
            Tool::resourceUrl($activity_info, 2);
            $resource_list = Tool::formatResource($activity_info['site_resources'], 2);

            if(isset($codeInfo['activity_info']['site_resources']) ) unset($codeInfo['activity_info']['site_resources']);
            unset($codeInfo['activity_info']);
        }
        $reDataArr['resource_list'] = $resource_list;


        $reDataArr['code_id'] =  $code_id;
        $reDataArr['code'] =  $code;
        $reDataArr['product_id'] = $codeInfo['product_id'] ?? 0;

        // 版权
        $reDataArr['copyright'] = config('public.copyright');

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
     * ajax保存数据
     *
     * @param Request $request
     * @return array
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_save(Request $request)
    {
        // $this->InitParams($request);
        // $company_id = $this->company_id;

        return CTAPIActivityCodeBusiness::save($request, $this);

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
