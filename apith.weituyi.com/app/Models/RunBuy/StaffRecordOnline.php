<?php

namespace App\Models\RunBuy;

class StaffRecordOnline extends BasePublicModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'staff_record_online';

    // 是否上班 1下班2上班
    public $onLineArr = [
        '1' => '下班',
        '2' => '上班',
    ];
    // 表里没有的字段
    protected $appends = ['on_line_text'];

    /**
     * 获取用户上下班文字
     *
     * @return string
     */
    public function getOnLineTextAttribute()
    {
        return $this->onLineArr[$this->on_line] ?? '';
    }
}
