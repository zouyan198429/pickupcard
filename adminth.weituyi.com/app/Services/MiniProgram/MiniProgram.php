<?php
namespace App\Services\MiniProgram;


use App\Services\Tool;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MiniProgram
{
    // 登录 根据 jsCode 获取用户 session 信息
    /**
     * 功能：登录
     * @param string $jsCode
     * @param string $encryptedData 需要解密的数据
     * @param int $expiryMinutes 数据的有效时间 ,单位：分钟;默认2分钟
     * @return array 结果
     *
     {
        "openId": "owfFF4ydu2HmuvmSDS4goIoAIYEs",
        "nickName": "笑对人生",
        "gender": 1,
        "language": "zh_CN",
        "city": "Xi'an",
        "province": "Shaanxi",
        "country": "China",
        "avatarUrl": "https://wx.qlogo.cn/mmopen/vi_32/E9ngLh0KSnSEwkBIIJ3ugheQNYJ4Im4fYvibYVY2uWXQVBCXQIclGOic4AttiaO1epPJKwWjgxtoO2YXaOiae0Dicicw/132",
        "unionId" : "ocMvos6NjeKLIBqg5Mr9QjxrP1FA"
        "watermark": {
            "timestamp": 1551201390,
            "appid": "wxcb82783fe211782f"
        },
       "session_key":"TBZO81wdyaWEOEOnqBfuPQ=="
    }
     *
     * @author zouyan(305463219@qq.com)
     */
    public static function login($jsCode, $encryptedData, $iv, $block = 'default', $expiryMinutes = 2){
        Log::info('微信日志-登陆参数:',[$jsCode, $iv, $encryptedData]);
        $app = app('wechat.mini_program.' . $block);
        $res = $app->auth->session($jsCode);// {"session_key":"TBZO81wdyaWEOEOnqBfuPQ==","openid":"owfFF4ydu2HmuvmSDS4goIoAIYEs"},可能还有unionId" -没有证实
        // json 转成数组
        jsonStrToArr($res , 1, '参数[data]格式有误!');
        Log::info('微信日志-session 还回结果',[$res]);
        if(!isset($res['session_key']) || empty($res['session_key'])) throws('调用接口session失败');
        $result = static::decryptData($app->config->app_id, $res['session_key'], $encryptedData, $iv, $expiryMinutes, true);
        if($result['code'] != 0 ){
            throws('小程序解密数据--失败,errCode[' . $result['code'] . ']');
        }
        $data = $result['data'] ?? [];
        $data = array_merge($data, $res);
        return $data;
    }

    /**
     * 功能：解密数据
     * @param string $appid
     * @param string $sessionKey
     * @param string $encryptedData 需要解密的数据
     * @param string $iv
     * @param int $expiryMinutes 数据的有效时间 ,单位：分钟;默认2分钟
     * @param boolean $throwErr 是否抛异常 true:抛；false：不抛
     * @return array 结果
     * [
            'code'=> $errCode,// 0：成功;非0:失败(1-参数与自身 appId 不一致;2--结束日期不是有效日期;3--时间比较出错, ....还有其它的)
            'data'=> $data,
        ]
     * @author zouyan(305463219@qq.com)
     */
    public static function decryptData($appid, $sessionKey, $encryptedData, $iv, $expiryMinutes = 2, $throwErr = false){

        /*
        $appid = 'wx4f4bc4dec97d474b';
        $sessionKey = 'tiihtNczf5v6AKRyjwEUhQ==';

        $encryptedData="CiyLU1Aw2KjvrjMdj8YKliAjtP4gsMZM
                QmRzooG2xrDcvSnxIMXFufNstNGTyaGS
                9uT5geRa0W4oTOb1WT7fJlAC+oNPdbB+
                3hVbJSRgv+4lGOETKUQz6OYStslQ142d
                NCuabNPGBzlooOmB231qMM85d2/fV6Ch
                evvXvQP8Hkue1poOFtnEtpyxVLW1zAo6
                /1Xx1COxFvrc2d7UL/lmHInNlxuacJXw
                u0fjpXfz/YqYzBIBzD6WUfTIF9GRHpOn
                /Hz7saL8xz+W//FRAUid1OksQaQx4CMs
                8LOddcQhULW4ucetDf96JcR3g0gfRK4P
                C7E/r7Z6xNrXd2UIeorGj5Ef7b1pJAYB
                6Y5anaHqZ9J6nKEBvB4DnNLIVWSgARns
                /8wR2SiRS7MNACwTyrGvt9ts8p12PKFd
                lqYTopNHR1Vf7XjfhQlVsAJdNiKdYmYV
                oKlaRv85IfVunYzO0IKXsyl7JCUjCpoG
                20f0a04COwfneQAGGwd5oa+T8yO5hzuy
                Db/XcxxmK01EpqOyuxINew==";

        $iv = 'r7BXXKkLb8qrSNn05n0qiA==';
        */

        $pc = new WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data );
        $returnArr = [
            'code'=> $errCode,
            'data'=> [],
        ];
        if ($errCode == 0) {// 成功
            Log::info('微信日志-小程序解密数据--成功',[$errCode, $data]);
            // print($data . "\n");
            // json 转成数组
            jsonStrToArr($data , 1, '参数[data]格式有误!');
            $returnArr['data'] = $data;
            // 校验此参数与自身 appId 是否一致
            if(isset($data['watermark']['appid']) && $data['watermark']['appid'] != $appid){
                Log::info('微信日志-小程序解密数据--失败',['校验此参数与自身 appId 不一致']);
                // $errCode = 1;
                $returnArr['code'] = 1;
                if($throwErr) throws('校验参数与自身 appId 不一致');
                return $returnArr;
            }
            // 敏感数据获取的时间戳, 开发者可以用于数据时效性校验
            if(isset($data['watermark']['timestamp'])){
                $time = $data['watermark']['timestamp'];// 1477314187;
                $end_date = judgeDate($time,"Y-m-d H:i:s");
                if($end_date === false){
                     // ajaxDataArr(0, null, '结束日期不是有效日期');
                    // 结束日期不是有效日期
                    Log::info('微信日志-小程序解密数据--失败',['结束日期不是有效日期']);
                    // $errCode = 2;
                    $returnArr['code'] = 2;
                    if($throwErr) throws('结束日期不是有效日期');
                    return $returnArr;
                }

                //$end_date = judgeDate($time,"Y-m-d H:i:s");
                $begin_date =  Carbon::now()->subMinutes($expiryMinutes);
                $reslut = Tool::judgeBeginEndDate($begin_date, $end_date,  1 + 2 + 4 + 32 + 256, 2, date('Y-m-d H:i:s'), '时间');
                // 出错
                if($reslut !== true){
                    Log::info('微信日志-小程序解密数据--失败',['有效日期失效']);
                    // $errCode = 3;
                    $returnArr['code'] = 3;
                    if($throwErr) throws('有效日期失效');
                    return $returnArr;
                }
            }
        }else{// 失败
            Log::info('微信日志-小程序解密数据--失败',[$errCode]);
            if($throwErr) throws('小程序解密数据--失败,errCode[' . $errCode . ']');
            // print($errCode . "\n");
        }
        return $returnArr;
    }

}