<?php

namespace App\Console;

use App\Business\CompanyExamStaffBusiness;
use App\Business\CompanyWorkDoingBusiness;
use App\Business\DB\RunBuy\CityDBBusiness;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // everyThirtyMinutes() 每三十分钟运行一次任务 ->hourly() 每小时运行一次任务  ->everyFiveMinutes();	每五分钟运行一次任务

//         $schedule->command('inspire')
//                  ->hourly();
//        $filePath = '/data/CronResult.text';
//        $schedule->call(function () {
//            CompanyWorkDoingBusiness::autoSiteMsg();// 工单状态自动监控
//        })->everyMinute();// 每分钟执行一次 锁会在 5 分钟后失效->withoutOverlapping(5)[会失败] ;  ->appendOutputTo($filePath)
//        $schedule->call(function () {
//            CompanyExamStaffBusiness::autoExamStaff();// 在线考试自动交卷
//        })->everyMinute();
        $schedule->call(function () {
            CityDBBusiness::autoCityOnLine();// 跑城市店铺营业中脚本
        })->everyMinute();// ->everyFiveMinutes();//	每五分钟运行一次任务 ->everyMinute();
        $schedule->call(function () {
            CityDBBusiness::autoCityShopSalesVolume();// 跑城市店铺月销量最近30天脚本
        })->everyThirtyMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
