<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\BaseController;
use App\Services\Cookie\CookieOperate;
use App\Services\Request\CommonRequest;
use App\Services\Request\API\Sites\APIRunBuyRequest;
use App\Services\SessionCustom\SessionCustom;
use App\Services\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BaseWebController extends BaseController
{
    public static $VIEW_PATH = 'site';// 视图文件夹目录名称
    public static $VIEW_NAME = '';// 视图栏目文件夹目录名称
    public static $LOGIN_ADMIN_TYPE = 8;// 当前登录的用户类型1平台2企业4管理员8个人
    public static $ALLOW_BROWSER_OPEN = true;// 调试用开关，true:所有浏览器都能开； false:微信内


    public $code_id = null;
//
    public function InitParams(Request $request)
    {
        // 获得redisKey 参数值
        $temRedisKey = CommonRequest::get($request, 'redisKey');
        if(isAjax()){
            $this->source = 2;
        }
        if(!empty($temRedisKey)){// 不为空，则是从小程序来的
            $this->redisKey = $temRedisKey;
            $this->save_session = false;
            $this->source = 3;
        }
        //session_start(); // 初始化session
        //$userInfo = $_SESSION['userInfo']?? [];
        $codeInfo = $this->getUserInfo();// 保存的是code表的单条记录
        if(empty($codeInfo)) {
            throws('非法请求！', $this->source);
//            if(isAjax()){
//                ajaxDataArr(0, null, '非法请求！');
//            }else{
//                redirect('login');
//            }
        }
        $company_id = $codeInfo['id'] ?? null;//$userInfo['company_id'] ?? null;//CommonRequest::getInt($request, 'company_id');
        if(empty($company_id) || (!is_numeric($company_id))){
            throws('非法请求！', $this->source);
//            if(isAjax()){
//                ajaxDataArr(0, null, '非法请求！');
//            }else{
//                redirect('login');
//            }
        }
        $this->reDataArr['codeInfo'] = $codeInfo;

        $this->user_info =$codeInfo;
        $this->user_id = 0;// $codeInfo['id'] ?? '';
        $this->operate_staff_id = $this->user_id;
        $this->operate_staff_id_history = $this->user_id;
        $this->company_id = $company_id;


        $this->code_id = $codeInfo['id'] ?? '';

        /*
        $userInfo = $this->getUserInfo();
        // pr($userInfo);
        if(empty($userInfo)) {
            throws('非法请求！', $this->source);
//            if(isAjax()){
//                ajaxDataArr(0, null, '非法请求！');
//            }else{
//                redirect('login');
//            }
        }
        $company_id = $userInfo['id'] ?? null;//$userInfo['company_id'] ?? null;//CommonRequest::getInt($request, 'company_id');
        if(empty($company_id) || (!is_numeric($company_id))){
            throws('非法请求！', $this->source);
//            if(isAjax()){
//                ajaxDataArr(0, null, '非法请求！');
//            }else{
//                redirect('login');
//            }
        }
        // Tool::judgeInitParams('company_id', $company_id);
        $this->user_info =$userInfo;
        $this->user_id = $userInfo['id'] ?? '';
        $this->operate_staff_id = $this->user_id;
        $this->operate_staff_id_history = $this->user_id;
        $this->company_id = $company_id;


        $this->admin_type = $userInfo['admin_type'] ?? 0;
        $this->city_site_id = $userInfo['city_site_id'] ?? 0;
        $this->city_partner_id = $userInfo['city_partner_id'] ?? 0;
        $this->seller_id = $userInfo['seller_id'] ?? 0;
        $this->shop_id = $userInfo['shop_id'] ?? 0;

        $real_name = $userInfo['real_name'] ?? '';
        $mobile = $userInfo['mobile'] ?? '';
        if(empty($real_name)){
            $real_name = $mobile;
        }
        $this->reDataArr['baseArr']['real_name'] = $real_name;
        $this->reDataArr['qqMapsKey'] = config('public.qqMapsKey');// 腾讯地图Key鉴权
        // 每*分钟，自动更新一下左则
//        $recordTime  = time();
//        $difTime = 60 * 5 ;// 5分钟
//        $modifyTime = $userInfo['modifyTime'] ?? ($recordTime - $difTime - 1);
//        if($this->save_session &&  ($modifyTime + $difTime) <=  $recordTime){// 后台
//            $proUnits = $this->getUnits($this->user_info);
//            $userInfo['proUnits'] = $proUnits;
//            $userInfo['modifyTime'] = time();
//            $redisKey = $this->setUserInfo($userInfo, -1);
//        }

        */
    }

    // 判断是否在微信内访问
    public function judgeWeixinVisit(){
        if(!static::$ALLOW_BROWSER_OPEN && !Tool::isWeixinVisit()){
            if(isAjax()){
                throws('请在微信内访问！');
            }else{
                die('请在微信内访问！');
            }
        }
    }

    // 获得用户的微信id[没有时，去请求微信]
    // 用卡密登陆过后的 缓存键 $cache_pre --唯一
    // $scopes 授权方式 snsapi_base 【默认】 snsapi_userinfo
    // $callback 回调url  url('/api/wx/callback') 中的 '/api/wx/callback' 部分
    // $initWechatAppKey 要实列化的微权三方 对象的键名 默认  'wechat.official_account'
    // $session_openid_key 保存 用户 openid 的session 键名
    // $session_do_url_key 保存 当前正在访问地址的 的session 键名
    // 返回 $openid - string 或 请求微信登录
    public function autoGetOpenid(Request $request, $cache_pre = '', $scopes = 'snsapi_base', $callback = 'api/wx/callback', $initWechatAppKey = 'wechat.official_account', $session_openid_key = 'openid', $session_do_url_key = 'wechat_back_url'){

//        ini_set('session.use_strict_mode', 0);//关闭严格模式
//        ini_set('session.use_cookies', 0);//禁止通过cookie传递session id
//        //获取open id比较简单，就不再赘述了……
//        //微信公众号scywzh，假设 $openId 为从微信服务器得到的用户 open id.
//        $openId = '123';
//        $sessionId = md5($openId);
//        session_id($sessionId);
//        session_start();

        if(empty($cache_pre)) throws('缓存键不能为空！');

        //下面就可以像平常一样该干嘛干嘛了...
        $this->judgeWeixinVisit();// 判断是否微信内浏览器
         $session_openid_key = $cache_pre . '_openid';
        $openid = Tool::getRedis($session_openid_key, 3);
//         $openid = SessionCustom::get($session_openid_key, true);
//        $openid = CookieOperate::get($session_openid_key);
            Log::info('微信日志-登陆情况信息',[$openid]);
        if(empty($openid)){// 没有登录
            Log::info('微信日志-登陆',['未登陆']);
             $session_do_url_key = $cache_pre . '_wechat_back_url';
            $doUrl = $request->fullUrl();// 记录当前正在访问的页面，
//            SessionCustom::set($session_do_url_key, $doUrl, 600);
//            CookieOperate::set($session_do_url_key, $doUrl, 600);
              Tool::setRedis('', $session_do_url_key, $doUrl, 60 * 10 , 3);

//            $target_url = SessionCustom::get($session_do_url_key);
//            pr($target_url);
            // 开始微信授权
            $app = app($initWechatAppKey);
            $oauth = $app->oauth;
            // return $oauth->redirect();

            return $app->oauth->scopes([$scopes])
                ->redirect(url($callback));
            // 这里不一定是return，如果你的框架action不是返回内容的话你就得使用
            // $oauth->redirect()->send();
        }
        Log::info('微信日志-登陆',['已经登录过']);
        return $openid;
    }

    // 获得用户的微信id[没有时，去请求微信]
    // 用卡密登陆过后的 缓存键 $cache_pre --唯一
    // 具体参数说明请查看 autoGetOpenid方法
    // 返回 $openid - string 或 请求微信登录
    public function autoSiteGetOpenid(Request $request, $cache_pre = '', $scopes = 'snsapi_base', $initWechatAppKey = 'wechat.official_account'){
        $callback = 'api/site/wx/callback/' . $cache_pre;
        $session_openid_key = 'openid';
        $session_do_url_key = 'wechat_back_url';
        return $this->autoGetOpenid($request, $cache_pre, $scopes, $callback, $initWechatAppKey, $session_openid_key, $session_do_url_key);
    }
}
