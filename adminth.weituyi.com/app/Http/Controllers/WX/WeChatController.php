<?php

namespace App\Http\Controllers\WX;

use App\Http\Controllers\WorksController;
use App\Services\Tool;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\Text;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class WeChatController extends BaseController
{
    public $controller_id =0;// 功能小模块[控制器]id - controller_id  历史表 、正在进行表 与原表相同

    public function test(){
        $app = app('wechat.official_account');

        $openid1 = 'o5MtAw40KOeGC0c5jlU5pxUeoS-k';// 我的微信openid
        $openid2 = 'o5MtAw8wcnGbkaX8OfItq55UdELI';// 用户2 openid

        // 群发
//        $aa = $app->broadcasting->sendText("大家好！欢迎使用 aaaaaaaaaaEasyWeChat。", [$openid1, $openid2]);
//        pr($aa);
//        获取用户信息
//        获取单个：
        $user = $app->user->get($openid1);
//        pr($user);
        /*
         *
            Array
            (
                [subscribe] => 1
                [openid] => o5MtAw40KOeGC0c5jlU5pxUeoS-k
                [nickname] => 笑对人生
                [sex] => 1
                [language] => zh_CN
                [city] => 西安
                [province] => 陕西
                [country] => 中国
                [headimgurl] => http://thirdwx.qlogo.cn/mmopen/N3rG0Nn7pv4ChnVWKozbOGcFr2I0wUp4U6PgpqqvZrMpkCicpBVzzI2fXmET9xshYuTDJrjXDHmRuglYq5QbD1X9DDCoywZgA/132
                [subscribe_time] => 1504771300
                [remark] =>
                [groupid] => 0
                [tagid_list] => Array
                    (
                    )

                [subscribe_scene] => ADD_SCENE_SEARCH
                [qr_scene] => 0
                [qr_scene_str] =>
            )
         */
//        获取多个：
//        $users = $app->user->select([$openid1, $openid2]);
//        pr($users);
//        $app->user_tag->create('测试标签');
    }
    // token认证及信息传输
    public function index(){
        Log::info('微信日志',['request arrived.']);
        $app = app('wechat.official_account');
        // 服务端的作用呢，在整个微信开发中主要是负责 接收用户发送过来的消息，还有 用户触发的一系列事件。
        // 如果选择不作任何回复，你也得回复一个空字符串或者字符串 SUCCESS[试了,不行，会返回文字]（不然用户就会看到 该公众号暂时无法提供服务）
        // 可以选择传入一个函数名，一个 [$class, $method] 或者 Foo::bar 这样的类型。
        // 某些情况，我们需要直接使用 $message 参数，那么怎么在 push 的闭包外调用呢？
        $message = $app->server->getMessage();
        Log::info('微信日志-闭包外调用message',[$message]);
        /*
            请求消息基本属性(以下所有消息都有的基本属性)：

            ToUserName 接收方帐号（该公众号 ID）
            FromUserName 发送方帐号（OpenID, 代表用户的唯一标识）
            CreateTime 消息创建时间（时间戳）
            MsgId 消息 ID（64位整型）
         *
         */
        $openid1 = 'o5MtAw40KOeGC0c5jlU5pxUeoS-k';// 我的微信openid
        $openid2 = 'o5MtAw8wcnGbkaX8OfItq55UdELI';// 用户2 openid
        $app->server->push(function ($message) {
            // return "您好！欢迎使用 EasyWeChat!";
            // $message['FromUserName'] // 用户的 openid
            // $message['MsgType'] // 消息类型：event, text....
            Log::info('微信日志',[$message]);
            // 转发收到的消息给客服
            // return new Transfer();
            // 也可以指定转发给某一个客服
            // return new Transfer($account);

            switch ($message['MsgType']) {
                case 'event':
                    /*
                     *
                     * 关注事件
                        {
                            "ToUserName": "gh_66481fc2a62e",
                            "FromUserName": "o5MtAw8wcnGbkaX8OfItq55UdELI",
                            "CreateTime": "1550643754",
                            "MsgType": "event",
                            "Event": "subscribe",
                            "EventKey": null
                        }
                            // 取消关注

                        {
                            "ToUserName": "gh_66481fc2a62e",
                            "FromUserName": "o5MtAw8wcnGbkaX8OfItq55UdELI",
                            "CreateTime": "1550643886",
                            "MsgType": "event",
                            "Event": "unsubscribe",
                            "EventKey": null
                        }
                        事件：
                        MsgType event
                        Event 事件类型 （如：subscribe(订阅)、unsubscribe(取消订阅) ...， CLICK 等）

                    // 扫描带参数二维码事件
                        扫描带参数二维码事件
                        EventKey 事件KEY值，比如：qrscene_123123，qrscene_为前缀，后面为二维码的参数值
                        Ticket 二维码的 ticket，可用来换取二维码图片

                    // 自定义菜单事件
                        自定义菜单事件
                        EventKey 事件KEY值，与自定义菜单接口中KEY值对应，如：CUSTOM_KEY_001, www.qq.com
                     *
                     */
                    return '收到事件消息';
                    break;
                case 'text':
                    /*
                     *
                        {
                            "ToUserName": "gh_66481fc2a62e",
                            "FromUserName": "o5MtAw40KOeGC0c5jlU5pxUeoS-k",
                            "CreateTime": "1550641320",
                            "MsgType": "text",
                            "Content": "2222",
                            "MsgId": "22199847965550451"
                        }
                        文本：
                        MsgType text
                        Content 文本消息内容
                     *
                     */
                    return '收到文字消息';// '收到文字消息' 或 null 或 "" 或 "SUCCESS"[试了,不行，会返回文字]
                    break;
                case 'image':
                    /*
                     *
                        {
                            "ToUserName": "gh_66481fc2a62e",
                            "FromUserName": "o5MtAw40KOeGC0c5jlU5pxUeoS-k",
                            "CreateTime": "1550641552",
                            "MsgType": "image",
                            "PicUrl": "http://mmbiz.qpic.cn/mmbiz_jpg/IMQ6RV41II95vU4ONX7s3KUe1pUe4DPhauFvoulSibfhicEmYic9u282YCniaupjbhukPNgG4Jp9vptwVCrMWUITwg/0",
                            "MsgId": "22199849562767718",
                            "MediaId": "bAniyNLIatrmXJhKDBLgqGmfaiZuM5MkT9VGe861t2B8kcXIkYa8-KGfNjVIF3jq"
                        }
                        {
                            "ToUserName": "gh_66481fc2a62e",
                            "FromUserName": "o5MtAw40KOeGC0c5jlU5pxUeoS-k",
                            "CreateTime": "1550641553",
                            "MsgType": "image",
                            "PicUrl": "http://mmbiz.qpic.cn/mmbiz_jpg/IMQ6RV41II95vU4ONX7s3KUe1pUe4DPhauFvoulSibfhicEmYic9u282YCniaupjbhukPNgG4Jp9vptwVCrMWUITwg/0",
                            "MsgId": "22199849562767718",
                            "MediaId": "ul-IbvQ6QSmEdCjZ9gYCzLgLtP_7ILzEzgWtiu_ByoTg-_S9PnmLEpP3y_w-NVyq"
                        }
                        图片：
                        MsgType image
                        MediaId 图片消息媒体id，可以调用多媒体文件下载接口拉取数据。
                        PicUrl 图片链接
                     *
                     */
                    // return '收到图片消息';
                    $image = new Image($message['MediaId']);
                    return $image;
                    break;
                case 'voice':
                    /*
                     *
                      {
                        "ToUserName": "gh_66481fc2a62e",
                        "FromUserName": "o5MtAw40KOeGC0c5jlU5pxUeoS-k",
                        "CreateTime": "1550641970",
                        "MsgType": "voice",
                        "MediaId": "VTUEJRrEx-ww9Gh36276sD8yaVsPYdpTqZZFG5BFM6KZ2zeL-ZOd-q37K_toK88B",
                        "Format": "amr",
                        "MsgId": "6659956548955013120",
                        "Recognition": "三加二减五等于零。"
                    }
                    语音：
                    MsgType voice
                    MediaId 语音消息媒体id，可以调用多媒体文件下载接口拉取数据。
                    Format 语音格式，如 amr，speex 等
                    Recognition * 开通语音识别后才有
                    {warning} 请注意，开通语音识别后，用户每次发送语音给公众号时，微信会在推送的语音消息XML数据包中，增加一个 Recongnition 字段
                     *
                     */
                    return '收到语音消息';
                    break;
                case 'video':
                    /*
                     *
                        {
                            "ToUserName": "gh_66481fc2a62e",
                            "FromUserName": "o5MtAw40KOeGC0c5jlU5pxUeoS-k",
                            "CreateTime": "1550642058",
                            "MsgType": "video",
                            "MediaId": "RDn9UQ1B0VN3KM5t6p9ajHZDjHJkIad21IGQaNWS-fHyLHpdKFWINtPVOIHYjEPJ",
                            "ThumbMediaId": "xf89nGunHqxa2BKRLHOUAaFWU5K7eNd4yMUcWZoLO-kgz3L782L69sobhFap0jnA",
                            "MsgId": "22199854589135397"
                        }
                        视频：
                        MsgType video
                        MediaId 视频消息媒体id，可以调用多媒体文件下载接口拉取数据。
                        ThumbMediaId 视频消息缩略图的媒体id，可以调用多媒体文件下载接口拉取数据。
                        小视频：
                        MsgType shortvideo
                        MediaId 视频消息媒体id，可以调用多媒体文件下载接口拉取数据。
                        ThumbMediaId 视频消息缩略图的媒体id，可以调用多媒体文件下载接口拉取数据。
                     *
                     */
                    return '收到视频消息';
                    break;
                case 'location':
                    /*
                     *
                        {
                            "ToUserName": "gh_66481fc2a62e",
                            "FromUserName": "o5MtAw40KOeGC0c5jlU5pxUeoS-k",
                            "CreateTime": "1550642819",
                            "MsgType": "location",
                            "Location_X": "35.036053",
                            "Location_Y": "108.088036",
                            "Scale": "16",
                            "Label": "明珠馨苑(咸阳市彬州市华宇小区斜对面)",
                            "MsgId": "22199868579206628"
                        }
                        地理位置：
                        MsgType location
                        Location_X 地理位置纬度
                        Location_Y 地理位置经度
                        Scale 地图缩放大小
                        Label 地理位置信息

                        上报地理位置事件
                        Latitude 23.137466 地理位置纬度
                        Longitude 113.352425 地理位置经度
                        Precision 119.385040 地理位置精度
                     *
                     */
                    return '收到坐标消息';
                    break;
                case 'link':
                    /*
                     *
                        {
                            "ToUserName": "gh_66481fc2a62e",
                            "FromUserName": "o5MtAw40KOeGC0c5jlU5pxUeoS-k",
                            "CreateTime": "1550644086",
                            "MsgType": "link",
                            "Title": "翠香猕猴桃新鲜5斤包邮",
                            "Description": "大秦岭原生态鲜果园",
                            "Url": "http://www.liemaoec.com/app/index.php?i=78&c=entry&m=ewei_shopv2&do=mobile&r=goods.detail&id=1436&mid=13512",
                            "MsgId": "22199885630443807"
                        }
                        链接：
                        MsgType link
                        Title 消息标题
                        Description 消息描述
                        Url 消息链接

                     *
                     */
                    return '收到链接消息';
                    break;
                case 'file':
                    /*
                     *
                    {
                        "ToUserName": "gh_66481fc2a62e",
                        "FromUserName": "o5MtAw40KOeGC0c5jlU5pxUeoS-k",
                        "CreateTime": "1550642997",
                        "MsgType": "file",
                        "Title": "o_c_v_c",// 文件名称
                        "Description": "0.7 KB",
                        "FileKey": "AgAAAAAAAAB0t+5i",
                        "FileMd5": "4e954d48ec135d323c10f7879a86f4e7",
                        "FileTotalLen": "671",
                        "MsgId": "22199867176989397"
                    }
                    文件：
                    MsgType      file
                    Title        文件名
                    Description 文件描述，可能为null
                    FileKey      文件KEY
                    FileMd5      文件MD5值
                    FileTotalLen 文件大小，单位字节
                     *
                     */
                    return '收到文件消息';
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
            // ...
        });
        /*
        回复消息
        回复的消息可以为 null，此时 SDK 会返回给微信一个 "SUCCESS"，
        你也可以回复一个普通字符串，比如：欢迎关注 overtrue.，此时 SDK 会对它进行一个封装，产生一个 EasyWeChat\Kernel\Messages\Text 类型的消息并在最后的 $app->server->serve(); 时生成对应的消息 XML 格式。
        如果你想返回一个自己手动拼的原生 XML 格式消息，请返回一个 EasyWeChat\Kernel\Messages\Raw 实例即可。
         *
         */

        return $app->server->serve();
    }

    // 获取jssdk配置
    public function getJSSDKConfig(Request $request){
        // dump(explode(',',$request->get('apis')));
        $arr = explode(',',$request->get('apis'));
        $debug = $request->get('debug') ==='true' ? true : false;
        // 默认返回 JSON 字符串，当 $json 为 false 时返回数组，你可以直接使用到网页中。
        $json = $request->get('json') ==='true' ? true : false;
        $url =$request->get('url');
//        dump($request->get('url'));
        // check
        if(!$url){
            return response()->json(['status'=>false,'msg'=>'params error','data'=>'']);
        }
        $app = app('wechat.official_account');
        $app->jssdk->setUrl($url);// 设置当前URL，如果不想用默认读取的URL，可以使用此方法手动设置，通常不需要。
        $config = $app->jssdk->buildConfig($arr,$debug,$json,$url);
        return response($config);
    }

    // 需要授权才能访问的页面
    public function profile(){
        $app = app('wechat.official_account');
        $oauth = $app->oauth;

        // 未登录
        $preKey = Tool::getProjectKey(1, ':', ':');

       // $wechat_user = Tool::getRedis($preKey . 'target_url', 2);
        $wechat_user = Tool::getRedis($preKey . 'wechat_user', 2);
        Log::info('微信日志-登陆情况信息',[$wechat_user]);
//      if (empty($_SESSION[$preKey . 'wechat_user'])) {
        if (empty($wechat_user)) {
            Log::info('微信日志-登陆',['未登陆']);
            // $_SESSION[$preKey . 'target_url'] = 'user/profile';
            Tool::setRedis('', $preKey . 'target_url', '/api/wx/profile', 60*5, 2); // 5分钟
            // return $oauth->redirect();

            return $app->oauth->scopes(['snsapi_base'])
                ->redirect(url('/api/wx/callback'));
            // 这里不一定是return，如果你的框架action不是返回内容的话你就得使用
            // $oauth->redirect()->send();
        }

        // 已经登录过
        $user = Tool::getRedis($preKey . 'wechat_user', 2);// $_SESSION[$preKey . 'wechat_user'];
        // pr($preKey);
        echo '已经登录过';
        Log::info('微信日志-登陆',['已经登录过']);
        // pr($user);
        return view('web.pay', $user);

    }

    // 授权回调页
    public function callback(){
        $app = app('wechat.official_account');
        $oauth = $app->oauth;
        $preKey = Tool::getProjectKey(1, ':', ':');

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
        Tool::setRedis('', $preKey . 'wechat_user', $wechat_user, 60*5, 2); // 5分钟
        /*
         *
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
                    "privilege": [ ]
                },
                "token": "18_EeuWUKgUqSjbQsHh3EOc0h664K4XB22pGt-E_z8OBBhFBt5S-2MnHuVscPyN3GiS8YdXQ1AQC6FnjxhgIrz1kGyt3j377LjD_pr8NpdV6HM",
                "provider": "WeChat"
            }
         *
         */
        $target_url = Tool::getRedis($preKey . 'target_url', 2);
        // $targetUrl = empty($_SESSION[$preKey . 'target_url']) ? '/' : $_SESSION[$preKey . 'target_url'];
        $targetUrl = empty($target_url) ? '/' : $target_url;

        header('location:'. $targetUrl); // 跳转到 user/profile
    }

}
