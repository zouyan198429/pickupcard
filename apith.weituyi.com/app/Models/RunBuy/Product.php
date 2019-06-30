<?php

namespace App\Models\RunBuy;

class Product extends BasePublicModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'product';

    /**
     * 获取产品的历史-二维
     */
    public function historys()
    {
        return $this->hasMany('App\Models\RunBuy\ProductHistory', 'product_id', 'id');
    }


    /**
     * 获取产品的提货活动-二维
     */
    public function activitys()
    {
        return $this->hasMany('App\Models\RunBuy\Activity', 'product_id', 'id');
    }

    /**
     * 获取产品的兑换码-二维
     */
    public function codes()
    {
        return $this->hasMany('App\Models\RunBuy\ActivityCode', 'product_id', 'id');
    }

    /**
     * 获取产品的收货地址-二维
     */
    public function addrs()
    {
        return $this->hasMany('App\Models\RunBuy\DeliveryAddr', 'product_id', 'id');
    }
}
