<?php
/**
 * Created by PhpStorm.通用操作工具
 * User: Administrator
 * Date: 2018-12-11
 * Time: 23:20
 */

namespace App\Services;


class Common
{

    /**
     * 根据域名，作url跳转
     * @param string $httpHost 当前访问域名
     * @param int $operate 操作类型 1跳到主页,2跳到登陆页
     * @return  string 跳转url
     * @author zouyan(305463219@qq.com)
     */
    public static function urlRedirect($httpHost, $operate = 1){
        $domains = config('public.domains');
        $urls = $domains[$httpHost] ?? [];

        switch ($operate) {
            //1跳到主页
            case 1:
                return $urls['indexUrl'];
                break;
            //跳到登陆页
            case 2:
                return $urls['loginUrl'];
                break;
            default:
                echo 'error';
                die();
            // return redirect("/");
        }
    }
}