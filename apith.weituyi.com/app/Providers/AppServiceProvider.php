<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
        // 自定义多态类型
        Relation::morphMap([
//            'site_news'                 => 'App\Models\SiteNews',           // 站点新闻
//            'company_photo'             => 'App\Models\CompanyPhoto',       // 公司相册
//            'company_honor'             => 'App\Models\CompanyHonor',       // 公司荣誉
//            'company_menu'             => 'App\Models\CompanyProMenu',       // 公司菜单
//            'company_pro_config'        => 'App\Models\CompanyProConfig',   // 公司生产单元微站设置
//            'site_tiny_web_template'    => 'App\Models\SiteTinyWebTemplate',// 站点微店模板
//            'company_pro_report'        => 'App\Models\CompanyProReport',   // 检测报告
//            'company_pro_record'    => 'App\Models\CompanyProRecord',// 公司农事记录图片
//            'company_pro_record_pic'    => 'App\Models\CompanyProRecordPic',// 公司农事记录图片
//            'company_pro_input_pic'     => 'App\Models\CompanyProInputPic', // 公司生产投入品图片
//            'company_pro_input'         => 'App\Models\CompanyProInput',    // 公司生产投入品
//            'company_pro_unit'         => 'App\Models\CompanyProUnit',    // 公司生产单元
            'shop'                 => 'App\Models\RunBuy\Shop',           // 店铺图片
            'shop_goods'             => 'App\Models\RunBuy\ShopGoods',       // 商品图片
            'shop_type'             => 'App\Models\RunBuy\ShopType',       // 店铺分类
        ]);

        DB::listen(function ($query) {
            $sqlLog = [
                'sql'       => $query->sql,
                'bindings'  => $query->bindings,
                'time'      => $query->time,
            ];
            Log::info('sql执行日志',$sqlLog);
            /*
            echo '<pre>';
            print_r($query->sql);
            echo '<br/><br/>';
            print_r($query->bindings) ;
            echo '<br/><br/>';
            print_r($query->time);
            echo '<br/><br/>';
            echo '</pre>';
            */
            // $query->sql
            // $query->bindings
            // $query->time
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
