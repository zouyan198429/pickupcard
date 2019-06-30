<?php

namespace App\Models\RunBuy;

class DeliveryAddr extends BasePublicModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'delivery_addr';

    // 状态1未发货2已发货4已收货
    public $statusArr = [
        '1' => '未发货',
        '2' => '已发货',
        '4' => '已收货',
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
     * 获取收货地址对应的产品--一维
     */
    public function productInfo()
    {
        return $this->belongsTo('App\Models\RunBuy\Product', 'product_id', 'id');
    }

    /**
     * 获取收货地址对应的产品历史--一维
     */
    public function productHistoryInfo()
    {
        return $this->belongsTo('App\Models\RunBuy\ProductHistory', 'product_id_history', 'id');
    }
    /**
     * 获取地址对应的提货活动--一维
     */
    public function activityInfo()
    {
        return $this->belongsTo('App\Models\RunBuy\Activity', 'activity_id', 'id');
    }

    /**
     * 获取地址对应的兑换码--一维
     */
    public function codeInfo()
    {
        return $this->belongsTo('App\Models\RunBuy\ActivityCode', 'code_id', 'id')->withDefault();
    }
}
