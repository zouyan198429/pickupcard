<?php

namespace App\Models\RunBuy;

class ActivityCode extends BasePublicModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'activity_code';

    // 状态1未兑换2已兑换4过期[不用吧]
    public $statusArr = [
        '1' => '未兑换',
        '2' => '已兑换',
        '4' => '过期',
    ];

    // 表里没有的字段
    protected $appends = ['status_text'];

    /**
     * 获取状态文字
     *
     * @return string
     */
    public function getStatusTextAttribute()
    {
        return $this->statusArr[$this->status] ?? '';
    }

    /**
     * 获取兑换码的收货地址--一维
     */
    public function addr()
    {
        return $this->hasOne('App\Models\RunBuy\DeliveryAddr', 'code_id', 'id');
    }

    /**
     * 获取兑换码对应的产品--一维
     */
    public function productInfo()
    {
        return $this->belongsTo('App\Models\RunBuy\Product', 'product_id', 'id');
    }

    /**
     * 获取兑换码对应的产品历史--一维
     */
    public function productHistoryInfo()
    {
        return $this->belongsTo('App\Models\RunBuy\ProductHistory', 'product_id_history', 'id');
    }

    /**
     * 获取兑换码对应的提货活动--一维
     */
    public function activityInfo()
    {
        return $this->belongsTo('App\Models\RunBuy\Activity', 'activity_id', 'id');
    }

}
