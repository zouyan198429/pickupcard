<?php

namespace App\Http\Controllers\Site;

use App\Business\Controller\API\RunBuy\CTAPIActivityCodeBusiness;
use App\Business\Controller\API\RunBuy\CTAPICityBusiness;
use App\Business\Controller\API\RunBuy\CTAPIDeliveryAddrBusiness;
use App\Business\Controller\API\RunBuy\CTAPIStaffBusiness;
use App\Services\Cookie\CookieOperate;
use App\Services\Request\CommonRequest;
use App\Services\SessionCustom\SessionCustom;
use App\Services\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WeChatController extends BaseWebController
{
    public static $VIEW_NAME = '';// 视图栏目文件夹目录名称

    // 授权回调页
    public function callback(Request $request, $redisKey = ''){
//        ini_set('session.use_strict_mode', 0);//关闭严格模式
//        ini_set('session.use_cookies', 0);//禁止通过cookie传递session id
//        //获取open id比较简单，就不再赘述了……
//        //微信公众号scywzh，假设 $openId 为从微信服务器得到的用户 open id.
//        $openId = '123';
//        $sessionId = md5($openId);
//        session_id($sessionId);
//        session_start();

        Log::info('微信日志-登陆回调',['开始回调']);
        if(empty($redisKey)) throws('请先提交密码');
        $request->merge(['redisKey' => $redisKey]);
        $this->InitParams($request);

        $initWechatAppKey = 'wechat.official_account';
        $session_openid_key = $redisKey . '_openid';
        $session_do_url_key = $redisKey . '_wechat_back_url';
        $app = app($initWechatAppKey);
        $oauth = $app->oauth;
//        $preKey = Tool::getProjectKey(1, ':', ':');

        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user();

        // $user 可以用的方法:
        // $user->getId();  // 对应微信的 OPENID
        // $user->getNickname(); // 对应微信的 nickname
        // $user->getName(); // 对应微信的 nickname
        // $user->getAvatar(); // 头像网址
        // $user->getOriginal(); // 原始API返回的结果
        // $user->getToken(); // access_token， 比如用于地址共享时使用
        // $_SESSION[$preKey . 'wechat_user'] = $user->toArray();
        $wechat_user = $user->toArray();
        Log::info('微信日志-登陆回调',[$wechat_user]);
//        Tool::setRedis('', $preKey . 'wechat_user', $wechat_user, 60*5, 2); // 5分钟
//        SessionCustom::set($session_do_url_key, $user->getId(), 60*60*2);
        /*
         *
         *
            snsapi_userinfo 格式
            {
                "id": "o5MtAw40KOeGC0c5jlU5pxUeoS-k",
                "name": "笑对人生",
                "nickname": "笑对人生",
                "avatar": "http://thirdwx.qlogo.cn/mmopen/vi_32/jBg4Hc9Vy7d2xlRuCibpAu8uwGApgeX25KEnZPPwuPEfdcaODTMOB8hniaF7bFyichgUcp2Z0X8MXq2FkbIqUXibzA/132",
                "email": null,
                "original": {
                    "openid": "o5MtAw40KOeGC0c5jlU5pxUeoS-k",
                    "nickname": "笑对人生",
                    "sex": 1,
                    "language": "zh_CN",
                    "city": "西安",
                    "province": "陕西",
                    "country": "中国",
                    "headimgurl": "http://thirdwx.qlogo.cn/mmopen/vi_32/jBg4Hc9Vy7d2xlRuCibpAu8uwGApgeX25KEnZPPwuPEfdcaODTMOB8hniaF7bFyichgUcp2Z0X8MXq2FkbIqUXibzA/132",
                    "privilege": [ ],
                    "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
                },
                "token": "18_EeuWUKgUqSjbQsHh3EOc0h664K4XB22pGt-E_z8OBBhFBt5S-2MnHuVscPyN3GiS8YdXQ1AQC6FnjxhgIrz1kGyt3j377LjD_pr8NpdV6HM",
                "provider": "WeChat"
            }
            snsapi_base 格式 ；没有 unionid
            {
                "id": "o5MtAw40KOeGC0c5jlU5pxUeoS-k",
                "name": null,
                "nickname": null,
                "avatar": null,
                "email": null,
                "original": {
                    "access_token": "36_trECH9a51umRKoWFDTZ3GquxhF8Z0NhV7u6BoOzzqSsBYohcKenv87BgLRySZVYqYrCedakH-_0UWn5wVgu8f5yPFuBo93-u6ohbzlo9ZM4",
                    "expires_in": 7200,
                    "refresh_token": "36_nxQNcMQUPr_8w0W31e4ANK5wacXX7h8z45IkcCUrGThCKsCMa46LUoH2EdREnnuj5IBfrHFb_kye-hRV-kUoEmMYeXJDLOF_FCbw1xAil8Q",
                    "openid": "o5MtAw40KOeGC0c5jlU5pxUeoS-k",
                    "scope": "snsapi_base"
                },
                "token": "36_trECH9a51umRKoWFDTZ3GquxhF8Z0NhV7u6BoOzzqSsBYohcKenv87BgLRySZVYqYrCedakH-_0UWn5wVgu8f5yPFuBo93-u6ohbzlo9ZM4",
                "provider": "WeChat"
            }
         *
         */
        // 注册或更新用户信息

        $original = $wechat_user['original'] ?? [];
        if(empty($original)){
            Log::info('微信日志-登陆回调',['登录失败:没有用户信息!']);
            throws('登录失败:没有用户信息!');
        }
        // 保存到数据库
        $wx_unionid = $original['unionid'] ?? '';
        $mp_openid = $original['openid'] ?? '';
        if(!isset($original['openid']) || empty($mp_openid)){
            Log::info('微信日志-登陆回调',['登录失败:openId获取失败!']);
            throws('登录失败:openId获取失败!');
        }
        Log::info('微信日志-登陆回调',['开始保存用户信息!']);
        // 获得用户id
        $id = 0;
        $saveData = [
            'admin_type' => 8,// 64,
            'wx_unionid' => $wx_unionid,
            // 'mini_openid' => $mini_openid,
             'mp_openid' => $mp_openid,
            'mini_session_key' => $wechat_user['token'] ?? '',// 网页授权access_token
            'nickname' => $original['nickname'] ?? '',
            'gender' => $original['sex'] ?? 0,
            'sex' => $original['sex'] ?? 0,
            'province' => $original['province'] ?? '',
            'city' => $original['city'] ?? '',
            'country' => $original['country'] ?? '',
            'avatar_url' => $original['headimgurl'] ?? '',
            'lastlogintime' => date('Y-m-d H:i:s',time()),
        ];
        try{
            $resultDatas = CTAPIStaffBusiness::replaceById($request, $this, $saveData, $id,  false, 1);

        } catch ( \Exception $e) {
            Log::info('微信日志-登陆回调',['开始保存用户信息--失败!' . $e->getMessage()]);
            throws($e->getMessage(), $e->getCode());
        }

        Log::info('微信日志-登陆回调',['开始保存用户信息--成功!']);
        // 保存session
        // 存储数据到session...
        // if (!session_id()) session_start(); // 初始化session
        // $_SESSION['userInfo'] = $userInfo; //保存某个session信息
//        $preKey = 0;// 小程序
//        $redisKey = $this->setUserInfo($resultDatas, $preKey);
//        $reData['redisKey'] = $redisKey;
//        CookieOperate::set('redisKey' , $redisKey, 60*60*2);
//        Log::info('微信日志-登陆回调',['开始保存用户信息--保存redisKey' . CookieOperate::get('redisKey')]);
        // $target_url = Tool::getRedis($preKey . 'target_url', 2);
//        Tool::setRedis('', $preKey . 'wechat_user', $wechat_user, 60*5, 2); // 5分钟
        Log::info('微信日志-登陆回调',['开始保存用户信息--保存openid' . $user->getId()]);
//        SessionCustom::set($session_openid_key, $user->getId(), 60*60*2);
//        CookieOperate::set($session_openid_key, $user->getId(), 60*60*2);
        Tool::setRedis('', $session_openid_key, $user->getId(), 60*60*2 , 3);
//        $target_url = SessionCustom::get($session_do_url_key);
        // $target_url = CookieOperate::get($session_do_url_key);
        $target_url = Tool::getRedis($session_do_url_key, 3);
            Log::info('微信日志-登陆回调',['开始保存用户信息--跳转页面!' . $target_url]);
         // $targetUrl = empty($_SESSION[$preKey . 'target_url']) ? '/' : $_SESSION[$preKey . 'target_url'];
        $targetUrl = empty($target_url) ? '/' : $target_url;

//        Log::info('微信日志-登陆回调',['开始保存用户信息--跳转页面!' . $target_url]);
         header('location:'. $targetUrl); // 跳转到 user/profile
//         redirect($targetUrl);

    }

}
