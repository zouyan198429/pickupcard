<?php

namespace App\Http\Controllers\Site;

use App\Business\Controller\API\RunBuy\CTAPIActivityBusiness;
use App\Business\Controller\API\RunBuy\CTAPIActivityCodeBusiness;
use App\Business\Controller\API\RunBuy\CTAPIStaffBusiness;
use App\Services\Cookie\CookieOperate;
use App\Services\Request\CommonRequest;
use App\Services\SessionCustom\SessionCustom;
use App\Services\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IndexController extends BaseWebController
{
    public static $VIEW_NAME = '';// 视图栏目文件夹目录名称

    /**
     * 测试
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function test(Request $request)
    {
        $session_openid_key = 'openid';
        Log::info('微信日志-index',['首页!' . $session_openid_key]);
        SessionCustom::set($session_openid_key, 'aabbcc111', 60 * 30);
        $kkk = SessionCustom::get($session_openid_key);
        // $kkk = CookieOperate::get($session_openid_key);
        Log::info('微信日志-index',['首页!' . $kkk]);
        echo '<a href="' . url('site') .'">aaaaaabbbb</a>';
        pr($kkk);

//        ini_set('session.use_strict_mode', 0);//关闭严格模式
//        ini_set('session.use_cookies', 0);//禁止通过cookie传递session id
//        //获取open id比较简单，就不再赘述了……
//        //微信公众号scywzh，假设 $openId 为从微信服务器得到的用户 open id.
//        $openId = '123';
//        $sessionId = md5($openId);
//        session_id($sessionId);
//        session_start();
//        $session_do_url_key = 'aaa';
//        $doUrl = 'ccc';
////            SessionCustom::set($session_do_url_key, $doUrl, 600);
//            $kkk = SessionCustom::get($session_do_url_key);
//            pr($kkk);

        $key = 'aaa';
        CookieOperate::set($key, 'bbccdd', 60);
        $cookie = CookieOperate::get($key);
        echo $cookie;
        echo '<hr/>';
        CookieOperate::del($key);
        $cookie = CookieOperate::get($key);
        dd($cookie);
        $preKey = Tool::getProjectKey(1, ':', ':');
        pr($preKey);
        $user = Tool::getRedis( 'wechat_user', 2);
        pr($user);
        $preKey = Tool::getProjectKey(1, ':', ':');
        pr($preKey);
        $app = app('wechat.official_account');
//        $oauth = $app->oauth;// 未登录
//        if (empty($_SESSION['wechat_user'])) {
//        $response = $app->oauth->scopes(['snsapi_userinfo'])
//            ->redirect();
//            $response = $app->oauth->scopes(['snsapi_userinfo'])
//                ->redirect($request->fullUrl());
//            $response = $app->oauth->scopes(['snsapi_base'])
//                ->redirect($request->fullUrl());
//            return $response;
//        }
//        if (!session_id()) session_start();
//        $_SESSION['wechat_user'] = $redisKey;
//
//        //回调后获取user时也要设置$request对象
//      $user = $app->oauth->setRequest($request)->user();
//      if(!empty($user)){
//          pr($user);
//      }
//        echo 'aaa';
//        pr($response);
    }

    /**
     * 首页
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $session_openid_key = 'openid';
        Log::info('微信日志-index',['首页!' . $session_openid_key]);
//        SessionCustom::set($session_openid_key, 'aabbcc', 60 * 30);
        echo 'index:';
        $kkk = SessionCustom::get($session_openid_key);
        // $kkk = CookieOperate::get($session_openid_key);
        Log::info('微信日志-index',['首页!' . $kkk]);
        pr($kkk);

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
         return view('' . static::$VIEW_PATH . '.index', $reDataArr);
    }

    /**
     * 显示商品页
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function product(Request $request, $code_id = 0, $code = '')
    {

        $this->judgeWeixinVisit();// 判断是否微信内浏览器

        $reDataArr = $this->reDataArr;
        // $code_id = CommonRequest::getInt($request, 'code_id');
        // 获得兑换码信息
        $codeInfo = CTAPIActivityCodeBusiness::getInfoData($request, $this, $code_id, ['product_id', 'activity_id'],  ['productInfo', 'activityInfo.siteResources'], 1);

        // 资源url
        $resource_list = [];
        if(isset($codeInfo['activity_info'])){
            $activity_info = $codeInfo['activity_info'] ?? [];
            $codeInfo['activity_info']['pay_price'] = Tool::formatFloat($activity_info['price'] + $activity_info['freight_price'] + $activity_info['insured_price']);;
            Tool::resourceUrl($activity_info, 2);
            $resource_list = Tool::formatResource($activity_info['site_resources'], 2);

            if(isset($codeInfo['activity_info']['site_resources']) ) unset($codeInfo['activity_info']['site_resources']);
//            unset($codeInfo['activity_info']);
        }
        $reDataArr['resource_list'] = $resource_list;

        $reDataArr['info'] =  $codeInfo;
        $reDataArr['code_id'] =  $code_id;
        $reDataArr['code'] =  $code;
        $reDataArr['product_id'] = $codeInfo['product_id'] ?? 0;

        // 版权
        $reDataArr['copyright'] = config('public.copyright');

        // Log::info('日志测试---search页',[]);
        return view('' . static::$VIEW_PATH . '.product', $reDataArr);
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
        $this->judgeWeixinVisit();// 判断是否微信内浏览器

        $reDataArr = $this->reDataArr;
        // $code_id = CommonRequest::getInt($request, 'code_id');
        // 获得兑换码信息
        $codeInfo = CTAPIActivityCodeBusiness::getInfoData($request, $this, $code_id, ['product_id', 'activity_id'],  ['activityInfo.siteResources'], 1);

        // 资源url
        $resource_list = [];
        if(isset($codeInfo['activity_info'])){
            $activity_info = $codeInfo['activity_info'] ?? [];
            $codeInfo['activity_info']['pay_price'] = Tool::formatFloat($activity_info['price'] + $activity_info['freight_price'] + $activity_info['insured_price']);;
            Tool::resourceUrl($activity_info, 2);
            $resource_list = Tool::formatResource($activity_info['site_resources'], 2);

            if(isset($codeInfo['activity_info']['site_resources']) ) unset($codeInfo['activity_info']['site_resources']);
//            unset($codeInfo['activity_info']);
        }
        $reDataArr['resource_list'] = $resource_list;


        $reDataArr['info'] =  $codeInfo;
        $reDataArr['code_id'] =  $code_id;
        $reDataArr['code'] =  $code;
        $reDataArr['product_id'] = $codeInfo['product_id'] ?? 0;

        // 版权
        $reDataArr['copyright'] = config('public.copyright');
        // Log::info('日志测试---search页',[]);
        return view('' . static::$VIEW_PATH . '.search', $reDataArr);
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
        $this->judgeWeixinVisit();// 判断是否微信内浏览器
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
        // CTAPIActivityCodeBusiness::loginOut($request, $this);
        CTAPIStaffBusiness::loginOut($request, $this);
        $reDataArr = $this->reDataArr;
//        return redirect('site/search');
        return redirect('site');
    }

}
