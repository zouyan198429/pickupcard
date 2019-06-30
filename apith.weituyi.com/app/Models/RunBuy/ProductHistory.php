<?php

namespace App\Models\RunBuy;

class ProductHistory extends Product
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'product_history';

    /**
     * 获取产品历史的提货活动-二维
     */
    public function activitys()
    {
        return $this->hasMany('App\Models\RunBuy\Activity', 'product_id_history', 'id');
    }

    /**
     * 获取产品历史的兑换码-二维
     */
    public function codes()
    {
        return $this->hasMany('App\Models\RunBuy\ActivityCode', 'product_id_history', 'id');
    }

    /**
     * 获取产品历史的收货地址-二维
     */
    public function addrs()
    {
        return $this->hasMany('App\Models\RunBuy\DeliveryAddr', 'product_id_history', 'id');
    }

    /**
     * 获取历史对应的商品主表--一维
     */
    public function productinfo()
    {
        return $this->belongsTo('App\Models\RunBuy\Product', 'product_id', 'id');
    }

}
