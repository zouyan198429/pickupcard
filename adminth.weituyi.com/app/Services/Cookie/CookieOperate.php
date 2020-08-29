<?php
// 通用工具服务类-- 操作数据库
namespace App\Services\Cookie;

use App\Services\Tool;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * 通用工具服务类-- 操作数据库
 */
class CookieOperate
{
    // 获取cookie
    public static function get($key){
//        return Cookie::get($key);
//        $request->cookie('name');
        return  @$_COOKIE[$key];
    }

    // 设置
    // $expire 有效期，单位 秒
    public static function set($key, $val, $expire = 600 ){
        #方式一：推荐，不用再用response，可以再任一方法中都能使用，删除的时候需要注意下

//        Cookie::queue($key, $val, $expire);// 24 * 3600 * 7
#方式二：严重不推荐，此种方式必须return，在函数中return好像也不起作用
//        return response('Hello World')->cookie(
//            'name', 'value', $minutes, $path, $domain, $secure, $httpOnly
//        );
//        #方式三：和二差不多
//        $user_info = array('name'=>'laravel','age'=>12);
//        $user = Cookie::make('user',$user_info,30);
//        return Response::make()->withCookie($user);

        setcookie($key, $val,time() + $expire);     // user 为用户名，$user 为变量的值
    }

    public static function del($key){
        //这里有点大家要注意，由于我之前使用的\Cookie::queue('aid', '1111');删除时使用\Cookie::forget一直删除不了，这里使用方式一
        // 方式一：
//         Cookie::queue(\Cookie::forget($key));
        // 方式二：
        // $cookie = Cookie::forget('name');
        setcookie($key, '', time() -1);
    }


}
