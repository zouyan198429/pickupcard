<?php

namespace App\Models\RunBuy;

class Activity extends BasePublicModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'activity';

    // 状态1未开始2进行中4已结束
    public $statusArr = [
        '1' => '未开始',
        '2' => '进行中',
        '4' => '已结束',
    ];

    // 兑换码生成是是否启用1待启用2直接启用
    public $defaultOpenStatusArr = [
        '1' => '待启用',
        '2' => '直接启用',
    ];

    // 表里没有的字段
    protected $appends = ['status_text', 'default_open_status_text'];

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
     * 获取状态文字
     *
     * @return string
     */
    public function getDefaultOpenStatusTextAttribute()
    {
        return $this->defaultOpenStatusArr[$this->default_open_status] ?? '';
    }

    /**
     * 获取活动的兑换码-二维
     */
    public function codes()
    {
        return $this->hasMany('App\Models\RunBuy\ActivityCode', 'activity_id', 'id');
    }

    /**
     * 获取活动的收货地址-二维
     */
    public function addrs()
    {
        return $this->hasMany('App\Models\RunBuy\DeliveryAddr', 'activity_id', 'id');
    }


    /**
     * 获取活动对应的产品--一维
     */
    public function productInfo()
    {
        return $this->belongsTo('App\Models\RunBuy\Product', 'product_id', 'id');
    }

    /**
     * 获取活动对应的产品历史--一维
     */
    public function productHistoryInfo()
    {
        return $this->belongsTo('App\Models\RunBuy\ProductHistory', 'product_id_history', 'id');
    }
}
