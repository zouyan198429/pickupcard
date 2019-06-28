<?php

namespace App\Models\RunBuy;

class LrChinaCity extends BasePublicModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'lr_china_city';

    // 状态 0新工单1待确认2待反馈工单[处理中];4待回访工单;8已完成工单
    public $status_arr = [
        '0' => '新工单',
        '1' => '待确认',
        '2' => '处理中',
        '4' => '待回访',
        '8' => '已完成',
    ];

}
